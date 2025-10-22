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
        Schema::create('fire_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alamat');
            $table->enum('jenis_ikon',['kebakaran','pos_pemadam'])->default('kebakaran');
            $table->decimal('latitude', 10, 7); 
            $table->decimal('longitude', 11, 7);
            $table->date('tanggal_kejadian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fire_areas');
    }
};
