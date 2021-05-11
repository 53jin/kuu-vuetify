<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('status')->default(Status::Disabled);
            $table->string('name', 40)
                  ->storedAs('trim(concat(ifnull(first_name, \'\'), \' \', ifnull(last_name, \'\')))');
            $table->string('first_name', 16)->nullable();
            $table->string('last_name', 16)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->unsignedTinyInteger('im_type')->default(0);
            $table->string('im_contact', 40)->nullable();
            $table->string('api_token', 40)->unique()->nullable();
            $table->json('ip_whitelist')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
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
