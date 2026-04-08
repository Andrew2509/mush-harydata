<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDemographicsToSusResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sus_responses', function (Blueprint $table) {
            $table->integer('usia')->nullable()->after('user_id');
            $table->string('jenis_kelamin')->nullable()->after('usia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sus_responses', function (Blueprint $table) {
            $table->dropColumn(['usia', 'jenis_kelamin']);
        });
    }
}
