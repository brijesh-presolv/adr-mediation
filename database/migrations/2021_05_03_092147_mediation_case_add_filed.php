<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediationCaseAddFiled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('mediation_case', function(Blueprint $table) {
  			$table->text('document_settelment')->default(null)->nullable();
  		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('mediation_case', function($table) {
  			$table->dropColumn('document_settelment');
  		});
    }
}
