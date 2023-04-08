<?php

namespace App\Services;

use App\Models\Casts\TgUserState\UserStateData;
use App\Models\TgUserState;
use Illuminate\Support\Facades\Cache;

class TgUserStateService
{
    protected function getStateCacheKey(int $userId): string
    {
        return config('cache.config.tgUserState.key') . ":$userId";
    }

    public function getState(int $userId): TgUserState
    {
        $cacheKey = $this->getStateCacheKey($userId);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $state = TgUserState::firstWhere('user_id', $userId);

        if (!$state) {
            $state = new TgUserState();
            $state->user_id = $userId;
            $state->data = UserStateData::from([]);
            $state->save();
        }

        return $state;
    }

    public function saveState(TgUserState $state): void
    {
        $state->save();
        Cache::set($this->getStateCacheKey($state->user_id), $state, config('cache.config.tgUserState.ttl'));
    }
}
