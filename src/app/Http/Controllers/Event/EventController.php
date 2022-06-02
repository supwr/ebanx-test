<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Entities\TransactionFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Services\Event\DepositService;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __construct(private DepositService $depositService)
    {
    }

    /**
     * @param TransactionRequest $request
     * @return JsonResponse
     */
    public function postEvent(TransactionRequest $request): JsonResponse
    {
        $this->depositToAccount($request);
        return response()->json([]);
    }

    private function depositToAccount(TransactionRequest $request): void
    {
        $transaction = TransactionFactory::fromArray([
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'origin' => $request->input('destination'),
            'destination' => $request->input('destination')
        ]);

        $this->depositService->deposit($transaction);
    }
}
