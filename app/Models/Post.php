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

    /**
     * This function returns the first row from the database where the token column is equal to the token
     * parameter
     *
     * @param string token The token of the post you want to get.
     *
     * @return The first row of the table that matches the token.
     */
    public static function getByToken(string $token) {
        return self::where('token', '=', $token)
            ->first();
    }

}
