<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\CodeType;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $code
 */
class GetTokensByCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                Rule::exists('user_codes', 'code')
                    ->where('type', CodeType::AUTH->value)
                    ->whereNull('used_at')
                    ->where(function (Builder $query) {
                        $query->whereNull('expired_at')
                            ->orWhere('expired_at', '>', Carbon::now());
                    })
            ]
        ];
    }
}
