<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_identifiers', function (Blueprint $table) {
            $table->jsonb('extra_data')
                ->nullable()
                ->default(null);

            $table->index(['user_id', 'type', 'value', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_identifiers', function (Blueprint $table) {
            $table->dropColumn('extra_data');
            $table->dropIndex(['user_id', 'type', 'value', 'deleted_at']);
        });
    }
};
