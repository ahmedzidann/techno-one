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
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->nullable()->after('city_id');
            $table->foreign('region_id', 'clients_region_id_foreign')->on('areas')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('distributor_id')->nullable()->after('representative_id');
            $table->foreign('distributor_id', 'clients_distibutor_id_foreign')->on('representatives')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('tele_sales')->default(1)->comment('[1=>AM , 2=>PM]')->after('region_id');
            $table->string('commercial_register')->nullable()->after('tele_sales');
            $table->string('tax_card')->nullable()->after('commercial_register');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_region_id_foreign');
            $table->dropForeign('clients_distibutor_id_foreign');
            $table->dropColumn([
                'region_id',
                'distributor_id',
                'tele_sales',
                'commercial_register',
                'tax_card',
            ]);
        });
    }
};
