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
        Schema::create('help_offers', function (Blueprint $table) {
            $table->id('offer_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('offer_type', ['rescue', 'shelter', 'medical', 'supplies']);
            $table->text('description')->nullable();
            $table->decimal('latitude', 18, 14);
            $table->decimal('longitude', 18, 14);
            $table->boolean('is_available')->default(true);
            $table->integer('capacity')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_offers');
    }
};
