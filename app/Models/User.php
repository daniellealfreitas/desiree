<?php

namespace App\Models;
use App\Models\Like;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'level', 'profile_photo_path', 'cover_photo_path'
    ];

    // Relação com posts
    public function posts() {
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

    // Relação com pontos (pontuação do usuário)
    public function points(): HasOne {
        return $this->hasOne(UserPoint::class);
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
