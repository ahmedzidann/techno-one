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
            $table->tinyInteger('type')->after('id')->default(1)->comment('1=>esal, 2=>cheque');
            $table->unsignedBigInteger('bank_id')->nullable()->after('type');
            $table->foreign('bank_id')->references('id')->on('banks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('cheque_status')->after('paid')->default(1)->comment('1=>in_progress, 2=>accepted, 3=>refused');
            $table->string('cheque_number')->nullable()->after('cheque_status');
            $table->date('cheque_issue_date')->nullable()->after('cheque_number');
            $table->date('cheque_due_date')->nullable()->after('cheque_issue_date');
            $table->mediumText('refused_reason')->nullable()->after('notes');
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
            $table->dropColumn([
                'type',
                'bank_id',
                'cheque_status',
                'cheque_number',
                'cheque_issue_date',
                'cheque_due_date',
                'refused_reason',
            ]);
        });
    }
};
