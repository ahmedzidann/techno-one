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
        Schema::create('rasied_ayni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('rasied_ayni_branch_id_foreign');
            $table->unsignedBigInteger('storage_id')->nullable()->index('rasied_ayni_storage_id_foreign');
            $table->unsignedBigInteger('productive_id')->nullable()->index('rasied_ayni_productive_id_foreign');
            $table->enum('type', ['department', 'wholesale'])->nullable();
            $table->double('amount')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('publisher')->nullable()->index('rasied_ayni_publisher_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rasied_ayni');
    }
};
