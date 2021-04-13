<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediationStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mediation_status_logs', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger("user_id")->comment("users.id");
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->unsignedBigInteger("mediation_case_id")->comment("mediation_case.id");
			$table->foreign('mediation_case_id')->references('id')->on('mediation_case')->onDelete('cascade');
			$table->tinyInteger("status")->default(0)->comment("New request=0,Accepte=1,Reject=2");
			$table->longText('description')->default(null)->nullable();
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
        Schema::dropIfExists('mediation_status_logs');
    }
}
