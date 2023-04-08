<?php

namespace Database\Seeders\Traits;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait EnumUpdate
{
    /**
     * @param  string[]  $values
     */
    public function updateEnum(string $table, string $colName, array $values): void
    {
        if (config('database.default') === 'mysql') {
            $values = array_map(fn($item) => "'$item'", $values);
            $values = implode(', ', $values);

            DB::statement(
                "ALTER TABLE `{$table}` CHANGE `{$colName}` `{$colName}` ENUM({$values}) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL"
            );
        } elseif (config('database.default') === 'pgsql') {
            DB::transaction(function () use ($table, $colName, $values) {
                $checkConstraintName = "{$table}_{$colName}_check";
                DB::statement("ALTER TABLE {$table} DROP CONSTRAINT {$checkConstraintName}");

                $availableTypes = join(
                    ', ',
                    array_map(function ($value) {
                        return sprintf("'%s'::character varying", $value);
                    }, $values)
                );

                DB::statement(
                    "ALTER TABLE {$table} ADD CONSTRAINT {$checkConstraintName} CHECK (type::text = ANY (ARRAY[$availableTypes]::text[]))"
                );
            });
        }
    }
}
