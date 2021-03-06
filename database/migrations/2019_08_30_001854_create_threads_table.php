<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')
                ->unique()
                ->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('channel_id');
            $table->string('title');
            $table->text('body');
            $table->unsignedInteger('replies_count')->default(0);
            $table->unsignedInteger('visits')->default(0);
            $table->boolean('locked')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
