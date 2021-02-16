<?php


namespace App\Http\Controllers;


use App\Models\User;

class UserController extends Controller
{
    public function get()
    {
        foreach ($this->amoClient->account->users as $amoUser) {

            if($amoUser->is_active == true && stripos('Отдел продаж', $amoUser->group->name) !== false) {

                $user = new User();
                $user->user_id = $amoUser->id;
                $user->name = $amoUser->name;
                $user->group_id = $amoUser->group->id;
                $user->role = 'manager';
                $user->save();
            }
        }
    }

    public function clear()
    {
        //чистим менеджеров в базе
    }
}
