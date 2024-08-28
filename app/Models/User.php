<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail {
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'header_photo_url'
    ];

    /**
     * The headerPhotoUrl function returns the URL of the header photo, either from storage or a
     * default URL.
     *
     * @return Attribute The `headerPhotoUrl` function is returning an `Attribute` object. The
     * `Attribute::get` method is used to retrieve the value of the attribute, which is determined by
     * the callback function provided. In this case, the callback function checks if the
     * `header_photo_path` property is set for the current object. If it is set, it generates the URL
     * for the header photo using the `
     */
    public function headerPhotoUrl(): Attribute {
        return Attribute::get(function () {
            return $this->header_photo_path
                ? Storage::disk($this->profilePhotoDisk())->url($this->header_photo_path)
                : $this->defaultProfilePhotoUrl();
        });
    }

    /**
     * The `following` function establishes a many-to-many relationship between users using the
     * `relationships` table with `follower_id` and `following_id` as the foreign keys.
     *
     * @return The `following()` function is returning a many-to-many relationship between the current
     * User model and the User model, using the `relationships` table with `follower_id` and
     * `following_id` as the foreign keys.
     */
    public function following() {
        return $this->belongsToMany(User::class, 'relationships', 'follower_id', 'following_id');
    }

    /**
     * The `followers` function defines a many-to-many relationship between the current user and other
     * users through the `relationships` table.
     *
     * @return The `followers()` function is returning a many-to-many relationship between the current
     * User model and the User model, using the 'relationships' table with the foreign keys
     * 'following_id' and 'follower_id'. This relationship represents the users who are following the
     * current user.
     */
    public function followers() {
        return $this->belongsToMany(User::class, 'relationships', 'following_id', 'follower_id');
    }

    /**
     * The `sentPosts` function defines a relationship where a user has many posts that they have sent.
     *
     * @return The `sentPosts` function is returning a relationship where the current model has many
     * `Post` models with the foreign key `sender_id`.
     */
    public function sentPosts() {
        return $this->hasMany(Post::class, 'sender_id');
    }

    /**
     * The function `receivedPosts` defines a relationship where a user has many posts received.
     *
     * @return The `receivedPosts` function is returning a relationship where the current model has
     * many `Post` instances with the foreign key `recipient_id`.
     */
    public function receivedPosts() {
        return $this->hasMany(Post::class, 'recipient_id');
    }

    /**
     * The attachments function returns a collection of attachments associated with a post sender.
     *
     * @return The `attachments()` function is returning a relationship using the `hasManyThrough`
     * method in Laravel. This relationship allows you to retrieve attachments associated with a post
     * through a sender_id.
     */
    public function attachments() {
        return $this->hasManyThrough(Attachment::class, Post::class, 'sender_id');
    }

    /**
     * The function `likedPosts` returns the posts that the user has liked.
     *
     * @return The `likedPosts` function is returning a relationship where the current model belongs to
     * many `Post` models through the `reactions` table. It also includes a condition to only retrieve
     * posts where the pivot table's `type` column is set to `'like'`.
     */
    public function likedPosts() {
        return $this->belongsToMany(Post::class, 'reactions')
            ->where('reactions.type', 'like');
    }

    /**
     * The function `dislikedPosts` returns the posts that a user has disliked.
     *
     * @return The `dislikedPosts()` function is returning a relationship where the current model
     * belongs to many `Post` models through the `reactions` table, with an additional constraint that
     * the pivot table must have a `type` column with a value of `'dislike'`.
     */
    public function dislikedPosts() {
        return $this->belongsToMany(Post::class, 'reactions')
            ->where('reactions.type', 'dislike');
    }
}
