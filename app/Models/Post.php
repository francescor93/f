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
     * The function `likedByUsers` returns a relationship where the current object is liked by multiple
     * users.
     *
     * @return The `likedByUsers` function is returning a relationship where the current model is
     * related to the `User` model through a many-to-many relationship defined by the `reactions`
     * table. The relationship is filtered to only include users who have reacted with a 'like' type
     * reaction.
     */
    public function likedByUsers() {
        return $this->belongsToMany(User::class, 'reactions')
            ->where('reactions.type', 'like');
    }

    /**
     * The function `dislikedByUsers` returns users who have disliked a specific item.
     *
     * @return The `dislikedByUsers` function is returning a relationship where the current model is
     * related to the `User` model through the `reactions` table. It filters the results to only
     * include users who have a 'dislike' reaction associated with the current model.
     */
    public function dislikedByUsers() {
        return $this->belongsToMany(User::class, 'reactions')
            ->where('reactions.type', 'dislike');
    }

    /**
     * The function checks if the current user has liked a specific item.
     *
     * @return The `getIsLikedByCurrentUserAttribute` function is returning a boolean value. If the
     * current user is not authenticated (not logged in), it will return `false`. If the current user
     * is authenticated, it will check if the user has liked the current item and return `true` if they
     * have, or `false` if they have not.
     */
    public function getIsLikedByCurrentUserAttribute() {
        if (!auth()->check()) {
            return false;
        }

        return $this->likedByUsers()->where('user_id', auth()->id())->exists();
    }

    /**
     * The function checks if the current user has disliked a specific item.
     *
     * @return The `getIsDislikedByCurrentUserAttribute` function is returning a boolean value. It
     * checks if the current user is authenticated (logged in). If the user is not authenticated, it
     * returns `false`. If the user is authenticated, it checks if the current user has disliked the
     * item represented by the model instance. If the current user has disliked the item, it returns
     * `true`, otherwise it returns
     */
    public function getIsDislikedByCurrentUserAttribute() {
        if (!auth()->check()) {
            return false;
        }

        return $this->dislikedByUsers()->where('user_id', auth()->id())->exists();
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
