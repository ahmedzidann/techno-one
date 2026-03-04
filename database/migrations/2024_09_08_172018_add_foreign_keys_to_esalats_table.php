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
        Schema::table('esalats', function (Blueprint $table) {
            $table->foreign(['client_id'])->references(['id'])->on('clients')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['publisher'])->references(['id'])->on('admins')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esalats', function (Blueprint $table) {
            $table->dropForeign('esalats_client_id_foreign');
            $table->dropForeign('esalats_publisher_foreign');
        });
    }
};
