<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repair_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_brand_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('image_path')->nullable();
            $table->unsignedSmallInteger('release_year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['repair_brand_id', 'slug']);
            $table->index(['repair_brand_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_models');
    }
};
