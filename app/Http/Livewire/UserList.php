<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $buttonGreen;

    public function deArchive($id)
    {
        $user = User::find($id);
            $user->archived_at = null;
            $user->archive_message = null;
            $user->user_role = 2;
            $user->save();

        return redirect()->to('/ListUser');
    }


    public function render()
    {
        $newUsers = User::where('user_role', '!=', 1)->paginate(10);
        return view('livewire.user-list',
            ['users' =>$newUsers,
            'buttonGreen' => $this->buttonGreen]);
    }
}
