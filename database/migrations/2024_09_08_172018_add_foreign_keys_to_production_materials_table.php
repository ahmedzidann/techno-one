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
        Schema::table('production_materials', function (Blueprint $table) {
            $table->foreign(['main_productive_id'])->references(['id'])->on('productive')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['productive_id'])->references(['id'])->on('productive')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
        Schema::table('production_materials', function (Blueprint $table) {
            $table->dropForeign('production_materials_main_productive_id_foreign');
            $table->dropForeign('production_materials_productive_id_foreign');
            $table->dropForeign('production_materials_publisher_foreign');
        });
    }
};
