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
           $table->enum('register_type', ['office', 'web', 'iphone', 'android'])
                  ->default('office')
                  ->after('client_type'); // ضع العمود بعد العمود المناسب حسب حاجتك
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
             $table->dropColumn('register_type');
        });
    }
};
