<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\DTO\Transaction\DepositOutputDTO;
use App\DTO\Transaction\OutputDTOInterface;
use App\DTO\Transaction\TransferOutputDTO;
use App\DTO\Transaction\WithdrawOutputDTO;
use App\Entities\Transaction;
use App\Entities\TransactionFactory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Exceptions\InvalidTransactionTypeException;
use App\Http\Requests\TransactionRequest;
use App\Services\Balance\GetBalanceService;
use App\Services\Event\DepositService;
use App\Services\Event\TransferService;
use App\Services\Event\WithdrawService;
use App\Services\Exceptions\AccountNotFoundException;
use App\ValueObjects\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EventController extends Controller
{
    public function __construct(
        private DepositService $depositService,
        private WithdrawService $withdrawService,
        private TransferService $transferService,
        private GetBalanceService $balanceService
    ) {
    }

    /**
     * @param TransactionRequest $request
     * @return JsonResponse|Response
     * @throws InvalidTransactionTypeException
     * @throws \App\Services\Exceptions\BalanceNotFoundException
     * @throws \App\Services\Exceptions\DepositServiceException
     * @throws \App\Services\Exceptions\WithdrawServiceException
     */
    public function postEvent(TransactionRequest $request): JsonResponse|Response
    {
        $type = $request->input('type', '');
        $response = match ($type) {
            TransactionType::DEPOSIT => $this->depositToAccount($request),
            TransactionType::WITHDRAW => $this->withdrawFromAccount($request),
            TransactionType::TRANSFER => $this->transferFromAccount($request),
            default => throw new InvalidTransactionTypeException(
                message: sprintf('Invalid transaction type [%s]', $type)
            )
        };

        if ($response instanceof OutputDTOInterface) {
            return response()->json($response->toArray(), ResponseAlias::HTTP_CREATED);
        }

        return $response;
    }

    /**
     * @param TransactionRequest $request
     * @return WithdrawOutputDTO|Response
     * @throws \App\Services\Exceptions\BalanceNotFoundException
     * @throws \App\Services\Exceptions\WithdrawServiceException
     */
    private function withdrawFromAccount(TransactionRequest $request): WithdrawOutputDTO|Response
    {
        $transaction = $this->getTransactionFromRequest($request);

        try {
            $this->withdrawService->withdraw($transaction);
        } catch (AccountNotFoundException $e) {
            return response(0, ResponseAlias::HTTP_NOT_FOUND);
        }

        $balance = $this->balanceService->getBalance($transaction->origin->toInt());

        return WithdrawOutputDTO::fromArray([
            'account_id' => $transaction->origin->toInt(),
            'balance' => $balance->toFloat()
        ]);
    }

    private function transferFromAccount(TransactionRequest $request): TransferOutputDTO|Response
    {
        $transaction = $this->getTransactionFromRequest($request);

        try {
            $this->transferService->transfer($transaction);
        } catch (AccountNotFoundException $e) {
            return response(0, ResponseAlias::HTTP_NOT_FOUND);
        }

        $originBalance = $this->balanceService->getBalance($transaction->origin->toInt());
        $destinationBalance = $this->balanceService->getBalance($transaction->destination->toInt());

        return TransferOutputDTO::fromArray([
            'origin_account_id' => $transaction->origin->toInt(),
            'origin_account_balance' => $originBalance->toFloat(),
            'destination_account_id' => $transaction->destination->toInt(),
            'destination_account_balance' => $destinationBalance->toFloat()
        ]);
    }

    /**
     * @param TransactionRequest $request
     * @return DepositOutputDTO
     * @throws \App\Services\Exceptions\BalanceNotFoundException
     * @throws \App\Services\Exceptions\DepositServiceException
     */
    private function depositToAccount(TransactionRequest $request): DepositOutputDTO
    {
        $transaction = $this->getTransactionFromRequest($request);

        $this->depositService->deposit($transaction);

        $balance = $this->balanceService->getBalance($transaction->destination->toInt());

        return DepositOutputDTO::fromArray([
            'account_id' => $transaction->destination->toInt(),
            'balance' => $balance->toFloat()
        ]);
    }

    /**
     * @param TransactionRequest $request
     * @return Transaction
     */
    private function getTransactionFromRequest(TransactionRequest $request): Transaction
    {
        return TransactionFactory::fromArray([
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'origin' => $request->input('origin'),
            'destination' => $request->input('destination')
        ]);
    }
}
