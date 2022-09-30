<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
			$table->string('msg',500)->nullable();
			$table->string('link')->nullable();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('sendBy');
			$table->boolean('status')->default(1)->comment('0 => Unseen, 1 => seen' );
            $table->unsignedBigInteger('branchId')->nullable();
            $table->index(['branchId']);
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
        Schema::dropIfExists('notifications');
    }
}
