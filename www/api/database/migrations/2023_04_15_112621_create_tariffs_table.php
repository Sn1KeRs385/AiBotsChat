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
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->enum('wallet_type', Arr::pluck(\App\Enums\WalletType::cases(), 'value'));
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('deposit');
            $table->boolean('is_active')
                ->default(false);
            $table->integer('weight')
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};
