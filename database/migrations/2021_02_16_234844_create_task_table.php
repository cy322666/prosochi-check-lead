<?php

class CreateClientsTable extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        \Illuminate\Support\Facades\Schema::create('tasks', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->integer('responsible_user_id');
            $table->timestamp('complete_till_at')->nullable();
            $table->string('status')->default('wait');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\Schema::table('tasks', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->index('id');
            $table->index('task_id');
            $table->index('responsible_user_id');
            $table->index('complete_till_at');
            $table->index('status');
        });
    }

    public function down()
    {
        \Illuminate\Support\Facades\Schema::dropIfExists('tasks');
    }
}
