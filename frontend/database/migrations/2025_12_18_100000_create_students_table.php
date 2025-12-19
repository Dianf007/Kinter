<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel students sudah ada dari migration lama, tambahkan kolom baru jika belum ada
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'school_id')) {
                $table->unsignedBigInteger('school_id')->nullable()->after('id');
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
            if (!Schema::hasColumn('students', 'classroom_id')) {
                $table->unsignedBigInteger('classroom_id')->nullable()->after('school_id');
                $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('set null');
            }
            if (!Schema::hasColumn('students', 'nis')) {
                $table->string('nis')->nullable()->unique()->after('classroom_id');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['L', 'P'])->nullable()->after('nis');
            }
            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->after('birth_date');
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
            if (Schema::hasColumn('students', 'classroom_id')) {
                $table->dropForeign(['classroom_id']);
                $table->dropColumn('classroom_id');
            }
        });
    }
};
