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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('amount');
            $table->double('spent')->default(0);
            $table->double('limit')->default(0);
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('account_id')->nullable();
            $table->json('sub_category_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('exceeded')->default(false);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
