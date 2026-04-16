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
        // Сами контакты (Диспетчеры или Группы)
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // "Диспетчер" или "Наш чат"
            $table->string('subtitle')->nullable(); // Номер или пояснение
            $table->enum('category', ['personal', 'group'])->default('personal');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Ссылки/Кнопки мессенджеров для этих контактов
        Schema::create('contact_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'telegram', 'whatsapp', 'viber', 'link'
            $table->string('url');  // Прямая ссылка
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts_and_links_tables');
    }
};
