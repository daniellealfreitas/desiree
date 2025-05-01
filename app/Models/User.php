<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Conto;
use App\Models\City;
use App\Models\State;
use App\Models\Like;
use App\Models\UserLevel;
use App\Models\UserPoint;
use App\Models\Notification;
use App\Models\FollowRequest;
use App\Models\LookingForOption;
use App\Models\PreferenceOption;
use App\Models\UserPhoto;
use App\Models\UserCoverPhoto;
use App\Models\Post;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    protected $fillable = [
        'name', 
        'username', 
        'email', 
        'password',
        'city_id', 
        'state_id', 
        'latitude', 
        'longitude',
        'sexo',
        'aniversario',
        'privado',
        'bio'
    ];

    // Relação com posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Relação com likes
    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_user_likes')->withTimestamps();
    }

    public function contos()
    {
        return $this->hasMany(Conto::class);
    }

  
    // Método para atualizar o nível do usuário com base nos pontos
    public function updateLevel() {
        $points = $this->points ? $this->points->points : 0;
        $levelData = UserLevel::where('min_points', '<=', $points)
            ->orderBy('level', 'desc')
            ->first();

        if ($levelData && $this->level !== $levelData->level) {
            $this->level = $levelData->level;
            $this->save();
        }
    }

    // Relacionamento com "looking_for" via pivot
    public function lookingFor() {
        return $this->belongsToMany(LookingForOption::class, 'user_looking_for', 'user_id', 'looking_for_option_id');
    }

    // Relacionamento com "preferences" via pivot
    public function preferences() {
        return $this->belongsToMany(PreferenceOption::class, 'user_preferences', 'user_id', 'preference_option_id');
    }

    // Relacionamento com fotos
    public function photos()
    {
        return $this->hasMany(UserPhoto::class);
    }

    public function userPhotos()
    {
        return $this->hasMany(UserPhoto::class);
    }

    public function userPhoto()
    {
        return $this->hasOne(UserPhoto::class);
    }

    public function userCoverPhotos()
    {
        return $this->hasMany(UserCoverPhoto::class);
    }

    /**
     * Usuários que seguem este usuário
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Usuários que este usuário segue
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    /**
     * Check if the user is following another user
     */
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Relacionamento com notificações
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relacionamento com solicitações de seguir
     */
    public function followRequests()
    {
        return $this->hasMany(FollowRequest::class, 'receiver_id');
    }

    public function sentFollowRequests()
    {
        return $this->hasMany(FollowRequest::class, 'sender_id');
    }

    public function hasPendingFollowRequestFrom(User $user)
    {
        return $this->followRequests()
            ->where('sender_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Increment ranking points
     */
    public function incrementRankingPoints($points)
    {
        $this->ranking_points += $points;
        $this->save();
    }

    /**
     * Relacionamento com pontos do usuário
     */
    public function userPoints()
    {
        return $this->hasMany(UserPoint::class);
    }

    public function hobbies(): BelongsToMany
    {
        return $this->belongsToMany(Hobby::class);
    }

    public function procuras(): BelongsToMany
    {
        return $this->belongsToMany(Procura::class);
    }

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
            'aniversario' => 'date',
            'privado' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }
}
