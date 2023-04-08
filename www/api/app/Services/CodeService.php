<?php

namespace App\Services;

use App\Enums\CodeType;
use App\Exceptions\CodeNotFoundException;
use App\Models\User;
use App\Models\UserCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CodeService
{
    /**
     * @param  User  $user
     * @param  CodeType  $type
     * @param  int  $characterNumber
     * @param  bool  $onlyNumbers
     * @return UserCode
     */
    public function sendCode(User $user, CodeType $type, int $characterNumber = 4, bool $onlyNumbers = true): UserCode
    {
        if ($onlyNumbers) {
            $maxCode = 0;
            for ($i = 0; $i < $characterNumber; $i++) {
                $maxCode = $maxCode + 9 * pow(10, $i);
            }
            $code = rand(pow(10, $characterNumber - 1), $maxCode);
        } else {
            $code = Str::random($characterNumber);
        }

        /** @var UserCode $code */
        $code = $user->codes()
            ->create(
                [
                    'type' => $type,
                    'code' => $code,
                    'expired_at' => Carbon::now()->addSeconds(config('code.expiration')),
                ]
            );

        return $code;
    }

    /**
     * @param  string  $code
     * @return UserCode
     * @throws CodeNotFoundException
     */
    public function useCode(string $code): UserCode
    {
        $userCode = UserCode::query()
            ->where('type', CodeType::AUTH)
            ->where('code', $code)
            ->whereNull('used_at')
            ->where(function (Builder $query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', Carbon::now());
            })
            ->first();

        if (!$userCode) {
            throw new CodeNotFoundException();
        }

        $userCode->used_at = Carbon::now();
        $userCode->save();

        return $userCode;
    }
}
