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

    /**
     * The function `getProfile` retrieves posts from the database based on privacy settings, sender
     * and recipient IDs, and whether the post has been deleted, and returns them in descending order
     * of creation.
     *
     * @param User user The "user" parameter is an instance of the User class. It represents the user
     * whose profile is being retrieved.
     *
     * @return a collection of posts that meet certain criteria. The criteria include checking the
     * privacy settings of the posts, checking if the post is sent by the owner of the profile or
     * addressed to them, and checking if the post is not deleted. The posts are ordered in descending
     * order based on their creation date.
     */
    public static function getProfile(User $user) {
        return self::where(function ($query) use ($user) {

            /* Checking if the privacy is ALL or REGISTERED USERs or (ONLY ME and I'm the sender of the
                post). */
            $query->where(function ($query) use ($user) {
                $query->where('privacy', '=', '0')
                    ->orWhere('privacy', '=', '1')
                    ->orWhere(function ($query) use ($user) {
                        $query->where('privacy', '=', '2')
                            ->where('sender_id', '=', $user->id);
                    });
            })
                /* Checking if the post is sent by the owner of the profile or addressed to them. */
                ->where(function ($query) use ($user) {
                    $query->where('sender_id', '=', $user->id)
                        ->orWhere('recipient_id', '=', $user->id);
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
