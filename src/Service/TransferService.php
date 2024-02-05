<?php

namespace App\Service;

use App\CurrencyConverter\Contract\CurrencyConverterInterface;
use App\Entity\Account;
use App\Entity\DTO\MoneyAmountTransferDTO;
use App\Event\AccountBalanceUpdated;
use App\Exceptions\AccountAmountTransferFailedUnexpectedly;
use App\Exceptions\AccountHasInsufficientFunds;
use App\Exceptions\AccountNotFound;
use App\Exceptions\TransactionValidationException;
use App\Repository\AccountRepository;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Currency;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

class TransferService
{
    const string ERROR_ACCOUNT_MISMATCH = "You Cant Send To Yourself";


    public function __construct(
        private readonly AccountRepository          $accounts,
        private readonly CurrencyConverterInterface $currencyConverter,
        private readonly EventDispatcherInterface   $eventDispatcher
    )
    {
    }

    public function transfer(MoneyAmountTransferDTO $command): void
    {
        $sourceAccount = $this->accounts->findAccountOrFail($command->sourceAccountId());
        $targetAccount = $this->accounts->findAccountOrFail($command->targetAccountId());
        $this->validate($sourceAccount, $targetAccount);
        $this->accounts->beginTransaction();
        try {
            $this->transferMoney($sourceAccount, $targetAccount, $command);
            $this->accounts->commit();
        } catch (AccountNotFound|AccountHasInsufficientFunds $exception) {
            $this->handleExceptionAndRollback($exception);
        } catch (Throwable $exception) {
            $this->handleExceptionAndRollback(AccountAmountTransferFailedUnexpectedly::from($exception));
        }
    }

    private function validate(Account $sourceAccount, Account $targetAccount): void
    {
        if ($sourceAccount->getId() === $targetAccount->getId()) {
            throw new TransactionValidationException(self::ERROR_ACCOUNT_MISMATCH, 400);
        }
    }


    private function transferMoney(Account $sourceAccount, Account $targetAccount, MoneyAmountTransferDTO $command): void
    {
        try {
            $sourceBalanceAmount = $sourceAccount->fromBalanceTransformerMoneyAmount();
            $targetBalanceAmount = $targetAccount->fromBalanceTransformerMoneyAmount();

            $amountToTransfer = Money::of($command->amount(), Currency::of($sourceAccount->getCurrency()));

            // Check if there are sufficient funds
            if ($sourceBalanceAmount->isLessThan($amountToTransfer)) {
                throw AccountHasInsufficientFunds::byId($command->sourceAccountId());
            }

            // Check if currency conversion is needed
            if (!$sourceBalanceAmount->getCurrency()->is($targetBalanceAmount->getCurrency())) {
                $convertedAmount = $this->currencyConverter->convert($amountToTransfer, Currency::of($targetAccount->getCurrency()));
            }

            $this->updateSourceBalance($sourceAccount, sourceBalance: $sourceBalanceAmount->minus($amountToTransfer)->getMinorAmount()->toInt());
            $this->updateTargetBalance($sourceAccount, targetBalance: $targetBalanceAmount->plus($convertedAmount ?? $amountToTransfer)->getMinorAmount()->toInt());

            $this->dispatchAccountBalanceUpdatedEvent(sourceAccount: $sourceAccount, targetAccount: $targetAccount, amount: $amountToTransfer->getMinorAmount()->toInt(), convertedAmount: $convertedAmount ?? null);

        } catch (MoneyMismatchException|RoundingNecessaryException|NumberFormatException|MathException|UnknownCurrencyException $e) {
            $this->handleExceptionAndRollback($e);
        }
    }

    private function updateSourceBalance(Account $sourceAccount, int $sourceBalance): void
    {
        $sourceAccount->setBalance($sourceBalance);
        $this->accounts->update($sourceAccount);
    }

    private function updateTargetBalance(Account $targetAccount, int $targetBalance): void
    {
        $targetAccount->setBalance($targetBalance);
        $this->accounts->update($targetAccount);
    }

    public function dispatchAccountBalanceUpdatedEvent(Account $sourceAccount, Account $targetAccount, string $amount, Money $convertedAmount = null): void
    {
        $event = new AccountBalanceUpdated(
            sourceAccountId: $sourceAccount->getId(),
            targetAccountId: $targetAccount->getId(),
            amount: $amount,
            convertedAmount: $convertedAmount?->getMinorAmount()->toInt()
        );
        $this->eventDispatcher->dispatch($event);
    }

    private function handleExceptionAndRollback($exception)
    {
        $this->accounts->rollback();
        throw $exception;
    }
}