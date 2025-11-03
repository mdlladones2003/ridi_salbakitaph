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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id('alert_id');
            $table->foreignId('disaster_id')->constrained('disaster_updates', 'disaster_id')->onDelete('cascade');
            $table->text('message');
            $table->enum('severity', ['info', 'warning', 'critical']);
            $table->boolean('is_active')->default(true);
            $table->timestamp('sent_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
