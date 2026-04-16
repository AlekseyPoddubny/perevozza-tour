<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');

            // Цены, устанавливаемые администратором
            $table->decimal('price_rub', 10, 2)->nullable()->comment('Цена в рублях');
            $table->decimal('price_eur', 10, 2)->nullable()->comment('Цена в евро');

            $table->string('status')->default('scheduled');
            $table->string('type')->default('additional');
            $table->dateTime('departure_at')->nullable();
            $table->string('frequency')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
