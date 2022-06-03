<?php

declare(strict_types=1);

namespace App\Http\Controllers\Balance;

use App\Services\Balance\GetBalanceService;
use App\Services\Exceptions\BalanceNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class BalanceController extends BaseController
{
    /**
     * @param GetBalanceService $balanceService
     */
    public function __construct(private GetBalanceService $balanceService)
    {
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \App\Services\Exceptions\BalanceNotFoundException
     */
    public function getBalance(Request $request): Response
    {
        $accountId = (int) $request->input('account_id', 0);

        try {
            $balance = $this->balanceService->getBalance($accountId);
        } catch (BalanceNotFoundException) {
            return response(0, Response::HTTP_NOT_FOUND);
        }

        return response($balance->toFloat());
    }
}
