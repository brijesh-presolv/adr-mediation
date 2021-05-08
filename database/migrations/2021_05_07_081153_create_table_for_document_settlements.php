<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableForDocumentSettlements extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('document_settlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mediation_case_id');
            $table->foreign('mediation_case_id')->references('id')->on('mediation_case')->onDelete('cascade');
            $table->longText('file_path');
            $table->unsignedBigInteger('uploaded_by');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('document_settlements');
    }

}
