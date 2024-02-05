<?php

namespace App\Controller;

use App\Entity\DTO\MoneyAmountTransferDTO;
use App\Request\MoneyTransferRequest;
use App\Service\TransferService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[AsController]
class TransferController extends AbstractController
{
    public function __construct(private readonly TransferService $transferService)
    {
    }

    public function transfer(
        Request $request,
    ): JsonResponse
    {
        try {
            $request = new MoneyTransferRequest(json_decode($request->getContent(), true));
            $this->transferService->transfer(new MoneyAmountTransferDTO(
                $request->sourceAccountId(),
                $request->targetAccountId(),
                $request->amount()
            ));
            return $this->json(['success' => true, 'message' => 'Successfully Transferred'], Response::HTTP_OK);
        } catch (Throwable $exception) {
            // exception logging might be implemented here
            return $this->json(['success' => false, 'message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}