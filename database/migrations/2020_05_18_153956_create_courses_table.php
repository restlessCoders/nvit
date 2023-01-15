<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('courseName', 255)->nullable();
            $table->text('courseDescription')->nullable();
            $table->unsignedFloat('rPrice', 10, 2)->comment('regular Course Price')->default(0);
            $table->unsignedFloat('mPrice', 10, 2)->comment('Course Material Price')->default(0);
            $table->boolean('status')->default(0)->comment('0 => inactive, 1 => active');
            $table->unsignedBigInteger('userId')->default(1);
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
        Schema::dropIfExists('courses');
    }
}
