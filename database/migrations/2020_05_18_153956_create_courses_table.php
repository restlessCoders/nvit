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
            $table->unsignedFloat('rPrice', 10, 2)->comment('At a Time Payment Price')->default(0);
            $table->unsignedFloat('iPrice', 10, 2)->comment('Installment Price')->default(0);
            $table->unsignedFloat('mPrice', 10, 2)->comment('Course Material Price')->default(0);
            $table->boolean('status')->default(0)->comment('0 => inactive, 1 => active');
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
        Schema::dropIfExists('courses');
    }
}
