<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDb20210817 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('time_sheets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->tinyInteger('day_type')->default(1);
            //                'working-day', 1
            //                'saturday', 2
            //                'sunday', 3
            //                'holiday', 4
            $table->integer('employee_id');
            $table->string('account', 191);
            $table->integer('project_id');
            $table->string('project_code', 191);
            $table->dateTime('entry_time')->nullable();
            $table->dateTime('leave_time')->nullable();
            $table->float('working_time')->default(0); // auto-calculate
            $table->float('ot_time')->default(0);
            $table->float('on_time')->default(0);

            $table->timestamps();

            $table->index([
                'employee_id',
                'project_id',
                'account',
                'date',
            ]);
        });

        Schema::create('ot_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->tinyInteger('day_type')->default(1);
            $table->integer('employee_id');
            $table->string('account', 191);
            $table->integer('project_id');
            $table->string('project_code', 191);
            $table->dateTime('entry_time')->nullable();
            $table->dateTime('leave_time')->nullable();
            $table->float('ot_time')->default(0);
            $table->float('on_time')->default(0);
            $table->text('ot_reason')->nullable();

            $table->integer('confirmed_by_id')->nullable();
            $table->integer('approved_by_id')->nullable();
            $table->integer('rejected_by_id')->nullable();
            $table->timestamps();

            $table->index([
                'employee_id',
                'project_id',
                'date',
            ]);
            $table->index([
                'confirmed_by_id',
                'approved_by_id',
                'rejected_by_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('ot_requests');
        Schema::dropIfExists('time_sheets');
    }
}
