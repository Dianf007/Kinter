<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up()
    {
        Schema::create('kid_projects_scratch', function (Blueprint $table) {
            $table->id();
            $table->string('scratch_id', 50);
            $table->text('title');
            $table->text('description');
            $table->text('instructions');
            $table->date('expired_dt')->nullable();
        });
    }
    public function down()
    {
        Schema::dropIfExists('kid_projects_scratch');
    }
};
