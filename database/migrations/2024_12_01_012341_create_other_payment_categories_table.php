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
        Schema::create('other_payment_categories', function (Blueprint $table) {
            $table->id();
            $table->string("category_name")->unique();
            $table->string("short_name")->unique()->nullable();
            $table->timestamps();
        });
        DB::table('other_payment_categories')->insert([
                [
                    "category_name" => "IDB"
                ],
                [
                    "category_name" => "Exam"
                ],
                [
                    "category_name" => "Other"
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_payment_categories');
    }
};
