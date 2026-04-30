<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repair_model_issue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_model_id')->constrained()->cascadeOnDelete();
            $table->foreignId('repair_issue_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 12, 2)->nullable();
            $table->unsignedInteger('estimated_minutes')->nullable();
            $table->timestamps();

            $table->unique(['repair_model_id', 'repair_issue_id'], 'repair_model_issue_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_model_issue');
    }
};
