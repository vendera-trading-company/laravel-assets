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
        Schema::create('laravel_asset_files', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('disk')->nullable();
            $table->string('relative_path')->nullable();
            $table->string('absolute_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_asset_files');
    }
};