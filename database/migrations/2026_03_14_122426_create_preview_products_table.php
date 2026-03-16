<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preview_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preview_category_id')->constrained('preview_categories')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
             $table->integer('points');
            $table->text('image')->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preview_products');
    }
};
