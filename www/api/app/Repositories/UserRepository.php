<?php

namespace App\Repositories;

use App\Bots\Telegram\Facades\TelegramWebhook;
use App\Enums\IdentifierType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function findByIdentifier(IdentifierType $type, string $value): User|null
    {
        return User::query()
            ->whereHas('identifiers', function (Builder $query) use ($type, $value) {
                $query->where('type', $type)
                    ->where('value', $value);
            })
            ->first();
    }

    public function findOrCreateByIdentifier(IdentifierType $type, string $value): User
    {
        $user = $this->findByIdentifier($type, $value);
        
        if (!$user) {
            DB::transaction(function () use (&$user, $type, $value) {
                $user = new User();
                $user->save();
                $user->identifiers()
                    ->create([
                        'type' => $type,
                        'value' => $value
                    ]);
            });
        }

        return $user;
    }
}
