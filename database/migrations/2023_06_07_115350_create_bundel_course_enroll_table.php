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
        Schema::create('bundel_course_enroll', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_course_id')->comment('Relation with student_courses table id');
            $table->unsignedBigInteger('sub_course_id');
            $table->unsignedBigInteger('student_id');
            $table->string('systemId',100);
            $table->integer('status')->default(1)->default(2)->comment('1 => batch assigned, , 2 => Pending' );
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
        Schema::dropIfExists('bundel_course_enroll');
    }
};
