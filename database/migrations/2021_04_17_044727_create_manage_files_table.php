<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("case_id")->comment("mediation_case.id");
            $table->string('file_name',110)->nullable()->default('NULL');
            $table->integer('uploaded_by')->legth(10)->unsigned()->nullable()->default(0)->comment("who_upload_files");
            // $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('manage_files');
    }
}
