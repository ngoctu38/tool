<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatOtBatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ot_batch', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id');
            $table->string('project_code', 255)->nullable();
            $table->date('date');
            $table->string('note')->nullable();
            $table->integer('requested_by_id');
            $table->integer('approved_by_id')->nullable();
            $table->integer('rejected_by_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::table('ot_requests', function (Blueprint $table) {
            $table->bigInteger('batch_id')->nullable()->after('id');
            $table->integer('requested_by_id')->nullable();
            $table->dropColumn('confirmed_by_id');
            $table->tinyInteger('status')->default(1);
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
        Schema::table('ot_requests', function (Blueprint $table) {
            $table->dropColumn(['batch_id']);
            $table->integer('confirmed_by_id')->nullable();
            $table->dropColumn('requested_by_id');
            $table->dropColumn('status');
        });
        Schema::dropIfExists('ot_batch');
    }
}
