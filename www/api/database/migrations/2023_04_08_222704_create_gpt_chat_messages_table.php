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
        Schema::create('gpt_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->string('role');
            $table->text('content');
            $table->boolean('is_stream')
                ->default(false);
            $table->boolean('is_stream_ended')
                ->default(true);
            $table->unsignedBigInteger('chunk_count')
                ->default(0);
            $table->jsonb('extra_data')
                ->nullable()
                ->default(null);
            $table->jsonb('notification_info')
                ->nullable()
                ->default(null);
            $table->timestamps();

            $table->foreign('chat_id')
                ->references('id')
                ->on('gpt_chats');

            $table->index(['chat_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpt_chat_messages');
    }
};
