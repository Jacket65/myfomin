<?php

namespace App\Providers;

use App\Http\Middleware\IdempotencyMiddleware;
use App\Models\Account;
use App\Policies\AccountPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
class AppServiceProvider extends ServiceProvider
{


    protected $policies = [
        Account::class => AccountPolicy::class,

    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('global', function (Request $request) {
            return Limit::perSecond(2)
                ->by($request->ip())
                ->response(function () {
                    $retryAfter = 1;

                    return response()->json([
                        'message' => 'Too many requests',
                        'retry_after' => $retryAfter
                    ], 429)->header('Retry-After', $retryAfter);
                });
        });
    }
}
