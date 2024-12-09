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
        Schema::create('other_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mrNo')->unique()->index();
            $table->string('pay_by')->nullable();
            $table->foreignId('other_payment_category_id')->constrained()->onDelete('cascade');
            $table->unsignedFloat('amount', 10, 2)->default(0);
            $table->date('paymentDate');
            $table->text("accountNote")->nullable();
            $table->boolean('payment_mode')->comment('1 => Cash, 2=> Bkash 3=> Card');
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
        Schema::dropIfExists('other_payments');
    }
};
