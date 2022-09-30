<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('student_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->text("executiveNote")->nullable();
            $table->integer("status")->default(1)->comment('0 => inactive, 1 => active, 2 => waiting, 3=> Knocking, 4=> Enroll');
			$table->integer("acc_approve")->default(0)->comment('0 => inactive, 1 => active');
			$table->integer("om_approve")->default(0)->comment('0 => inactive, 1 => active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_courses');
    }
}
