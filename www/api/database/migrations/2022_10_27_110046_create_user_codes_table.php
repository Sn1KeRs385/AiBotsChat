<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', Arr::pluck(\App\Enums\CodeType::cases(), 'value'));
            $table->string('code');
            $table->timestamp('used_at')
                ->nullable();
            $table->timestamp('expired_at')
                ->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->index(['type', 'code', 'expired_at', 'used_at']);
            $table->index(['user_id', 'type', 'code', 'expired_at', 'used_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_codes');
    }
};
