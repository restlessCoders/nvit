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
            $table->string('systemId',100);
            $table->unsignedFloat('course_price', 10, 2)->default(0);
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('package_id')->nullable();
            $table->date('entryDate')->nullable();
            $table->text("note")->nullable();
            $table->integer("acc_approve")->default(0)->comment('0 => no Invoice, 2 => Invoice posted');
            //$table->integer("status")->default(1)->comment('0 => inactive, 1 => active, 2 => waiting' );
            //$table->integer("status")->comment('0 => Close, 1 => Running, 2=> Knocking, 3=> Enroll 4=> Registered 5=> Evoulation');
            $table->integer("cstatus")->comment('0 => course incomplete, 1 => course Complete');
            $table->integer("status")->comment('2=> Enrolled , 3=> Knocking, 4=> Evoulation');
            $table->integer("pstatus")->comment('0 => payment incomplete, 1 => Payment Complete');
            $table->integer("type")->comment('1 => At A Time,2 => Installment');
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
        Schema::dropIfExists('student_batches');
    }
}
