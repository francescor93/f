<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class FollowButton extends Component {

    public $profileUser = '';
    public $currentUser = '';

    public function mount() {
        $this->currentUser = auth()->user()->username;
    }

    public function toggle() {
        auth()->user()->following()->toggle(User::where('username', '=', $this->profileUser)->get());
    }

    public function render() {

        return view('livewire.follow-button')->with([
            'show' => $this->profileUser != $this->currentUser,
            'buttonText' => (auth()->user()->following->where('username', '=', $this->profileUser)->count()) ? trans('Unfollow') : trans('Follow')
        ]);
    }
}
