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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Заголовок (О нас / Контакты)
            $table->string('slug')->unique(); // Технический адрес (about / contacts)
            $table->text('content'); // Основной текст (можно с HTML)
            $table->json('metadata')->nullable(); // Для доп. данных (телефон, почта, карта)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
