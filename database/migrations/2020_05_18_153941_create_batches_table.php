<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batchId', 255);
            $table->unsignedBigInteger('courseId')->nullable();
			$table->date('startDate')->nullable();
			$table->date('endDate')->nullable();
            $table->string('bslot', 255)->nullable();
            $table->string('btime', 255)->nullable();
            $table->unsignedBigInteger('trainerId')->nullable();
			$table->date('examDate')->nullable();
			$table->time('examTime')->nullable();
			$table->unsignedBigInteger('examRoom')->nullable();
			/*$table->unsignedFloat('price', 10, 2)->default(0);
			$table->unsignedFloat('discount', 10, 2)->default(0);*/
            $table->integer('seat')->comment('Total Available Seat');
            $table->integer('courseDuration')->comment('Calculation Hour Basis');
            $table->integer('classHour')->comment('Per Class Hour');
            $table->integer('totalClass')->comment('Total Class');
            $table->text('remarks')->comment('Course Remarks')->nullable();
            $table->boolean('status')->default(1)->comment('1 => Running, 0=> Closing');
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
        Schema::dropIfExists('batches');
    }
}
