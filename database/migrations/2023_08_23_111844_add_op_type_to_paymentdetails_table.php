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
        Schema::table('paymentdetails', function (Blueprint $table) {
            $table->tinyInteger('op_type')->comment('1 => Refund, 2 => Adjustment 3=> Batch Transfer 4=> Repeat')->after('m_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentdetails', function (Blueprint $table) {
            $table->dropColumn('op_type');
        });
    }
};
