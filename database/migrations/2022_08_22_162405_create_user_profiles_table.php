<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('surname');  
            $table->string('alias');   
            $table->string('gender')->nullable();
            $table->string('birth_date')->nullable()->comment('Date of Birth');  
            $table->string('telephone')->nullable();
            $table->string('profession', 100)->nullable();
            $table->string('profile_phrase', 100)->nullable();
            $table->text('biography')->nullable();
            $table->string('avatar')->nullable()->default(null)->comment('User Profile Image');
            $table->boolean('active')->default(1)->comment('It says if user is active');
            $table->timestamps();   
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
