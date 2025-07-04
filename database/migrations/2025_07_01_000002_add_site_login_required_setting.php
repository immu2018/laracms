<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Insert site login required setting
        DB::table('site_settings')->insert([
            'key' => 'site_login_required',
            'value' => 'false',
            'type' => 'boolean',
            'description' => 'Require login to access entire site',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('site_settings')->where('key', 'site_login_required')->delete();
    }
};
