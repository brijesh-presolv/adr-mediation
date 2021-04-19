<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediationCaseComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mediation_case_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('mediation_case_id');
			$table->foreign('mediation_case_id')->references('id')->on('mediation_case')->onDelete('cascade');
            $table->tinyInteger('type')->default(0)->comment("Share=0, Private=1");
            $table->longText('comment');
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
        Schema::dropIfExists('mediation_case_comment');
    }
}
