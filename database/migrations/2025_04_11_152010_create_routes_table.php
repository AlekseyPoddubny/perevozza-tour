<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            // Если вдруг захотим откатить, придется добавить их обратно
            $table->string('departure_city')->nullable();
            $table->string('arrival_city')->nullable();
        });
    }
};
