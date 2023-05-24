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
        Schema::create('bundel_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_course_id');
            $table->unsignedBigInteger('sub_course_id');
            $table->unsignedFloat('rPrice', 10, 2)->comment('At a Time Payment Price')->default(0);
            $table->unsignedFloat('iPrice', 10, 2)->comment('Installment Price')->default(0)->nullable();;
            $table->unsignedFloat('mPrice', 10, 2)->comment('Course Material Price')->default(0)->nullable();
            $table->integer("status")->default(1)->comment('1 => active, 2 => Inactive, 0 => Delete');
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
        Schema::dropIfExists('bundel_courses');
    }
};
