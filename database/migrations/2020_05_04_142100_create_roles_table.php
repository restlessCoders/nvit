<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('type', 20)->unique();
            $table->string('identity', 30)->unique();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            [
                'type' => 'Superadmin',
                'identity' => 'superadmin',
                'created_at' => Carbon::now()
            ],
			[
                'type' => 'Admin',
                'identity' => 'admin',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Operation Manager',
                'identity' => 'operationmanager',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Account Manager',
                'identity' => 'accountmanager',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Sales Manager',
                'identity' => 'salesmanager',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Facility Manager',
                'identity' => 'facilitymanager',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Training Manager',
                'identity' => 'trainingmanager',
                'created_at' => Carbon::now()
            ],
			[
                'type' => 'Front Desk',
                'identity' => 'frontdesk',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Sales Executive',
                'identity' => 'salesexecutive',
                'created_at' => Carbon::now()
            ],
			[
                'type' => 'Facility Executive',
                'identity' => 'facilityexecutive',
                'created_at' => Carbon::now()
            ],
			[
                'type' => 'Trainer',
                'identity' => 'trainer',
                'created_at' => Carbon::now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
