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
        Schema::table('head_back_sales', function (Blueprint $table) {
            $table->foreign(['client_id'])->references(['id'])->on('clients')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['publisher'])->references(['id'])->on('admins')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['storage_id'])->references(['id'])->on('storages')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_back_sales', function (Blueprint $table) {
            $table->dropForeign('head_back_sales_client_id_foreign');
            $table->dropForeign('head_back_sales_publisher_foreign');
            $table->dropForeign('head_back_sales_storage_id_foreign');
        });
    }
};
