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
        Schema::create('destruction_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destruction_id')->nullable()->index('destruction_details_destruction_id_foreign');
            $table->unsignedBigInteger('productive_id')->nullable()->index('destruction_details_productive_id_foreign');
            $table->string('productive_code')->nullable();
            $table->enum('productive_type', ['kham', 'tam'])->default('tam');
            $table->double('amount')->default(0);
            $table->double('price')->default(0);
            $table->enum('type', ['department', 'wholesale'])->default('wholesale');
            $table->double('all_pieces')->default(0);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('destruction_details_publisher_foreign');
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
        Schema::dropIfExists('destruction_details');
    }
};
