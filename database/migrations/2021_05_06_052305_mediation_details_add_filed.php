<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediationDetailsAddFiled extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('consent_disclosures', function(Blueprint $table) {
            $table->text('particulars4')->default(null)->nullable()->comment("ability to complete the entire mediation within the time limits prescribed under the Rules");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('consent_disclosures', function($table) {
            $table->dropColumn('particulars4');
        });
    }

}
