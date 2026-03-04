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
        Schema::table('clients', function (Blueprint $table) {
             $table->unsignedBigInteger('user_update_status')
                  ->nullable()
                  ->after('updated_at');

            $table->timestamp('time_update_status')
                  ->nullable()
                  ->after('user_update_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
             $table->dropColumn([
                'user_update_status',
                'time_update_status'
            ]);
        });
    }
};
