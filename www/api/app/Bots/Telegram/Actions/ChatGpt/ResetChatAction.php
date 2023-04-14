<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Bots\Telegram\Keyboards\ChatGptKeyboards;
use App\Models\GptChat;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

class ResetChatAction extends AbstractAction
{
    use ActionRouteInfoMapper;

    public function __construct(protected ChatGptKeyboards $keyboards)
    {
    }

    public function __invoke(): void
    {
        GptChat::create(['user_id' => TelegramWebhook::getUser()->id]);

        $text = "<b>Диалог сброшен</b>";

        TelegramWebhook::getBot()->sendMessage(
            $text,
            [
                'chat_id' => TelegramWebhook::getData()->getChat()->id,
                'parse_mode' => ParseMode::HTML,
                'reply_markup' => $this->keyboards->getMainReplyKeyboard(),
            ]
        );
    }

    public static function getPaths(): array
    {
        return [
            '/^\/reset_chat/u',
            '/^.{0,3}Сбросить диалог/u'
        ];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::MESSAGE];
    }
}
