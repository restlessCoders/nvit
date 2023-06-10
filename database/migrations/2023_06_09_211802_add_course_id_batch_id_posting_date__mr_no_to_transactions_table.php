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
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('course_id')->after('exe_id')->default(0);
            $table->integer('batchId')->after('course_id')->default(0);
            $table->integer('mrNo')->after('batchId');
            $table->date('postingDate')->after('mrNo')->nullable(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('course_id');
            $table->dropColumn('batchId');
            $table->dropColumn('mrNo');
            $table->dropColumn('postingDate');
        });
    }
};
