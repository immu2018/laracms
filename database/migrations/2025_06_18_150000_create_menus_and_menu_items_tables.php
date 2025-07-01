<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable(); // e.g. header, footer
            $table->timestamps();
        });
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->string('title');
            $table->string('url')->nullable();
            $table->integer('order')->default(0);
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            $table->string('type')->nullable(); // e.g. custom, post, page, category
            $table->unsignedBigInteger('related_id')->nullable(); // for linking to posts/pages
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
    }
};
