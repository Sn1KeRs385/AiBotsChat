<?php

return [
    'telegram' => [
        'authenticate_failed' => 'Ошибка авторизации. X-Telegram-Bot-Api-Secret-Token отсутствует либо указан неверно.',
    ],
    'gpt_chat' => [
        'message_cannot_be_empty' => 'Текст сообщения не может быть пустым',
    ],
    'wallet' => [
        'wallet_already_exists' => 'Кошелек уже существует',
        'transaction_type_create_method_not_implement' => 'Метод для этого типа транзакции не реализован',
        'transaction_amount_must_be_greater_than_zero' => 'Сумма транзакции может быть больше нуля',
        'not_enough_founds' => 'Недостаточно средств на счете',
    ],
    'code_not_found' => 'Код не найден',
    'has_unready_file_on_model' => 'Имеются незагруженные файлы',
];
