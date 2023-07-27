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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_route');
            $table->integer('employee');
            $table->integer('store');
            $table->integer('store_branch');
            $table->tinyInteger('day');
            $table->tinyInteger('status')->comment('1 - Ziyarete Başlandı, 2 - Ziyaret Tamamlandı');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
