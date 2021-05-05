<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConsentDisclosures extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('consent_disclosures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("mediator_id")->comment("users.id");
            $table->foreign('mediator_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger("mediation_case_id")->comment("mediation_case.id");
            $table->foreign('mediation_case_id')->references('id')->on('mediation_case')->onDelete('cascade');
            $table->tinyInteger('consent1')->default(0)->comment("I accept and consent to act as a mediator in the captioned dispute");
            $table->tinyInteger('consent2')->default(0)->comment("I am qualified, possess the required competence, knowledge and expertise, and have sufficient time to be able to conduct the mediation proceedings within the time limits prescribed in the Rules");
            $table->tinyInteger('consent3')->default(0)->comment("I shall be, and remain, independent and neutral throughout the proceedings i.e. from beginning to end and ensure that my words, manner, attitude, body language and process management reflects an impartial and even-handed approach");
            $table->tinyInteger('consent4')->default(0)->comment("I shall conduct the mediation proceedings in a fair and impartial manner, and endeavour to provide a procedurally fair process in which each party is given an adequate opportunity to participate");
            $table->tinyInteger('consent5')->default(0)->comment("I shall maintain utmost confidentiality of all matters relating to mediation proceedings, including all documents, records, and communications, during as well as after its completion");
            $table->string('particulars1')->default(null)->nullable()->comment("Experience");
            $table->string('particulars2')->default(null)->nullable()->comment("Circumstances disclosing any past or present relationship with, or interest in, any of the parties or in relation to the subject-matter in dispute, whether financial, business, professional or other kind, which is likely to impair your independence or impartiality (list out");
            $table->string('particulars3')->default(null)->nullable()->comment("Circumstances which are likely to affect your ability to devote sufficient time to the mediation and in particular your ability to complete the entire mediation within the time limits prescribed under the Rules");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('consent_disclosures');
    }

}
