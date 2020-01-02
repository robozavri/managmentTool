<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('projectId');
            $table->index('projectId');
            $table->integer('userId');
            $table->index('userId');
            $table->integer('parentId')->default(0);
            $table->index('parentId');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('childSort')->nullable();
            $table->tinyInteger('childCount')->nullable()->default(0);
            $table->tinyInteger('childDoneCount')->nullable()->default(0);
            $table->enum('status',['backlog','progress','test','done'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
}
