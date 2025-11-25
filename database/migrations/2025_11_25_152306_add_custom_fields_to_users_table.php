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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 50)->after('id');
            $table->string('last_name', 50)->after('first_name');
            $table->enum('role', ['customer', 'admin'])->default('customer')->after('email');
            $table->string('verification_code')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('status')->default(1)
                ->comment('0 = Inactive, 1 = Active');

            $table->index('role');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
