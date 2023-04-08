<?php

namespace App\Http\Resources\Model;

use App\Models\UserCode;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin UserCode
 */
class UserCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type' => $this->type,
            'code' => App::isProduction() ? null : (string)$this->code,
            'character_numbers' => $this->characterNumber,
            'is_number_code' => $this->isNumberCode,
        ];
    }
}
