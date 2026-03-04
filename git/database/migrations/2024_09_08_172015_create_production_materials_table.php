<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('process', ['production', 'destruction'])->default('production');
            $table->unsignedBigInteger('process_id')->nullable();
            $table->unsignedBigInteger('main_productive_id')->nullable()->index('production_materials_main_productive_id_foreign');
            $table->double('main_amount')->default(0);
            $table->unsignedBigInteger('productive_id')->nullable()->index('production_materials_productive_id_foreign');
            $table->double('amount')->default(0);
            $table->double('all_amount')->default(0);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('production_materials_publisher_foreign');
            $table->string('month', 20)->nullable();
            $table->year('year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_materials');
    }
};
