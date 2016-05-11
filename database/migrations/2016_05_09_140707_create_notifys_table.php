<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('notifys', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');        //内容:回答的内容，公告的内容
            $table->integer('type');        //1,消息提醒 2,公告
            $table->integer('target_id');   //对应的问题或回答的id
            $table->string('target_type',20);   //区分是问题还是回答，根据此去取id
            $table->string('action',60);    //动作类型：赞同，关注，回答等
            $table->integer('sender_id')->unsigned();   //发送者的id

            $table->foreign('sender_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
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
        Schema::drop('notifys');
    }
}
