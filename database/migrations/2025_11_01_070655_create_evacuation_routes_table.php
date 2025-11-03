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
        Schema::create('evacuation_routes', function (Blueprint $table) {
            $table->id('route_id');
            $table->foreignId('barangay_id')->constrained('barangays', 'barangay_id')->onDelete('cascade');
            $table->string('route_name');
            $table->string('start_point');
            $table->string('end_point');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evacuation_routes');
    }
};
