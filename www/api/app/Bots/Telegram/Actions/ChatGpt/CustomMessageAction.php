<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Enums\GptRole;
use App\Enums\MessengerType;
use App\Exceptions\Models\GptChat\MessageCannotBeEmpty;
use App\Models\Casts\NotificationInfo;
use App\Services\GptChatService;
use SergiX44\Nutgram\Telegram\Attributes\ChatActions;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

class CustomMessageAction extends AbstractAction
{
    use ActionRouteInfoMapper;

    public function __construct(protected GptChatService $gptChatService)
    {
    }

    public function __invoke(): void
    {
        if (!TelegramWebhook::getData()->getMessage()->text) {
            TelegramWebhook::getBot()->sendMessage(
                'Сообщение не может быть пустым',
                [
                    'chat_id' => TelegramWebhook::getData()->getChat()->id,
                    'parse_mode' => ParseMode::HTML,
                    'reply_to_message_id' => TelegramWebhook::getData()->getMessage()->message_id,
                ]
            );
            return;
        }

        $messageForResult = TelegramWebhook::getBot()->sendMessage(
            '<b>Печатаю...</b>',
            [
                'chat_id' => TelegramWebhook::getData()->getChat()->id,
                'parse_mode' => ParseMode::HTML,
                'reply_to_message_id' => TelegramWebhook::getData()->getMessage()->message_id,
            ]
        );

        TelegramWebhook::getBot()->sendChatAction(
            ChatActions::TYPING,
            ['chat_id' => TelegramWebhook::getData()->getChat()->id]
        );

        try {
            $this->gptChatService->addMessageToChatByUser(
                TelegramWebhook::getData()->getMessage()->text,
                TelegramWebhook::getUser(),
                GptRole::USER,
                NotificationInfo::from(
                    [
                        'type' => MessengerType::TELEGRAM,
                        'chatId' => $messageForResult->chat->id,
                        'messageId' => $messageForResult->message_id
                    ]
                ),
            );
        } catch (\Throwable $exception) {
            $text = 'Произошла неизвестная ошибка, попробуйте повторить попытку позже.';
            if ($exception instanceof MessageCannotBeEmpty) {
                $text = $exception->getMessage();
            }
            TelegramWebhook::getBot()->editMessageText($text, [
                'chat_id' => $messageForResult->chat->id,
                'message_id' => $messageForResult->message_id,
            ]);
            throw $exception;
        }
    }

    public static function getPaths(): array
    {
        return [];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::MESSAGE];
    }
}
