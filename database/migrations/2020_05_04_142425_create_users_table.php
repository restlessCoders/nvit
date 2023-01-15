<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;


class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('mobileNumber', 20)->unique();
            $table->string('timezone')->nullable();
            $table->string('password', 100);
			$table->string('api_token', 100)->nullable();;
            $table->boolean('status', 1)->default(1)->comment('0 => inactive, 1 => active, 2 => discharge' );
            $table->unsignedInteger('userCreatorId')->nullable();
            $table->unsignedBigInteger('branchId')->nullable();
			$table->unsignedBigInteger('roleId');
            $table->index(['branchId']);
            $table->index(['email']);
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                'name' => 'Superadmin',
                'username' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'mobileNumber' => '1',
                'password' => sha1(md5('superadmin')),
                'status' => 1,
                'roleId' => 1,
                'userCreatorId' => 1,
                'branchId' => null,
                'created_at' => Carbon::now()
            ],
			[
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'mobileNumber' => '2',
                'password' => sha1(md5('admin')),
                'status' => 1,
                'roleId' => 2,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Operation Manager',
                'username' => 'operation',
                'email' => 'operation@gmail.com',
                'mobileNumber' => '3',
                'password' => sha1(md5('operation')),
                'status' => 1,
                'roleId' => 3,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Accounts Manager',
                'username' => 'account',
                'email' => 'account@gmail.com',
                'mobileNumber' => '4',
                'password' => sha1(md5('account')),
                'status' => 1,
                'roleId' => 4,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Sales Manager',
                'username' => 'sale',
                'email' => 'sale@gmail.com',
                'mobileNumber' => '5',
                'password' => sha1(md5('sale')),
                'status' => 1,
                'roleId' => 5,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Facility Manager',
                'username' => 'facility',
                'email' => 'facility@gmail.com',
                'mobileNumber' => '6',
                'password' => sha1(md5('facility')),
                'status' => 1,
                'roleId' => 6,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Training Manager',
                'username' => 'training',
                'email' => 'training@gmail.com',
                'mobileNumber' => '7',
                'password' => sha1(md5('training')),
                'status' => 1,
                'roleId' => 7,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Front Desk',
                'username' => 'front',
                'email' => 'front@gmail.com',
                'mobileNumber' => '8',
                'password' => sha1(md5('front')),
                'status' => 1,
                'roleId' => 8,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Sales Executive 1',
                'username' => 'tanbeen',
                'email' => 'tanbeen@gmail.com',
                'mobileNumber' => '9',
                'password' => sha1(md5('tanbeen')),
                'status' => 1,
                'roleId' => 9,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Sales Executive 2',
                'username' => 'sadi',
                'email' => 'sadi@gmail.com',
                'mobileNumber' => '10',
                'password' => sha1(md5('sadi')),
                'status' => 1,
                'roleId' => 9,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Sales Executive 3',
                'username' => 'nafees',
                'email' => 'nafees@gmail.com',
                'mobileNumber' => '11',
                'password' => sha1(md5('nafees')),
                'status' => 1,
                'roleId' => 9,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Facility Executive 1',
                'username' => 'shamshed',
                'email' => 'shamshed@gmail.com',
                'mobileNumber' => '12',
                'password' => sha1(md5('shamshed')),
                'status' => 1,
                'roleId' => 10,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Trainer Executive 1',
                'username' => 'tawid',
                'email' => 'tawhid@gmail.com',
                'mobileNumber' => '13',
                'password' => sha1(md5('tawhid')),
                'status' => 1,
                'roleId' => 11,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Account Executive 1',
                'username' => 'tania',
                'email' => 'tania@gmail.com',
                'mobileNumber' => '14',
                'password' => sha1(md5('tania')),
                'status' => 1,
                'roleId' => 12,
                'userCreatorId' => 1,
                'branchId' => 1,
                'created_at' => Carbon::now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
