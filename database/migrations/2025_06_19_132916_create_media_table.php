<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Original file name
            $table->string('path'); // Storage path
            $table->string('type')->nullable(); // mime type
            $table->unsignedBigInteger('size')->nullable(); // file size in bytes
            $table->unsignedBigInteger('uploaded_by')->nullable(); // user id
            $table->timestamps();
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
};
