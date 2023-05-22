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
        Schema::create('certificates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->integer('attn')->comment('attendance')->nullable();
            $table->boolean('perf')->comment('performance')->default(0)->comment('1 => ok, 0=> no performance');
            $table->boolean('pass')->comment('passed')->default(0)->comment('1 => passed, 0=> failed');
            $table->boolean('drop')->comment('dropped')->default(0)->comment('1 => drop, 0=> not drop');
            $table->text('inst_note')->nullable();
            $table->text('ac_note')->nullable();
            $table->text('op_note')->nullable();
            $table->text('gm_note')->nullable();
            $table->text('ex_note')->nullable();
            $table->boolean('issue_status')->default(0)->comment('1 => certificate issued, 0=> Not issued');
            $table->boolean('delivery_status')->default(0)->comment('1 => certificate Delivered, 0=> Not Delivered');
            $table->boolean('edit_allow')->default(0)->comment('1 => Edit Allowed, 0=> Not Allowed');
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
        Schema::dropIfExists('certificates');
    }
};
