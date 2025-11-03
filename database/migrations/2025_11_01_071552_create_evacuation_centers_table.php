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
        Schema::create('evacuation_centers', function (Blueprint $table) {
            $table->id('center_id');
            $table->foreignId('barangay_id')->constrained('barangays', 'barangay_id')->onDelete('cascade');
            $table->string('name');
            $table->text('address');
            $table->decimal('latitude', 18, 14);
            $table->decimal('longitude', 18, 14);
            $table->integer('capacity');
            $table->integer('current_occupancy')->default(0);
            $table->json('facilities')->nullable();
            $table->string('contact_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evacuation_centers');
    }
};
