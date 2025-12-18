<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Drop FK + column room_id.
            try {
                $table->dropForeign(['room_id']);
            } catch (\Throwable $e) {
                // ignore if missing
            }

            // Drop uniqueness that depended on room_id.
            try {
                $table->dropUnique('unique_room_time');
            } catch (\Throwable $e) {
                // ignore if missing
            }

            if (Schema::hasColumn('schedules', 'room_id')) {
                $table->dropColumn('room_id');
            }

            // New uniqueness for weekly schedule: classroom + day(date canonical) + time.
            $table->unique(['classroom_id', 'date', 'start_time', 'end_time'], 'unique_class_time');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            try {
                $table->dropUnique('unique_class_time');
            } catch (\Throwable $e) {
                // ignore if missing
            }

            if (!Schema::hasColumn('schedules', 'room_id')) {
                $table->foreignId('room_id')->after('classroom_id')->constrained('rooms')->onDelete('cascade');
            }

            $table->unique(['room_id', 'date', 'start_time', 'end_time'], 'unique_room_time');
        });
    }
};
