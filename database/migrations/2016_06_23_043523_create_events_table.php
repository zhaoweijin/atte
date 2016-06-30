<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->string('location')->nullable();
            $table->string('bg_type', 15)->default('color');
            $table->string('bg_color')->default(config('attendize.event_default_bg_color'));
            $table->string('bg_image_path')->nullable();
            $table->string('icon');
            $table->string('game');
            $table->unsignedBigInteger('game_id');
            $table->text('description');
            $table->string('zone_url');
            $table->string('down_url');
            $table->tinyInteger('type');
            $table->smallInteger('position');
            $table->tinyInteger('hot');
            $table->tinyInteger('is_tao');

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->dateTime('on_sale_date')->nullable();

            $table->integer('account_id')->unsigned()->index();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');



            $table->unsignedInteger('organiser_id');
            $table->foreign('organiser_id')->references('id')->on('organisers');

            $table->boolean('is_live')->default(false);

            $table->unsignedInteger('get_num');
            $table->unsignedInteger('tao_num');

            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
