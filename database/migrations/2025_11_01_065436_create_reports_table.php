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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('barangay_id')->constrained('barangays', 'barangay_id')->onDelete('cascade');
            $table->enum('type', ['flood', 'fire', 'earthquake', 'typhoon', 'landslide']);
            $table->enum('severity', ['low', 'moderate', 'high', 'critical']);
            $table->enum('status', ['pending', 'verified', 'resolved', 'false_alarm'])->default('pending');
            $table->text('content');
            $table->json('media')->nullable();
            $table->decimal('latitude', 18, 14);
            $table->decimal('longitude', 18, 14);
            $table->integer('verification_count')->default(0);
            $table->integer('affected_count')->nullable();
            $table->timestamp('reported_at');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['barangay_id', 'reported_at']);
            $table->index(['type', 'severity']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
