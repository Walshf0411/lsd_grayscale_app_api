<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('screen_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId("screen_config_id")->references("id")->on("screen_configs");
            $table->string("name");
            $table->string("type");
            $table->json("attributes")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screen_sections');
    }
};
