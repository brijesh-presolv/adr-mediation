<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManageSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_session', function (Blueprint $table) {
            $table->id();
            $table->integer('case_id')->legth(10)->unsigned()->nullable()->default(0);
            $table->string('session_date',225);
            $table->text('note');
            $table->string('zoom_id',10000);
            $table->integer('scheduled_by')->legth(10)->unsigned()->nullable()->default(0)->comment("who create session");
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manage_session');
    }
}
