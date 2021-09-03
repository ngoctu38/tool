<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDb20210816 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Org department
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('name', 191)->unique()->index();
            $table->integer('owner_id')->nullable();
            $table->timestamps();

            $table->index(['name']);
            $table->index(['owner_id']);
        });

        Schema::create('users_departments', function (Blueprint $table) {
            $table->id();
            $table->integer('department_id');
            $table->integer('admin_user_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('role', 191)->nullable();
            $table->timestamps();

            $table->index(['department_id', 'admin_user_id', 'employee_id']);
        });

        //
        Schema::table('employees', function (Blueprint $table){
            $table->integer('admin_user_id')->nullable()->after('full_name');
            $table->integer('department_id')
                ->nullable()->after('full_name');
            $table->integer('sub_department_id')
                ->nullable()->after('full_name');

            // Indexing
            $table->index(['department_id', 'sub_department_id']);
        });

        Schema::table('projects', function (Blueprint $table){
            $table->integer('pm_id')->nullable()->after('owner_id');

            // Indexing
//            $table->index(['owner_id', 'pm_id']);
            $table->index(['code', 'title']);
            $table->index(['from_date', 'to_date']);
        });
        Schema::dropIfExists('project_allocate');

        Schema::create('employee_allocations', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->integer('employee_id');
            $table->string('job', 191)->nullable();
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->float('hours')->default(8); // 8 hours
            $table->float('calendar_effort')->default(0); // rating
            $table->boolean('billable')->default(true);
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->index(['project_id', 'employee_id']);
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('employee_allocations');
        Schema::dropIfExists('users_departments');
        Schema::dropIfExists('departments');
    }
}
