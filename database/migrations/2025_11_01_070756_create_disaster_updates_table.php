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
        Schema::create('disaster_updates', function (Blueprint $table) {
            $table->id('disaster_id');
            $table->enum('type', ['flood', 'fire', 'earthquake', 'typhoon', 'landslide']);
            $table->text('content');
            $table->string('affected_area');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disaster_updates');
    }
};
