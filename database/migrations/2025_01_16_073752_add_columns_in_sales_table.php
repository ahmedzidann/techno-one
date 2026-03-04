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
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('governorate_id')->nullable()->after('year');
            $table->foreign('governorate_id', 'sales_governorate_id_foreign')->on('areas')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('city_id')->nullable()->after('governorate_id');
            $table->foreign('city_id', 'sales_city_id_foreign')->on('areas')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('region_id')->nullable()->after('city_id');
            $table->foreign('region_id', 'sales_region_id_foreign')->on('areas')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('tele_sales')->default(1)->comment('[1=>AM , 2=>PM]')->after('region_id');
            $table->unsignedBigInteger('distributor_id')->nullable()->after('representative_id');
            $table->foreign('distributor_id', 'sales_distibutor_id_foreign')->on('representatives')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('client_subscription_id')->nullable()->after('client_id');
            $table->foreign('client_subscription_id', 'sales_client_subscription_id_foreign')->on('client_subscriptions')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('payment_category')->comment('[EVERY_MONTH => 1,EVERY_15_DAYS => 2,EVERY_WEEK => 3,ON_DELIVERED => 4]')->after('client_subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_governorate_id_foreign');
            $table->dropForeign('sales_city_id_foreign');
            $table->dropForeign('sales_region_id_foreign');
            $table->dropForeign('sales_distibutor_id_foreign');
            $table->dropForeign('sales_client_subscription_id_foreign');

            $table->dropColumn([
                'governorate_id',
                'city_id',
                'region_id',
                'tele_sales',
                'distributor_id',
                'client_subscription_id',
                'payment_category',
            ]);
        });
    }
};
