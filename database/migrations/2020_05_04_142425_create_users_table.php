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
            $table->unsignedBigInteger('created_by')->index()->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable()->index()->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
            $table->unsignedBigInteger('created_by')->index()->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable()->index()->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
