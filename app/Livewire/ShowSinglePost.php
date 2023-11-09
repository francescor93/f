<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class ShowSinglePost extends Component {

    public $item;
    public $post;

    public function render() {

        // If provided item is a string, search the original content by token and check that the user is allowed to view it
        if (is_string($this->item)) {
            $this->post = Post::getByToken($this->item);
        }

        // If provided item is an instance of Post, I already know for sure that the user is allowed to view it
        elseif ($this->item instanceof Post) {
            $this->post = $this->item;
        }

        // In any other case, return a not found error
        else {
            abort(404);
        }

        return view('livewire.show-single-post')->with(['post' => $this->post]);
    }
}
