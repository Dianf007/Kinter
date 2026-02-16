<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('kid_projects_scratch', function (Blueprint $table) {
            if (!Schema::hasColumn('kid_projects_scratch', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('students')->onDelete('cascade');
            }
            if (!Schema::hasColumn('kid_projects_scratch', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('expired_dt');
            }
            if (!Schema::hasColumn('kid_projects_scratch', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('kid_projects_scratch', function (Blueprint $table) {
            if (Schema::hasColumn('kid_projects_scratch', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('kid_projects_scratch', 'is_published')) {
                $table->dropColumn('is_published');
            }
            if (Schema::hasColumn('kid_projects_scratch', 'created_at')) {
                $table->dropColumn(['created_at', 'updated_at']);
            }
        });
    }
};
