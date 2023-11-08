<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    /**
     * The sender() function returns a relationship between the Message model and the User model
     *
     * @return The sender of the message.
     */
    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * The recipient() function returns a relationship between the Message model and the User model
     *
     * @return The recipient of the message.
     */
    public function recipient() {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * The attachments() function returns all the attachments that belong to this post
     *
     * @return A collection of Attachment objects.
     */
    public function attachments() {
        return $this->hasMany(Attachment::class);
    }
}
