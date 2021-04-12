<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInvolvedInAgreementTable extends Migration
{
    public function up()
    {
        Schema::create('user_involved_in_agreement', function (Blueprint $table) {

		$table->id();
		$table->integer('userId')->legth(10)->unsigned()->nullable()->default(0);
		$table->string('userEmail',500);
		$table->bigInteger('userPhone')->lenght(10)->unsigned();
		$table->integer('userPlanId')->lenght(10)->unsigned()->default(0);
		$table->string('joinCode',100)->nullable()->default('NULL');
		$table->string('username',110)->nullable()->default('NULL');
		$table->text('address1');
		$table->text('address2');
		$table->string('city',100);
		$table->integer('pincode')->lenght(10)->unsigned();
		$table->string('state',100);
		$table->string('country',100);
		$table->string('name',100);
		$table->integer('isClaimant')->lenght(2)->unsigned()->default(0);
		$table->integer('isOnboarded')->lenght(2)->nullable()->default(0)->unsigned();;
		$table->timestamp('created_at');
		$table->timestamp('updated_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('user_involved_in_agreement');
    }
}