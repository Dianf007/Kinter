<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Rename subjects table to mapels
        Schema::rename('subjects', 'mapels');
        
        // Rename schedule_subject_teachers table to schedule_mapel_teachers
        Schema::rename('schedule_subject_teachers', 'schedule_mapel_teachers');
        
        // Update foreign key in schedule_mapel_teachers
        Schema::table('schedule_mapel_teachers', function (Blueprint $table) {
            $table->renameColumn('subject_id', 'mapel_id');
        });
    }

    public function down(): void
    {
        // Revert: Rename back to original names
        Schema::rename('mapels', 'subjects');
        Schema::rename('schedule_mapel_teachers', 'schedule_subject_teachers');
        
        Schema::table('schedule_subject_teachers', function (Blueprint $table) {
            $table->renameColumn('mapel_id', 'subject_id');
        });
    }
};
