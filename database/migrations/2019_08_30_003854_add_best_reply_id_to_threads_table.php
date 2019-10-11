<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBestReplyIdToThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->unsignedInteger('best_reply_id')
                ->nullable()
                ->after('channel_id');

            $table->foreign('best_reply_id')
                ->references('id')
                ->on('replies')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->dropForeign('threads_best_reply_id_foreign');
            $table->dropColumn('best_reply_id');
        });
    }
}
