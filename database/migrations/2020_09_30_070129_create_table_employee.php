<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->unique();
            $table->string('full_name', 255)->nullable();
            // Personal
            $table->date('dob')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('pid', 100)->nullable();
            $table->string('mobile')->nullable();
            // Contract
            $table->integer('contract_type')->nullable();
            $table->date('contract_from')->nullable();
            $table->date('contract_to')->nullable();
            // Job
            $table->string('job_code', 100)->nullable();
            $table->string('job_level', 100)->nullable();
            $table->string('current_team', 255)->nullable();

            $table->tinyInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('employees');
    }
}
