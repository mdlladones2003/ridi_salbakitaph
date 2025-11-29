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
        Schema::create('barangays', function (Blueprint $table) {
            $table->id('barangay_id');
            $table->string('name');
            $table->string('municipality');
            $table->string('province');
            $table->decimal('latitude', 18, 14);
            $table->decimal('longitude', 18, 14);
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->string('source')->default('fallback'); // e.g., nominatim | geoapify | manual | fallback
            $table->float('accuracy', 8, 2)->nullable();   // e.g., confidence level 0–100
            $table->timestamps();

            $table->index(['municipality', 'province']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangays');
    }
};
