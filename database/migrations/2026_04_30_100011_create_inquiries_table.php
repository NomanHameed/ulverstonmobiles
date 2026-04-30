<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_id', 32)->unique();

            $table->enum('type', ['product', 'repair']);
            $table->enum('status', [
                'new',
                'contacted',
                'booked',
                'in_progress',
                'completed',
                'cancelled',
            ])->default('new');

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->string('customer_whatsapp')->nullable();
            $table->enum('preferred_contact', ['phone', 'whatsapp', 'email'])->default('whatsapp');
            $table->text('message')->nullable();

            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('repair_brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('repair_model_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('repair_issue_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('source', ['website', 'whatsapp', 'phone', 'manual'])->default('website');
            $table->json('meta')->nullable();

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('booked_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'type']);
            $table->index('created_at');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
