<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class ArchiveMessage extends Component
{
    public $message;
    public $user;
    protected $rules = [
        'message' => ['required', 'max:255'],
    ];
    protected $messages = [
        'message.required' => 'Dit is verplicht', // TODO Figure out how to translate this (Don't know how to do this in livewire)
        'message.max' => 'Het bericht mag maximaal 255 tekens bevatten',
    ];

    public function validated()
    {
        if ($this->hasErrors()) {
            $this->addError('message', $this->errorMessage('message'));
        }
    }

    private function errorMessage($field)
    {
        return implode(' ', $this->getErrorMessagesFor($field));
    }

    public function mount($id)
    {
        $this->user = User::find($id);
    }

    public function archiveMessage()
    {
        $this->validate();
        $this->user->archive_message = $this->message;
        $this->user->archived_at = Carbon::now();
        $this->user->user_role = 3;
        $this->user->save();
        return redirect()->to('/ListUser');
    }


    public function render()
    {
        return view('livewire.archive-message');
    }
}
