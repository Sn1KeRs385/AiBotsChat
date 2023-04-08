<?php

namespace App\Http\Middleware\Telegram;

use App\Exceptions\Bots\Telegram\AuthenticateFailedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Telegram-Bot-Api-Secret-Token');
        if (!$token || $token !== config('telegram.webhook_token')) {
            throw new AuthenticateFailedException;
        }

        return $next($request);
    }
}
