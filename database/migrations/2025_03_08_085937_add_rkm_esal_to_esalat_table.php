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
            $table->integer('rkm_esal')->nullable()->after('id'); // Nullable integer column after 'id'
            $table->integer('dafter_rkm_esal')->default(0)->after('rkm_esal'); // Default value and correct after() usage
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
            //
        });
    }
};
