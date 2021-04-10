<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTableUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
			$table->string('country_code',10)->nullable()->default('NULL');
			$table->string('address',255)->nullable()->default('NULL');
			$table->string('address1',255)->nullable()->default('NULL');
			$table->string('pincode',15)->nullable()->default('NULL');
			$table->string('city',255)->nullable()->default('NULL');
			$table->string('state',255)->nullable()->default('NULL');
			$table->string('country',255)->nullable()->default('NULL');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('users', function($table) {
			$table->dropColumn('country_code');
			$table->dropColumn('address');
			$table->dropColumn('address1');
			$table->dropColumn('pincode');
			$table->dropColumn('city');
			$table->dropColumn('state');
			$table->dropColumn('country');
		});
    }
}
