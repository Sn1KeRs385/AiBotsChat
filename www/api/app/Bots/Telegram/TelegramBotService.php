<?php

namespace App\Bots\Telegram;


use App\Enums\BotType;
use App\Models\Casts\NotificationInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;

class TelegramBotService
{

    public function notify(
        BotType $botType,
        NotificationInfo $notificationInfo,
        string $content,
        bool $force = false
    ): void {
        if (!$notificationInfo->chatId || !$notificationInfo->messageId) {
            return;
        }

        $bot = new TelegramBot(config("telegram.bot_api_keys.{$botType->getConfigEquivalent()}"));

        $blockKey = "TelegramBlock:Chat:{$notificationInfo->chatId}:Message:{$notificationInfo->messageId}";

        if ($force || !Cache::has($blockKey)) {
            $this->tryEditMessage(
                $bot,
                $content,
                $notificationInfo->chatId,
                $notificationInfo->messageId
            );
            Cache::add($blockKey, true, Carbon::now()->addSeconds(1));
        }
    }

    public function tryEditMessage(
        TelegramBot $bot,
        string $text,
        string $chatId,
        string $messageId,
    ) {
        try {
            $bot->editMessageText(
                $text,
                ['parse_mode' => ParseMode::MARKDOWN, 'chat_id' => $chatId, 'message_id' => $messageId]
            );
        } catch (\Throwable $exception) {
            try {
                $bot->editMessageText(
                    $text,
                    ['parse_mode' => ParseMode::MARKDOWN_LEGACY, 'chat_id' => $chatId, 'message_id' => $messageId]
                );
            } catch (\Throwable $exception) {
                try {
                    $bot->editMessageText(
                        $text,
                        ['parse_mode' => ParseMode::HTML, 'chat_id' => $chatId, 'message_id' => $messageId]
                    );
                } catch (\Throwable $exception) {
                }
            }
        }
    }
}
