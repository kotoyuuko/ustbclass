<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolIdentificationToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('elearning_id')->nullable();
            $table->string('elearning_pwd')->nullable();
            $table->timestamp('coursed_at')->nullable();
            $table->timestamp('sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('elearning_id');
            $table->dropColumn('elearning_pwd');
            $table->dropColumn('coursed_at');
            $table->dropColumn('sent_at');
        });
    }
}
