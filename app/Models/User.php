<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelLike\Traits\Liker;
use Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use Liker;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'location',
        'hobbies',
        'profile_picture',
        'languages_teach',
        'languages_learn',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hobbies' => 'array',
            'languages_teach' => 'array',
            'languages_learn' => 'array',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(related: Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(related: Comment::class);
    }

    /* Connections sent by this user (any status). */
    public function sentConnections(): HasMany
    {
        return $this->hasMany(Connection::class, 'sender_id');
    }

    /* Connections received by this user (any status). */
    public function receivedConnections(): HasMany
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    /* Users this user is following (accepted only). */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'connections', 'sender_id', 'receiver_id')
            ->wherePivot('status', 'accepted');
    }

    /* Users following this user (accepted only). */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'connections', 'receiver_id', 'sender_id')
            ->wherePivot('status', 'accepted');
    }

    /* Check if this user follows the given user. */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('users.id', $user->id)->exists();
    }

    /* Check if this user is followed by the given user. */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('users.id', $user->id)->exists();
    }

    /* Follow the given user (auto-accept for public profiles). */
    public function follow(User $user): void
    {
        if ($this->id === $user->id) {
            throw new \Exception('You cannot follow yourself.');
        }

        $this->sentConnections()->updateOrCreate(
            ['receiver_id' => $user->id],
            ['status' => 'accepted']
        );

        // Create follow notification
        if (! $user->getNotifications()
            ->where('sender_id', $this->id)
            ->where('type', 'follow')
            ->exists()) {

            $user->getNotifications()->create([
                'sender_id' => $this->id,
                'type' => 'follow',
            ]);
        }
    }

    /* Unfollow the given user (removes any connection). */
    public function unfollow(User $user): void
    {
        $this->sentConnections()->where('receiver_id', $user->id)->delete();
    }

    /* Get all connections (users this user is following + users following this user). */
    public function getConnections(): mixed
    {
        $followingIds = $this->following()->pluck('users.id')->toArray();
        $followerIds = $this->followers()->pluck('users.id')->toArray();
        $allIds = array_unique(array_merge($followingIds, $followerIds));

        return User::whereIn('id', $allIds)->get();
    }

    /**
     * Get the profile picture URL or default avatar
     */
    public function getProfilePictureUrl(): ?string
    {
        if ($this->profile_picture && \Storage::disk('public')->exists($this->profile_picture)) {
            return \Storage::url($this->profile_picture);
        }

        // Return default avatar
        return $this->getDefaultAvatarUrl();
    }

    /**
     * Get default avatar URL
     */
    public function getDefaultAvatarUrl(): ?string
    {
        return null;
    }

    /**
     * Get the flag picture URL or default flag picture
     */
    public function getFlagPictureUrl(): string
    {
        $location = $this->location ? strtolower($this->location) : null;

        if ($location) {
            return "https://cdn.jsdelivr.net/gh/lipis/flag-icons/flags/4x3/{$location}.svg";
        }

        // Return default flag
        return $this->getDefaultFlagUrl();
    }

    /**
     * Get default flag URL
     */
    public function getDefaultFlagUrl(): string
    {
        return 'https://placehold.co/120x120?text=??';
    }

    /**
     *  Does what it says
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    /**
     * Get all conversations this user is part of
     */
    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withTimestamps()
            ->withPivot(['last_read_at', 'joined_at', 'role'])
            ->orderByDesc('conversations.updated_at');
    }

    /**
     * Get conversations created by this user
     */
    public function createdConversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'creator_id');
    }

    /**
     * booted
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->public_id = Str::random(12);
        });
    }

    /**
     * Default id for routing
     */
    public function getRouteKeyName()
    {
        return 'public_id';
    }
}
