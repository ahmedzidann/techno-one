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
            // Rename column first
            $table->renameColumn('tele_sales', 'tele_sales_am');
        });

        Schema::table('clients', function (Blueprint $table) {
            // Ensure tele_sales_am is unsignedBigInteger to match employees.id
            $table->unsignedBigInteger('tele_sales_am')->nullable()->default(null)->change();

            // Add foreign key
            $table->foreign('tele_sales_am', 'clients_tele_sales_am_id_foreign')
                ->references('id')
                ->on('employees')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Add new column tele_sales_pm
            $table->unsignedBigInteger('tele_sales_pm')->nullable()->after('tele_sales_am');
            $table->foreign('tele_sales_pm', 'clients_tele_sales_pm_id_foreign')
                ->references('id')
                ->on('employees')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign('clients_tele_sales_am_id_foreign');
            $table->dropForeign('clients_tele_sales_pm_id_foreign');

            // Change tele_sales_am back to original state (you can adjust as needed)
            $table->unsignedBigInteger('tele_sales_am')->nullable(false)->change();

            // Drop tele_sales_pm
            $table->dropColumn('tele_sales_pm');
        });

        Schema::table('clients', function (Blueprint $table) {
            // Rename column back
            $table->renameColumn('tele_sales_am', 'tele_sales');
        });
    }
};
