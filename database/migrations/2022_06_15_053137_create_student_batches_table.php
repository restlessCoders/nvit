<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('student_id');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->date('entryDate')->nullable();
            $table->text("accountsNote")->nullable();
            $table->integer("acc_approve")->default(0)->comment('0 => inactive, 1 => active');
            //$table->integer("status")->default(1)->comment('0 => inactive, 1 => active, 2 => waiting' );
            //$table->integer("status")->comment('0 => Close, 1 => Running, 2=> Knocking, 3=> Enroll 4=> Registered 5=> Evoulation');
            $table->integer("status")->comment('0 => course complete, 1 => Payment Complete, 2=> Enrolled , 3=> Knocking, 4=> Evoulation');
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
        Schema::dropIfExists('student_batches');
    }
}
