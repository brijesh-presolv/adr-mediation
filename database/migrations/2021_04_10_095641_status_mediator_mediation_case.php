<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StatusMediatorMediationCase extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('mediators_mediation_cases_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("mediator_id")->comment("users.id");
            $table->foreign('mediator_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger("mediation_case_id")->comment("mediation_case.id");
            $table->foreign('mediation_case_id')->references('id')->on('mediation_case')->onDelete('cascade');
            $table->tinyInteger("status")->default(0)->comment("New request=0,Accepte=1,Reject=2");
            $table->tinyInteger("user_type")->default(0)->comment("mediators=1,other_user=0");
            $table->longText('description')->default(null)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('mediators_mediation_cases_status');
    }

}
