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
            $table->unsignedBigInteger('executiveId');
            $table->unsignedBigInteger('invoiceId')->nullable();
            $table->date('paymentDate');
            $table->unsignedFloat('tPayable', 10, 2)->default(0);
            $table->unsignedFloat('paidAmount', 10, 2)->default(0);
            $table->unsignedBigInteger('couponId')->nullable();;
            $table->unsignedFloat('discount', 10, 2)->default(0)->nullable();
            /*$table->boolean('mr')->default(5)->nullable();*/
            $table->boolean('status')->default(1)->comment('1 => due, 0=> paid');
            $table->unsignedBigInteger('createdBy');
            $table->unsignedBigInteger('updatedBy')->nullable();
            $table->text("accountNote")->nullable();
            $table->boolean('type')->default(1)->comment('1 => due, 0=> complete');
            //ki droner fee ei tah ei column a raka jabe naki inno new column kora hobe mane hocce kon doroner registraton fee naki onno kichu
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
