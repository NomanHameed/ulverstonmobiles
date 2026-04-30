<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('color')->nullable();
            $table->string('color_hex', 9)->nullable();
            $table->string('storage')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->decimal('price_modifier', 12, 2)->default(0);
            $table->decimal('price_override', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'color', 'storage'], 'product_variants_combo_unique');
            $table->index(['product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
