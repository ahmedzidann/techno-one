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
        Schema::table('item_installation_details', function (Blueprint $table) {
            $table->foreign(['item_installation_id'])->references(['id'])->on('item_installations')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['main_productive_id'])->references(['id'])->on('productive')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('item_installation_details', function (Blueprint $table) {
            $table->dropForeign('item_installation_details_item_installation_id_foreign');
            $table->dropForeign('item_installation_details_main_productive_id_foreign');
            $table->dropForeign('item_installation_details_productive_id_foreign');
            $table->dropForeign('item_installation_details_publisher_foreign');
        });
    }
};
