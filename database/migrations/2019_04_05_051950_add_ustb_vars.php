<?php

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
            ['key' => 'semester', 'value' => '2019-2020-1'],
            ['key' => 'semester_start', 'value' => '2019-09-09'],
            ['key' => 'current_week', 'value' => '1'],
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
