<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class ShowAllPosts extends Component {

    public $source;
    public $posts;
    public $class;
    public $user;

    public function render() {
        if ($this->source == 'home') {
            $this->posts = Post::getHome();
        } elseif($this->source == 'profile') {
            $this->posts = Post::getProfile($this->user);
        } else {
            abort(400);
        }

        return view('livewire.show-all-posts')->with(['posts' => $this->posts]);
    }
}
