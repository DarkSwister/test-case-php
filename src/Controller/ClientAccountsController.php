<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Util\AccountSearchCriteria;
use App\Entity\Util\SearchPagination;
use App\Entity\Util\SortingOrder;
use App\Repository\AccountRepository;
use App\Request\AccountListRequest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class ClientAccountsController extends AbstractController
{
    #[OA\Get(
        summary: 'Get client accounts by Identifier',
        tags: ['Client'],
        parameters: [new OA\Parameter(name: 'clientId', description: 'Client identifier', in: 'path'),
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
            ),],
        responses: [
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: Response::HTTP_FORBIDDEN, description: 'Forbidden'),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Not Found'),
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'OK',
                headers: [
                    new OA\Header(
                        header: 'X-Total-Count',
                        description: 'Total count of items without limit',
                        schema: new OA\Schema(type: 'int', example: '10'),
                    ),
                ],
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Account::class)),
                ),
            ),
        ],
    )]
    #[Route('/api/clients/{clientId}/accounts', name: 'clients.accounts.index', methods: ['GET'], format: 'JSON')]
    public function __invoke(
        Request $request,
        int     $clientId, AccountRepository $accountRepository
    ): JsonResponse
    {
        $request = new AccountListRequest($clientId, (int)$request->query->get('limit'), (int)$request->query->get('offset'));
        $accounts = $accountRepository->findByCriteria(new AccountSearchCriteria(
            $clientId,
            pagination: new SearchPagination($request->limit(), $request->offset()),
        ));
        return $this->json([
            'accounts' => array_map(function (Account $account) {
                return [
                    'id' => $account->getId(),
                    'client' => $account->getClient()->getId(),
                    'balance' => $account->getBalance(),
                    'currency' => $account->getCurrency(),
                ];
            }, $accounts),
        ]);
    }
}