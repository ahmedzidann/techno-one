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
        Schema::table('rasied_ayni', function (Blueprint $table) {
            $table->dropIndex('rasied_ayni_productive_id_foreign');
            $table->foreign('productive_id')->on('productive')->references('id')->cascadeOnDelete()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rasied_ayni', function (Blueprint $table) {
            $table->index('rasied_ayni_productive_id_foreign');
            $table->dropForeign('productive_id');
        });
    }
};
