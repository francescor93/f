<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model {
    use HasFactory;

    /**
     * The post() function defines a relationship where an object belongs to a Post class.
     *
     * @return The `post()` function is returning a relationship definition using the `belongsTo`
     * method in Laravel's Eloquent ORM. This indicates that the current model has a belongs-to
     * relationship with the `Post` model.
     */
    public function post() {
        return $this->belongsTo(Post::class);
    }

    /**
     * The function `user()` returns the relationship between the current object and a User object.
     *
     * @return The code snippet is defining a relationship method named `user` that returns the
     * relationship between the current model and the `User` model using the `belongsTo` method. This
     * indicates that the current model belongs to a user.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
