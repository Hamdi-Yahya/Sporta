<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabang_olahragas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_cabor')->unique();
            $table->string('slug')->unique();
            $table->boolean('dapat_dibooking')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabang_olahragas');
    }
};
