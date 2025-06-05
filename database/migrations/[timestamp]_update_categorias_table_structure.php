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
        Schema::table('categorias', function (Blueprint $table) {
            // Add slug column if it doesn't exist
            if (!Schema::hasColumn('categorias', 'slug')) {
                $table->string('slug')->nullable()->after('descripcion');
            }
            
            // Add imagen column if it doesn't exist
            if (!Schema::hasColumn('categorias', 'imagen')) {
                $table->string('imagen')->nullable()->after('descripcion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            if (Schema::hasColumn('categorias', 'slug')) {
                $table->dropColumn('slug');
            }
            
            if (Schema::hasColumn('categorias', 'imagen')) {
                $table->dropColumn('imagen');
            }
        });
    }
};