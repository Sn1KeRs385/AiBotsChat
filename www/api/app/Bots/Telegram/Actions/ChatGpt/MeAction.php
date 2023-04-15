<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Bots\Telegram\Keyboards\ChatGptKeyboards;
use App\Enums\WalletType;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

class MeAction extends AbstractAction
{
    use ActionRouteInfoMapper;

    public function __construct(protected ChatGptKeyboards $keyboards)
    {
    }

    public function __invoke(): void
    {
        $balance = TelegramWebhook::getUser()
            ->getOrCreateWalletByType(WalletType::GPT)
            ->getBalanceNormalize();

        $balance = number_format($balance, 0, '.', ' ');

        $text = "*Баланс токенов:* `{$balance}`";

        TelegramWebhook::getBot()->sendMessage(
            $text,
            [
                'chat_id' => TelegramWebhook::getData()->getChat()->id,
                'parse_mode' => ParseMode::MARKDOWN,
                'reply_markup' => $this->keyboards->getMainReplyKeyboard(),
            ]
        );
    }

    public static function getPaths(): array
    {
        return [
            '/^\/me/u',
            '/^.{0,3}Аккаунт/u'
        ];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::MESSAGE];
    }
}
