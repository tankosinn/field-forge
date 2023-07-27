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
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('employee');
            $table->integer('visit');
            $table->integer('rel_id')->nullable();
            $table->tinyInteger('type')->comment('1 - Ziyaret Oluşturuldu. | 2 - Fotoğraf Yüklendi. | 3 - Ziyaret Tamamlandı | 4 - Ziyaret Güncellendi.');
            $table->text('note')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
    }
};
