<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediationCaseTable extends Migration
{
    public function up()
    {
        Schema::create('mediation_case', function (Blueprint $table) {

		$table->id();
		$table->integer('userid')->length(11)->unsigned();
		$table->string('disputeCategory',100);
		$table->integer('noOfParties')->length(2);
		$table->string('amount',100);
		$table->text('issue')->nullable()->default(NULL);
		$table->string('documentPath',500)->nullable()->default(NULL);
		$table->integer('confirm_status')->length(11)->unsigned();
		$table->timestamp('created_at');
		$table->timestamp('updated_at')->nullable();;

        });
    }

    public function down()
    {
        Schema::dropIfExists('mediation_case');
    }
}