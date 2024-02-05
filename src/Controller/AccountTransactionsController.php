<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Util\SearchPagination;
use App\Entity\Util\SearchSorting;
use App\Entity\Util\SortingOrder;
use App\Entity\Util\TransactionSearchCriteria;
use App\Repository\TransactionRepository;
use App\Request\TransactionListRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class AccountTransactionsController extends AbstractController
{
    #[OA\Get(
        summary: 'Get Account Transactions',
        tags: ['Account'],
        parameters: [new OA\Parameter(name: 'accountId', description: 'Account Identifier', in: 'path'),
            new OA\Parameter(
                name: 'order',
                description: 'Sorting order direction',
                in: 'query',
                schema: new OA\Schema(type: 'string', enum: SortingOrder::SORTING),
            ),
            new OA\Parameter(
                name: 'limit',
                description: 'Result items limit',
                in: 'query',
            ),
            new OA\Parameter(
                name: 'offset',
                description: 'Result items offset',
                in: 'query',
            ),
        ],
        responses: [
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: Response::HTTP_FORBIDDEN, description: 'Forbidden'),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Not Found'),
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'OK',
            ),
        ],
    )]
    #[Route('/api/accounts/{accountId}/transactions', name: 'accounts.transactions.index', methods: ['GET'])]
    public function __invoke(
        Request               $request,
        int                   $accountId,
        TransactionRepository $transactionRepository,
    ): JsonResponse
    {
        $request = new TransactionListRequest($accountId, (int)$request->query->get('limit'), (int)$request->query->get('offset'));
        $transactions = $transactionRepository->findByCriteria(new TransactionSearchCriteria(
            accountId: $accountId,
            sorting: new SearchSorting('created_at', 'desc'),
            pagination: new SearchPagination($request->limit(), $request->offset()),
        ));
        return $this->json([
            'transactions' => array_map(function (Transaction $transaction) use ($accountId) {
                return [
                    'id' => $transaction->getId(),
                    'type' => $transaction->getType(),
                    'sender' => $transaction->getSender()->getId(),
                    'receiver' => $transaction->getReceiver()->getId(),
                    'currency' => $transaction->getCurrency(),
                    'amount' => $transaction->getAmount(),
                    'date' => $transaction->getCreatedAt(),
                ];
            }, $transactions),
        ]);
    }
}