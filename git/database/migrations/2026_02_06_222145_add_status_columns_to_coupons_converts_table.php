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
        Schema::table('coupons_converts', function (Blueprint $table) {
            $table->enum('status',['approved','refused','pending'])
                  ->default('pending')
                  ->after('updated_at');

            $table->unsignedBigInteger('update_user_status')
                  ->nullable()
                  ->after('status');

            $table->timestamp('update_time_status')
                  ->nullable()
                  ->after('update_user_status');

            $table->string('reason')
                  ->nullable()
                  ->after('update_time_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons_converts', function (Blueprint $table) {
             $table->dropColumn([
                'status',
                'update_user_status',
                'update_time_status',
                'reason'
            ]);
        });
    }
};
