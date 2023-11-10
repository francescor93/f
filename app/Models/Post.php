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

    /**
     * It returns all the posts that are visible to the user, ordered by creation date
     *
     * @param user the user who is requesting the posts
     *
     * @return A collection of posts
     */
    public static function getHome() {
        return self::where(function ($query) {

            /* Checking if the privacy is ALL or REGISTERED USERs or (ONLY ME and I'm the sender of the
                post). */
            $query->where(function ($query) {
                $query->where('privacy', '=', '0')
                    ->orWhere('privacy', '=', '1')
                    ->orWhere(function ($query) {
                        $query->where('privacy', '=', '2')
                            ->where('sender_id', '=', auth()->user()->id);
                    });
            })

                /* Checking if the post is sent by me or sent by one of the users I follow or
                    addressed to me or addressed to one of the users I follow. */
                ->where(function ($query) {
                    $query->where('sender_id', '=', auth()->user()->id)
                        ->orWhere(function ($query) {
                            $query->whereIn('sender_id', auth()->user()->following()->pluck('id'));
                        })
                        ->orWhere('recipient_id', '=', auth()->user()->id)
                        ->orWhere(function ($query) {
                            $query->whereIn('recipient_id', auth()->user()->following()->pluck('id'));
                        });
                })

                /* Checking if the post is not deleted. */
                ->where(function ($query) {
                    $query->whereNull('deleted_at');
                });
        })
            ->orderByDesc('created_at')
            ->get();
    }
}
