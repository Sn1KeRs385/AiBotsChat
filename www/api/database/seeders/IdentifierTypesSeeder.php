<?php

namespace Database\Seeders;

use Database\Seeders\Traits\EnumUpdate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class IdentifierTypesSeeder extends Seeder
{
    use EnumUpdate;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availableTypes = Arr::pluck(\App\Enums\IdentifierType::cases(), 'value');

        $this->updateEnum('user_identifiers', 'type', $availableTypes);
    }
}
