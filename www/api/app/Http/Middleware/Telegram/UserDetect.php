<?php

namespace App\Http\Middleware\Telegram;

use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Enums\IdentifierType;
use App\Models\UserIdentifier;
use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UserDetect
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = config('cache.config.authUser.key')
            .':'.IdentifierType::TG_USER_ID->value
            .':'.TelegramWebhook::getData()->getUser()->id;

        $user = Cache::remember($key, config('cache.config.authUser.ttl'), function () {
            return $this->userRepository->findOrCreateByIdentifier(
                IdentifierType::TG_USER_ID,
                TelegramWebhook::getData()->getUser()->id
            );
        });

        TelegramWebhook::setUser($user);
        $response = $next($request);

        /** @var UserIdentifier $identifier */
        $identifier = $user->identifiers()
            ->where('type', IdentifierType::TG_USER_ID)
            ->where('value', TelegramWebhook::getData()->getUser()->id)
            ->first();
        $identifier->extra_data = [
            'id' => TelegramWebhook::getData()->getUser()->id,
            'username' => TelegramWebhook::getData()->getUser()->username,
            'first_name' => TelegramWebhook::getData()->getUser()->first_name,
            'last_name' => TelegramWebhook::getData()->getUser()->last_name,
            'is_bot' => TelegramWebhook::getData()->getUser()->is_bot,
            'language_code' => TelegramWebhook::getData()->getUser()->language_code,
        ];
        $identifier->save();

        return $response;
    }
}
