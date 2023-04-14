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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->enum('type', Arr::pluck(\App\Enums\WalletTransactionType::cases(), 'value'));
            $table->unsignedBigInteger('amount');
            $table->jsonb('wallet_before');
            $table->jsonb('wallet_after');
            $table->jsonb('extra_info')
                ->default("{}");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
