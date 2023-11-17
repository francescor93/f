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

    public function headerPhotoUrl(): Attribute {
        return Attribute::get(function () {
            return $this->header_photo_path
                ? Storage::disk($this->profilePhotoDisk())->url($this->header_photo_path)
                : $this->defaultProfilePhotoUrl();
        });
    }

    public function following() {
        return $this->belongsToMany(User::class, 'relationships', 'follower_id', 'following_id');
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'relationships', 'following_id', 'follower_id');
    }

    public function sentPosts() {
        return $this->hasMany(Post::class, 'sender_id');
    }

    public function receivedPosts() {
        return $this->hasMany(Post::class, 'recipient_id');
    }

    public function attachments() {
        return $this->hasManyThrough(Attachment::class, Post::class, 'sender_id');
    }
}
