<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Reaction extends Component {
    public Post $post;

    public function like() {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $existingDislike = $this->post->dislikedByUsers()->where('user_id', $user->id)->first();
        if ($existingDislike) {
            $this->post->dislikedByUsers()->detach($user->id);
        }

        $existingLike = $this->post->likedByUsers()->where('user_id', $user->id)->first();
        if ($existingLike) {
            $this->post->likedByUsers()->detach($user->id);
        } else {
            $this->post->likedByUsers()->attach($user->id, ['type' => 'like']);
        }

        $this->post->refresh();
    }

    public function dislike() {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $existingLike = $this->post->likedByUsers()->where('user_id', $user->id)->first();
        if ($existingLike) {
            $this->post->likedByUsers()->detach($user->id);
        }

        $existingDislike = $this->post->dislikedByUsers()->where('user_id', $user->id)->first();
        if ($existingDislike) {
            $this->post->dislikedByUsers()->detach($user->id);
        } else {
            $this->post->dislikedByUsers()->attach($user->id, ['type' => 'dislike']);
        }

        $this->post->refresh();
    }

    public function render() {
        return view('livewire.reaction');
    }
}
