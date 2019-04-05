<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUstbVars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('vars')->insert([
            ['key' => 'semester', 'value' => '2018-2019-2'],
            ['key' => 'semester_start', 'value' => '2019-02-25'],
            ['key' => 'current_week', 'value' => '6'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('vars')->truncate();
    }
}
