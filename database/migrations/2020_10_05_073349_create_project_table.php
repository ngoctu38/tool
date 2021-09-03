<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('code', 100)->nullable();
            $table->integer('owner_id')->nullable();
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->enum('status', [
                'bidding',
                'on-going',
                'finished',
                'canceled',
            ]);
            $table->integer('rate')->nullable()->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('project_allocate', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->integer('user_id');
            $table->integer('effort')->default(8);
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->enum('status', [
                'plan',
                'on-going',
                'canceled',
            ]);
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('code', 100)->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('owner_id')->nullable();
            $table->date('from_date');
            $table->date('due_date')->nullable();
            $table->enum('status', [
                'new',
                'in-progress',
                'finished',
                'closed',
            ]);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('project_allocate');
        Schema::dropIfExists('projects');
    }
}
