<?php

declare(strict_types=1);

namespace App\Http\Controllers\Balance;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class BalanceController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function getBalance(): JsonResponse
    {
        return response()->json([]);
    }
}
