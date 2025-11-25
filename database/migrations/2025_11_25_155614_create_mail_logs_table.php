<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mail_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->comment('References users table');
            $table->string('to_email', 255)->nullable();
            $table->text('cc_email')->nullable();
            $table->text('subject')->nullable();
            $table->enum('send_status', ['not_send', 'pending', 'completed', 'failed'])
                ->default('not_send');
            $table->json('data')->nullable();
            $table->longText('response')->nullable();
            $table->timestamp('email_sent_at')->useCurrent();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_logs');
    }
};
