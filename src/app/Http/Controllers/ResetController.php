<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Account\ResetAccountBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class ResetController extends BaseController
{
    public function __construct(
        private ResetAccountBalanceService $resetBalanceService
    ) {
    }
    /**
     * @return JsonResponse
     */
    public function reset(): Response
    {
        $this->resetBalanceService->reset();
        return response('OK');
    }
}
