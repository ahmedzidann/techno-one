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
        Schema::table('clients', function (Blueprint $table) {
            $table->foreign(['city_id'])->references(['id'])->on('areas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['governorate_id'])->references(['id'])->on('areas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_city_id_foreign');
            $table->dropForeign('clients_governorate_id_foreign');
            $table->dropForeign('clients_publisher_foreign');
        });
    }
};
