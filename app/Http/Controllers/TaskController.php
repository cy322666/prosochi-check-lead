<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Task;

class TaskController extends Controller
{
    public function get()
    {
        $tasks = $this->amoClient->tasks()->where('filter[status][]', 0)->call();

        if($tasks) {

            $date = (new Carbon())->setTimezone('Europe/Moscow');
            $timestamp = $date->subDays(2)->timestamp;

            foreach ($tasks->toArray() as $task) {

                if($task['complete_till_at'] < $timestamp) {

                    $arrayTasks[] = ['id' => $task['id']];
                }
            }

            if($arrayTasks) {

                $users = User::all();

                foreach ($arrayTasks as $arrayTask) {

                    $task = $this->amoClient->tasks()->find($arrayTask['id']);

                    foreach ($users as $user) {

                        if($task->responsible_user_id == $user->user_id) {

                            if($user->role == 'manager') {

                                $taskModel = Task::where(['task_id' => $task->id])->first();

                                if($taskModel) {

                                    Log::info('повторная отработка по задаче'.$task->id);

                                    continue;
                                } else {

                                    $taskModel = new Task();
                                    $taskModel->task_id = $task->id;
                                    $taskModel->responsible_user_id = $task->responsible_user_id;
                                    $taskModel->complete_till_at = $task->complete_till_at;
                                    $taskModel->save();
                                }

                                $rop = $user->getRopByUser();

                                $task->responsible_user_id = $rop->user_id;
                                $task->save();

                                Log::info('смена ответственного на РОпа ('.$rop->name.') в задаче '.$task->id);

                                if($task->element_type == 2) {

                                    $lead = $this->amoClient->leads()->find($task->element_id);

                                    if($lead->pipeline_id == 1945420 || $lead->pipeline_id == 3514120) {

                                        $note = $lead->createNote($type = 4);
                                        $note->text = $user->name.' просрочил выполнение задачи на 2 дня, сделка возвращена РОПу';
                                        $note->save();

                                        $taskModel->lead_id = $lead->id;
                                        $taskModel->note_id = $note->id;
                                        $taskModel->status = 'completed';
                                        $taskModel->save();
                                    } else {
                                        Log::info('задача ' . $task->id . ' поставлена на сделку не в нужной воронке');

                                        $taskModel->status = 'no_pipeline';
                                        $taskModel->save();
                                    }
                                } else {
                                    Log::info('задача ' . $task->id . ' поставлена не на сделку');

                                    $taskModel->status = 'no_lead';
                                    $taskModel->save();
                                }
                            }
                        }
                    }
                    exit;
                }
            }
        }
    }
}
