<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('users', function(Blueprint $table) {
            $table->string('emailotp',11)->nullable()->default('NULL');
            $table->string('smsotp',11)->nullable()->default('NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('users', function(Blueprint $table) {
             $table->string('emailotp',11)->nullable()->default('NULL');
            $table->string('smsotp',11)->nullable()->default('NULL');
        });
    }
}
