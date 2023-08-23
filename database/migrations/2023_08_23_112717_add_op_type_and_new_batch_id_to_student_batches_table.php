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
        Schema::table('student_batches', function (Blueprint $table) {
            $table->tinyInteger('op_type')->comment('1 => Refund, 2 => Adjustment 3=> Batch Transfer 4=> Repeat')->after('type')->default(0);
            $table->integer('new_batch_id')->after('op_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_batches', function (Blueprint $table) {
            $table->dropColumn('op_type');
            $table->dropColumn('new_batch_id');
        });
    }
};
