<?php

namespace App\Bots\Telegram\Keyboards;


use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class ChatGptKeyboards
{
    public function getMainReplyKeyboard(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::make(resize_keyboard: true)
            ->addRow(
                KeyboardButton::make('🔄 Сбросить диалог'),
                KeyboardButton::make('💡 Возможности'),
            )
            ->addRow(
                KeyboardButton::make('👤 Аккаунт'),
                KeyboardButton::make('🛒 Магазин'),
            );
    }
}
