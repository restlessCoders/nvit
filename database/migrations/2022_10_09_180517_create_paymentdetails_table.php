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
            $table->integer('mrNo')->unique()->index();
            $table->unsignedBigInteger('invoiceId')->nullable()->unique()->index();
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('batchId');
            $table->unsignedFloat('cPayable', 10, 2)->default(0);
            $table->unsignedFloat('cpaidAmount', 10, 2)->default(0);
            $table->unsignedFloat('m_price', 10, 2)->default(0);
            $table->boolean('payment_type')->default(1)->comment('1 => partial, 0=> full');
            $table->boolean('feeType')->comment('1 => Registration Fee, 2=> Course Fee');
            $table->boolean('payment_mode')->comment('1 => Cash Fee, 2=> Bkash 3=> Card');
            $table->date('dueDate')->nullable();
            $table->unsignedFloat('discount', 10, 2)->default(0)->nullable();
            $table->unsignedBigInteger('created_by')->index()->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable()->index()->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
