<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mediation_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger("user_id")->comment("users.id");
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');			
			$table->text('area_of_specialization')->default(null)->nullable();
			$table->text('no_of_arbitrations')->default(null)->nullable();
			$table->text('linked_in_profile_link')->default(null)->nullable();
			$table->text('experience')->default(null)->nullable();
			$table->tinyInteger('is_accept1')->default(0)->nullable();
			$table->tinyInteger('is_accept2')->default(0)->nullable();
			$table->tinyInteger('is_accept3')->default(0)->nullable();
			$table->text('filed1')->default(null)->nullable();
			$table->text('filed2')->default(null)->nullable();
			$table->text('filed3')->default(null)->nullable();
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
		Schema::dropIfExists('mediation_details');
    }
}
