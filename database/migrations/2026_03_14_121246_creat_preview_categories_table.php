<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preview_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // اسم التصنيف
            $table->text('description')->nullable(); // وصف التصنيف
            $table->boolean('status')->default(1);   // مفعل او لا
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preview_categories');
    }
};
