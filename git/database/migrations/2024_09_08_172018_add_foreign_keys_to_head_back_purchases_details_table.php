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
        Schema::table('head_back_purchases_details', function (Blueprint $table) {
            $table->foreign(['head_back_purchases_id'])->references(['id'])->on('head_back_purchases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['productive_id'])->references(['id'])->on('productive')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('head_back_purchases_details', function (Blueprint $table) {
            $table->dropForeign('head_back_purchases_details_head_back_purchases_id_foreign');
            $table->dropForeign('head_back_purchases_details_productive_id_foreign');
            $table->dropForeign('head_back_purchases_details_publisher_foreign');
        });
    }
};
