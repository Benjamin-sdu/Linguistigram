<?php

namespace App\Livewire\Connections;

use Livewire\Component;

class UserItem extends Component
{
    protected const LANGUAGE_CAP = 8;
    protected $listeners = ['openProfile'];

    public $user;

    public $description;

    public $languages_teach;

    public $languages_learn;
    public $status;

    public function mount($user = null)
    {
        $this->user = $user;
        $this->description = $user->description ?? 'No description provided';
        $this->status = $user->status ?? 'No status';
        $this->languages_teach = array_slice($user->languages_teach ?? [], 0, self::LANGUAGE_CAP);
        $this->languages_learn = array_slice($user->languages_learn ?? [], 0, self::LANGUAGE_CAP);
    }

    public function openProfile()
    {
        return redirect()->route('profile.show', ['user' => $this->user]);
    }

    public function render()
    {
        return view('livewire.connections.user-item');
    }
}
