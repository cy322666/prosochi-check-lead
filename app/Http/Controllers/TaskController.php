<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;

class TaskController extends Controller
{
        //+ааторизация в амо
        //+запрашиваем все открытые задачи
        //+проверяем просроченные
        //пишем в бд
        //отрабатываем логику
    public function get()
    {
        $tasks = $this->amoClient->tasks()->where('filter[status][]', 0)->call();

        if($tasks) {

            $date = (new Carbon())->setTimezone('Europe/Moscow');
            $timestamp = $date->subDays(2)->timestamp;

            foreach ($tasks->toArray() as $task) {

                if($task['complete_till_at'] < $timestamp) {

                    $arrayTasks[] = $task['id'];
                }
            }

            if($arrayTasks) {

                //Записали в бд

                //запрос менеджеров

                $users = User::all();

                foreach ($arrayTasks as $arrayTask) {

                    $task = $this->amoClient->tasks()->find($arrayTask['id']);

                    foreach ($users as $user) {

                        if($task->responsible_user_id == $user->user_id) {

                            if($user->role == 'manager') {

                                //getRopByUser
                            }
                        }
                    }
                }
                //проводим логику
            }
        }
    }
}
