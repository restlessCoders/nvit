<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name",255);
            $table->string("photo",255)->nullable();
            $table->string("contact",13)->unique();
            $table->string("altContact",255)->nullable();
            $table->string("email",255)->unique()->nullable();
            $table->text("address")->nullable();
            $table->integer("division_id")->nullable();
            $table->integer("district_id")->nullable();
            $table->integer("upazila_id")->nullable();
            $table->text("otherInfo")->nullable();
            $table->integer("executiveId");
            $table->date("executiveReminder")->nullable();
            $table->text("executiveNote")->nullable();
            $table->integer('batch_slot_id')->nullable();
            $table->integer('batch_time_id')->nullable();
            $table->string('course_id',100)->nullable();
			$table->integer('refId')->unsigned();
			$table->foreign("refId")->references('id')->on('references')->onDelete('cascade');
            $table->integer("status")->default(1)->comment('0 => inactive, 1 => active, 2 => waiting, 3 => dump' );
            $table->unsignedBigInteger('branchId')->nullable();
            $table->index(['branchId']);
            $table->index(['email']);
            $table->index(['name']);
            $table->index(['contact']);
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
        Schema::dropIfExists('students');
    }
}
