<?php

namespace App\Http\Middleware\Telegram;

use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Services\TgUserStateService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WithState
{
    public function __construct(protected TgUserStateService $stateService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $state = $this->stateService->getState(TelegramWebhook::getUser()->id);
        TelegramWebhook::setState($state);

        $response = $next($request);

        $state = TelegramWebhook::getState();
        $this->stateService->saveState($state);

        return $response;
    }
}
