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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('studentId');
            $table->integer('mrNo')->unique()->index();
            $table->unsignedBigInteger('invoiceId')->nullable()->index();
            $table->unsignedBigInteger('executiveId');
            $table->date('paymentDate');
            $table->unsignedFloat('tPayable', 10, 2)->default(0);
            $table->unsignedFloat('paidAmount', 10, 2)->default(0);
            $table->text("accountNote")->nullable();
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
        Schema::dropIfExists('payments');
    }
};
