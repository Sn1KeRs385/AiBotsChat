<?php

namespace App\Observers;

use App\Bots\Telegram\TelegramBotService;
use App\Enums\BotType;
use App\Enums\MessengerType;
use App\Models\GptChatMessage;

class GptChatMessageObserver
{
    public function __construct(protected TelegramBotService $telegramBotService)
    {
    }

    public function created(GptChatMessage $gptChatMessage): void
    {
        if ($gptChatMessage->notification_info && !$gptChatMessage->is_stream) {
            switch ($gptChatMessage->notification_info->type) {
                case MessengerType::TELEGRAM:
                    $this->telegramBotService->notify(
                        BotType::GPT,
                        $gptChatMessage->notification_info,
                        $gptChatMessage->content
                    );
                    break;
            }
        }
    }

    public function updated(GptChatMessage $gptChatMessage): void
    {
        if ($gptChatMessage->notification_info && $gptChatMessage->is_stream) {
            $content = $gptChatMessage->content;
            if (!$gptChatMessage->is_stream_ended) {
                $content .= "\n...<b>Продолжение следует</b>...";
            }
            switch ($gptChatMessage->notification_info->type) {
                case MessengerType::TELEGRAM:
                    $this->telegramBotService->notify(
                        BotType::GPT,
                        $gptChatMessage->notification_info,
                        $content,
                        $gptChatMessage->is_stream_ended
                    );
                    break;
            }
        }
    }
}
