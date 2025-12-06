<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class IdempotencyMiddleware
{
    const CACHE_TTL = 86400; // 60 * 60 * 24

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->method() !== 'POST') {
            return $next($request);
        }

        $idempotencyKey = $request->header('Idempotency-Key');

        if (!$idempotencyKey) {
            return $next($request);
        }


        $cacheKey = $this->generateCacheKey($request, $idempotencyKey);

        if (Cache::has($cacheKey)) {
            return $this->retrieveResponseFromCache($cacheKey);
        }


        Cache::put($cacheKey, ['status' => 'processing'], 10);

        $response = $next($request);

        $this->storeResponseInCache($cacheKey, $response);

        return $response;
    }


    protected function generateCacheKey(Request $request, string $idempotencyKey): string
    {
        $bodyHash = hash('sha256', json_encode($request->all()));

        return "idempotency:{$idempotencyKey}:{$request->path()}:{$bodyHash}";
    }


    protected function storeResponseInCache(string $key, Response $response): void
    {
        $data = [
            'status' => 'completed',
            'code' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            'content' => $response->getContent(),
        ];

        Cache::put($key, $data, self::CACHE_TTL);
    }

    /**
     * Формує відповідь з даних кешу.
     */
    protected function retrieveResponseFromCache(string $key): Response
    {
        $cachedData = Cache::get($key);

        if ($cachedData['status'] === 'processing') {
            abort(Response::HTTP_CONFLICT, 'The previous request with this key is still being processed.');
        }

        $response = new Response(
            $cachedData['content'],
            $cachedData['code']
        );

        $response->headers->replace($cachedData['headers']);

        $response->headers->set('X-Idempotency-Cached', 'true');

        return $response;
    }
}
