<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsernotifysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
         Schema::create('usernotifys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('is_read')->default(0);        //是否已查看
            $table->integer('user_id')->unsigned();   //消息接收方
            $table->integer('notify_id')->unsigned();   //相关联的notify_id
           
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
             $table->foreign('notify_id')
                ->references('id')
                ->on('notifys')
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
        Schema::drop('usernotifys');
    }
}
