<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->string('phone')->unique();
            $table ->enum('Type',['client','driver']);
            $table->string('email')->unique()->default(null);
            $table->enum('trans_Type',['Taxi','motore','bus'])->nullable()->default(null);
            // $table->string('code_car')->unique()->nullable()->default(null);
            $table-> decimal('x_location',10, 7)->default(null);
            $table->decimal('y_location',10, 7)->default(null);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            // $table->foreignId('current_team_id')->nullable();
            // $table->string('profile_photo_path', 2048)->nullable();
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
        Schema::dropIfExists('users');
    }
}
