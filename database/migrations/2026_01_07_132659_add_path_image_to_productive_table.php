<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('productive', function (Blueprint $table) {
            $table->string('path_image')->nullable()->after('category_id');
        });
    }

    public function down()
    {
        Schema::table('productive', function (Blueprint $table) {
            $table->dropColumn('path_image');
        });
    }
};
