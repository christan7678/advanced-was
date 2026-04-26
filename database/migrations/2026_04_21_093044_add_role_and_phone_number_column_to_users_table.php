<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAndPhoneNumberColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            // Phone number first
            $table->string('phone_number', 15)
                  ->after('password');

            // Role LAST column (no after() er', 'adminneeded)
            $table->enum('role', ['us', 'super_admin'])
                  ->default('user')
                  ->after("remember_token");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'role']);
        });
    }
}
