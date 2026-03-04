<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // حذف الأعمدة اللي فيها FK بطريقة آمنة
            if (Schema::hasColumn('clients', 'representative_id')) {
                $table->dropConstrainedForeignId('representative_id');
            }

            if (Schema::hasColumn('clients', 'distributor_id')) {
                $table->dropConstrainedForeignId('distributor_id');
            }

            if (Schema::hasColumn('clients', 'client_subscription_id')) {
                $table->dropConstrainedForeignId('client_subscription_id');
            }

            // حذف الأعمدة العادية
            $columnsToDrop = [
                'payment_category',
                'tele_sales_am',
                'tele_sales_pm',
                'commercial_register',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('clients', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // يمكنك إعادة إنشاء الأعمدة هنا إذا لزم الأمر
            $table->unsignedBigInteger('representative_id')->nullable();
            $table->unsignedBigInteger('distributor_id')->nullable();
            $table->unsignedBigInteger('client_subscription_id')->nullable();
            $table->string('payment_category')->nullable();
            $table->string('tele_sales_am')->nullable();
            $table->string('tele_sales_pm')->nullable();
            $table->string('commercial_register')->nullable();
        });
    }
};
