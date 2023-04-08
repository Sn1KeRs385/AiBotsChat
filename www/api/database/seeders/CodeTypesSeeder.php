<?php

namespace Database\Seeders;

use Database\Seeders\Traits\EnumUpdate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CodeTypesSeeder extends Seeder
{
    use EnumUpdate;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availableTypes = Arr::pluck(\App\Enums\CodeType::cases(), 'value');

        $this->updateEnum('user_codes', 'type', $availableTypes);
    }
}
