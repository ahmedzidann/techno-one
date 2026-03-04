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
        Schema::create('refresh_tokens', function (Blueprint $table) {
           $table->id();
    $table->foreignId('client_id')->constrained()->cascadeOnDelete();
    $table->string('access_jti')->index();
    $table->string('refresh_token')->unique();
    $table->string('device_name')->nullable();
    $table->string('device_id')->nullable();
    $table->timestamp('expires_at');
    $table->boolean('revoked')->default(false);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
    }
};
