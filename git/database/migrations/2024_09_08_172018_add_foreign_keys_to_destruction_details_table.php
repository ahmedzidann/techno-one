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
        Schema::table('destruction_details', function (Blueprint $table) {
            $table->foreign(['destruction_id'])->references(['id'])->on('destruction')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('destruction_details', function (Blueprint $table) {
            $table->dropForeign('destruction_details_destruction_id_foreign');
            $table->dropForeign('destruction_details_productive_id_foreign');
            $table->dropForeign('destruction_details_publisher_foreign');
        });
    }
};
