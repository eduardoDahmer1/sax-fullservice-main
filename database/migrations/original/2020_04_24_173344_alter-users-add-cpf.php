<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersAddCpf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf')->nullable()->after('name');
            $table->string('address_number')->nullable()->after('address');
            $table->string('complement')->nullable()->after('address_number');
            $table->string('district')->nullable()->after('complement');
            $table->string('state')->nullable()->after('city');
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
            $table->dropColumn('cpf');
            $table->dropColumn('address_number');
            $table->dropColumn('complement');
            $table->dropColumn('district');
            $table->dropColumn('state');
        });
    }
}
