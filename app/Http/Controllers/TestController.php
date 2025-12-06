<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
                $errorChancePercent = 40;
        $r = random_int(1, 100);
        echo 1;
        if ($r <= $errorChancePercent) {
            $codes = [500, 502, 503, 504];
            $code = $codes[array_rand($codes)];

            return response()->json([
                'error' => 'server_error',
                'message' => 'Simulated server failure for testing retries',
                'attemptRandom' => $r,
            ], $code);
        }

        // Успішна відповідь
        return response()->json([
            'result' => 'ok',
            'message' => 'Successful response from unstable endpoint',
            'attemptRandom' => $r,
        ], 200);
    }
}
