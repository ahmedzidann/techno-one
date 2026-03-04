<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons_converts', function (Blueprint $table) {

            $table->string('invoice_number')->nullable()->after('amount');

            // نوع الإدخال مثلا: يدوي / آلي
            $table->string('type_insert')->nullable()->after('id');

            // الشخص أو المستخدم الذي قام بعملية التحويل
            $table->unsignedBigInteger('publisher')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('coupons_converts', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'type_insert', 'publisher']);
        });
    }
};
