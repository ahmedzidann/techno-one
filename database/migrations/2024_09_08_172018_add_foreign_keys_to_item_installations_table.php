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
        Schema::table('item_installations', function (Blueprint $table) {
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
        Schema::table('item_installations', function (Blueprint $table) {
            $table->dropForeign('item_installations_productive_id_foreign');
            $table->dropForeign('item_installations_publisher_foreign');
        });
    }
};
