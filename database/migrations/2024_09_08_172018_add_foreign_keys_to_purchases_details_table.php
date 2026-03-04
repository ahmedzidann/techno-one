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
        Schema::table('purchases_details', function (Blueprint $table) {
            $table->foreign(['productive_id'])->references(['id'])->on('productive')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['publisher'])->references(['id'])->on('admins')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['purchases_id'])->references(['id'])->on('purchases')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases_details', function (Blueprint $table) {
            $table->dropForeign('purchases_details_productive_id_foreign');
            $table->dropForeign('purchases_details_publisher_foreign');
            $table->dropForeign('purchases_details_purchases_id_foreign');
        });
    }
};
