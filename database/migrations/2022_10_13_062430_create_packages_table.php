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
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('courseId')->nullable();
            $table->unsignedBigInteger('batchId')->nullable();
            $table->string('pName', 255);
            $table->unsignedFloat('price', 10, 2)->comment('Price')->default(0);
            $table->date('startDate');
			$table->date('endDate');
            $table->time('endTime');
            $table->unsignedBigInteger('userId')->comment('createdBy');
            $table->unsignedBigInteger('updateBy')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->default(1)->comment('1 => Running, 0=> Closed');
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
        Schema::dropIfExists('packages');
    }
};
