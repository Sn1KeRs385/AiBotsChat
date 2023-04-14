<?php

namespace App\Observers;

use App\Bots\Telegram\TelegramBotService;
use App\Enums\BotType;
use App\Enums\MessengerType;
use App\Enums\WalletTransactionType;
use App\Enums\WalletType;
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
                    $this->telegramBotService->notifyToExistsMessage(
                        BotType::GPT,
                        $gptChatMessage->notification_info->chatId,
                        $gptChatMessage->notification_info->messageId,
                        $gptChatMessage->content
                    );
                    break;
            }

            $this->withdrawBalanceWithNotify(
                $gptChatMessage,
                WalletType::GPT,
                $gptChatMessage->extra_data['usage']['total_tokens'] ?? 0
            );
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
                    $this->telegramBotService->notifyToExistsMessage(
                        BotType::GPT,
                        $gptChatMessage->notification_info->chatId,
                        $gptChatMessage->notification_info->messageId,
                        $content,
                        $gptChatMessage->is_stream_ended
                    );
                    break;
            }

            if ($gptChatMessage->is_stream_ended) {
                $amount = 0;
                $messages = $gptChatMessage->chat->messages()
                    ->orderBy('created_at')
                    ->get();
                foreach ($messages as $message) {
                    /** @var GptChatMessage $message */
                    $tokens = 0;

                    if ($message->extra_data) {
                        $tokens = $message->extra_data['usage']['total_tokens'] ?? 0;
                        if ($tokens === 0 && isset($message->extra_data[0])) {
                            $tokens = count($message->extra_data);
                        }
                    }

                    if ($tokens === 0) {
                        $tokens = (int)round(mb_strlen($message->content) / 1.6);
                    }

                    $amount = $amount + $tokens;
                }
                $this->withdrawBalanceWithNotify(
                    $gptChatMessage,
                    WalletType::GPT,
                    $amount
                );
            }
        }
    }

    protected function withdrawBalanceWithNotify(
        GptChatMessage $gptChatMessage,
        WalletType $walletType,
        int $amount
    ): void {
        $wallet = $gptChatMessage->chat->user->getOrCreateWalletByType($walletType);
        $wallet->createTransaction(
            WalletTransactionType::WITHDRAW_DEBT_POSSIBLE,
            $amount,
            [
                'payFor' => [
                    'type' => $gptChatMessage->getMorphClass(),
                    'id' => $gptChatMessage->id,
                ]
            ]
        );

        $content = "<b>Списано <code>{$amount}</code> токенов. Остаток: <code>{$wallet->getBalanceNormalize()}</code></b>";
        switch ($gptChatMessage->notification_info->type) {
            case MessengerType::TELEGRAM:
                $this->telegramBotService->notify(
                    BotType::GPT,
                    $gptChatMessage->notification_info->chatId,
                    $content
                );
                break;
        }
    }
}
