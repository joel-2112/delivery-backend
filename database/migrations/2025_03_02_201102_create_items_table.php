<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('name', 200);
        $table->text('description');
        $table->decimal('price', 8, 2); // Or float, integer, etc., depending on your needs
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
