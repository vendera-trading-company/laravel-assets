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
        Schema::create('laravel_asset_pdfs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('header_id')->nullable();
            $table->string('main_id')->nullable();
            $table->string('footer_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_asset_pdfs');
    }
};
