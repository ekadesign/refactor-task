<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\CancelLoyaltyPointsDto;
use App\DTO\PaymentLoyaltyPointsDto;
use App\DTO\WithdrawLoyaltyPointsDto;
use App\Exceptions\AccountNotActiveException;
use App\Http\Requests\CancelLoyaltyPointsRequest;
use App\Http\Requests\PaymentLoyaltyPointsRequest;
use App\Http\Requests\WithdrawLoyaltyPointsRequest;
use App\Http\Resources\LoyaltyPointsTransactionResource;
use App\Services\LoyaltyPointsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LoyaltyPointsController extends Controller
{
    public function __construct(
        private LoyaltyPointsService $loyaltyPointsService
    ) {}

    public function deposit(PaymentLoyaltyPointsRequest $request): LoyaltyPointsTransactionResource|JsonResponse
    {
        $paymentLoyaltyPointsDto = new PaymentLoyaltyPointsDto($request->validated());

        Log::info('Deposit transaction input: ' . print_r($paymentLoyaltyPointsDto->toArray(), true));

        try {
            return $this->loyaltyPointsService->addLoyaltyPoints($paymentLoyaltyPointsDto);
        } catch (ModelNotFoundException) {
            Log::info('Account is not found');
            return response()->json(['message' => 'Account is not found'], Response::HTTP_NOT_FOUND);
        } catch (AccountNotActiveException $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function cancel(CancelLoyaltyPointsRequest $request): LoyaltyPointsTransactionResource|JsonResponse
    {
        $cancelLoyaltyPointsDto = new CancelLoyaltyPointsDto($request->validated());

        try {
            return $this->loyaltyPointsService->rollbackLoyaltyPoints($cancelLoyaltyPointsDto);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Transaction is not found'], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function withdraw(WithdrawLoyaltyPointsRequest $request): LoyaltyPointsTransactionResource|JsonResponse
    {
        $withdrawLoyaltyPointsDto = new WithdrawLoyaltyPointsDto($request->validated());

        Log::info('Withdraw loyalty points transaction input: ' . print_r($withdrawLoyaltyPointsDto->toArray(), true));

        try {
            return $this->loyaltyPointsService->withdrawLoyaltyPoints($withdrawLoyaltyPointsDto);
        } catch (ModelNotFoundException) {
            Log::info('Account is not found:' . $withdrawLoyaltyPointsDto->getAccountType() . ' ' . $withdrawLoyaltyPointsDto->getId());
            return response()->json(['message' => 'Account is not found'], Response::HTTP_NOT_FOUND);
        } catch (AccountNotActiveException $e) {
            Log::info('Account is not active: ' . $withdrawLoyaltyPointsDto->getAccountType() . ' ' . $withdrawLoyaltyPointsDto->getId());
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
