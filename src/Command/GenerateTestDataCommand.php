<?php

declare(strict_types=1);

namespace App\Command;

use App\Application\Factory\ClientFactory;
use App\Entity\Account;
use App\Entity\Client;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-fake-data',
    description: 'Populates DB',
    aliases: ['app:add-data'],
    hidden: false
)]
final class GenerateTestDataCommand extends Command
{
    private ClientRepository $clients;
    private AccountRepository $accounts;

    public function __construct(ClientRepository $clients, AccountRepository $accounts)
    {
        parent::__construct();

        $this->clients = $clients;
        $this->accounts = $accounts;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generates a number of test clients.')
            ->addArgument('count', InputArgument::REQUIRED, 'The number of clients to generate');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = (int)$input->getArgument('count');

        for ($i = 0; $i < $count; ++$i) {
            $client = $this->generateClient();

            $output->writeln("Created client: {$client->getId()}");

            $accountCount = random_int(0, 5);

            if ($accountCount < 1) {
                continue;
            }

            for ($j = 0; $j < $accountCount; ++$j) {
                $sender = $this->generateAccount($client);
                $receiver = $this->generateAccount($client);

                $output->writeln("Created account: {$sender->getCurrency()} {$sender->getBalance()}");
                $output->writeln("Created account: {$receiver->getCurrency()} {$receiver->getBalance()}");
            }
        }

        return Command::SUCCESS;
    }

    private function generateClient(): Client
    {
        $client = ClientFactory::create();

        $this->clients->add($client);

        return $client;
    }

    private function generateAccount(Client $client): Account
    {
        $currencies = ['USD', 'EUR', 'GBP'];

        $currency = $currencies[random_int(0, 2)];
        $amount = random_int(100, 100000);

        $account = new Account($client, $currency, $amount);

        $this->accounts->add($account);

        return $account;
    }

}
