<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up()
    {
        Schema::create('kid_project_viewer', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->date('view_date')->nullable();
            $table->string('scratch_id', 20);
        });
    }
    public function down()
    {
        Schema::dropIfExists('kid_project_viewer');
    }
};
