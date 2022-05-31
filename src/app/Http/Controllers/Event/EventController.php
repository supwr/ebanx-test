<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class EventController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function postEvent(): JsonResponse
    {
        return response()->json([]);
    }
}
