<?php


namespace App\Http\Controllers;


use App\Models\User;

class UserController extends Controller
{
    public function get()
    {
        User::where('role', 'manager')->delete();

        foreach ($this->amoClient->account->userGroups as $group) {

            if(stristr($group->name, 'Отдел продаж') !== false) {

                $groupSale[] = [
                    'id' => $group->id,
                ];
            }
        }

        foreach ($this->amoClient->account->users as $amoUser) {

            if($amoUser->is_active) {

                foreach ($groupSale as $group) {

                    if($amoUser->group->id == $group['id']) {

                        $userModel = User::where('user_id', $amoUser->id)->first();

                        if(!$userModel) {

                            $user = new User();

                            $user->user_id = $amoUser->id;
                            $user->name = $amoUser->name;
                            $user->group_id = $amoUser->group->id;
                            $user->role = 'manager';
                            $user->save();
                        }
                    }
                }
            }
        }
    }
}
