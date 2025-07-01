<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            [
                'key' => 'site_password_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Enable site-wide password protection',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_password',
                'value' => '',
                'type' => 'string',
                'description' => 'Site-wide password for visitor access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_password_message',
                'value' => 'This site is password protected. Please enter the password to continue.',
                'type' => 'string',
                'description' => 'Message displayed on password protection page',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
};
