<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_reports', function (Blueprint $table) {
            // Tambahkan kolom untuk koordinat setelah 'location_address'
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('fire_reports', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};