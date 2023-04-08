<?php

namespace App\Http\Middleware\Telegram;

use App\Bots\Telegram\Facades\TelegramWebhook;
use Closure;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Telegram\Attributes\ChatActions;
use Symfony\Component\HttpFoundation\Response;

class SetChatAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        TelegramWebhook::getBot()->sendChatAction(
            ChatActions::TYPING,
            [
                'chat_id' => TelegramWebhook::getData()->getChat()->id
            ]
        );

        return $next($request);
    }
}
