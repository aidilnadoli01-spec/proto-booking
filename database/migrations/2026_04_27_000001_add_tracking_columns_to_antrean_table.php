<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrean', function (Blueprint $table) {
            $table->timestamp('dipanggil_pada')->nullable()->after('status');
            $table->timestamp('selesai_pada')->nullable()->after('dipanggil_pada');
            $table->timestamp('dibatalkan_pada')->nullable()->after('selesai_pada');
            $table->foreignId('updated_by_user_id')->nullable()->after('dibatalkan_pada')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('antrean', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by_user_id');
            $table->dropColumn(['dipanggil_pada', 'selesai_pada', 'dibatalkan_pada']);
        });
    }
};

