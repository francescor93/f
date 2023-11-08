<?php

namespace App\Livewire;

use Livewire\Attributes\Rule;
use Livewire\Component;
use App\Models\Post;
use App\Models\Attachment;
use App\Http\Support\RateLimiter;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class CreatePost extends Component {
    use WithFileUploads;

    #[Rule('required|in:0,1,2')]
    public $privacy = '';

    #[Rule('required|min:3')]
    public $body = '';

    #[Rule(['attachments.*' => 'image|max:1024'])]
    public $attachments = [];

    public function save() {

        /* Getting the user id of the authenticated user. */
        $userId = auth()->user()->id;

        /* A rate limiter. It is limiting the number of posts a user can send in a given time. */
        $executed = RateLimiter::attempt(
            'send-post:' . $userId,
            10,
            function () use ($userId) {

                /* Validating the form data. */
                $this->validate();

                /* Creating a new post and saving it to the database. */
                $post = new Post();
                $post->body = $this->body;
                $post->privacy = $this->privacy;
                $post->sender_id = $userId;
                $post->recipient_id = $userId;
                $post->token = Str::uuid();
                $post->save();

                /* Saving the attachments to the database. */
                if ($this->attachments) {
                    foreach ($this->attachments as $attachment) {
                        $path = $attachment->store('public/postAttachments');
                        $attachment = new Attachment;
                        $attachment->file_path = str_replace('public/', '', $path);
                        $attachment->post_id = $post->id;
                        $attachment->save();
                    }
                }

                /* Resetting the form for a new post */
                $this->privacy = "";
                $this->body = "";
                $this->attachments = [];
            },
            300
        );

        if (!$executed) {
            return 'Too many posts sent. You may try again in ' . RateLimiter::availableIn('send-post:' . $userId) . ' seconds.';
        }
    }

    public function render() {
        return view('livewire.create-post')->with(['attachmentsCount' => count($this->attachments)]);
    }
}
