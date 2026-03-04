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
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign(['publisher'])->references(['id'])->on('admins')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['storage_id'])->references(['id'])->on('storages')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['supplier_id'])->references(['id'])->on('suppliers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_publisher_foreign');
            $table->dropForeign('purchases_storage_id_foreign');
            $table->dropForeign('purchases_supplier_id_foreign');
        });
    }
};
