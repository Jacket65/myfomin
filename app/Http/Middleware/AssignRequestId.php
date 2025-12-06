<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AssignRequestId
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = (string) Str::ulid();

        // Добавляємо у контекст для логування
        Context::add('requestId', $requestId);

        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        // Додати заголовок
        $response->headers->set('X-Request-Id', $requestId);

        // Якщо відповідь JSON — вставляємо requestId
        if ($response->headers->get('Content-Type') === 'application/json' ||
            str_contains($response->headers->get('Content-Type'), 'application/json')) {

            $data = json_decode($response->getContent(), true);

            // Тільки якщо JSON валідний
            if (json_last_error() === JSON_ERROR_NONE) {

                // Додаємо у відповідь
                $data['requestId'] = $requestId;

                // Оновлюємо тіло відповіді
                $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE));
            }
        }

        return $response;
    }
}
