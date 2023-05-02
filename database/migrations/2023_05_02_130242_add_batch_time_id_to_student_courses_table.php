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
        Schema::table('student_courses', function (Blueprint $table) {
            $table->integer('batch_time_id')->nullable()->after('executiveNote');
            $table->integer('batch_slot_id')->nullable()->after('batch_time_id');
            $table->decimal('price',10,2)->default(0.00)->after('batch_slot_id');
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
        Schema::table('student_courses', function (Blueprint $table) {
            $table->dropColumn('batch_time_id');
            $table->dropColumn('batch_slot_id');
            $table->dropColumn('price');
            $table->dropColumn('price');
        });
    }
};
