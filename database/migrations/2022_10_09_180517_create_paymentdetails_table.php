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
        Schema::create('paymentdetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('paymentId');
            $table->integer('mrNo');
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('batchId');
            $table->unsignedFloat('cPayable', 10, 2)->default(0);
            $table->unsignedFloat('cpaidAmount', 10, 2)->default(0);
            $table->unsignedFloat('m_price', 10, 2)->default(0);
            $table->boolean('payment_type')->default(1)->comment('1 => partial, 0=> full');
            $table->boolean('feeType')->comment('1 => Registratio Fee, 2=> Course Fee');
            $table->boolean('payment_mode')->comment('1 => Cash Fee, 2=> Bkash 3=> Card');
            $table->date('dueDate')->nullable();
            $table->unsignedFloat('discount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentdetails');
    }
};
