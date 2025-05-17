<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use App\Models\Conto;
use App\Models\City;
use App\Models\State;
use App\Models\Like;
use App\Models\UserLevel;
use App\Models\UserPoint;
use App\Models\Notification;
use App\Models\FollowRequest;
// use App\Models\LookingForOption;
// use App\Models\PreferenceOption;
use App\Models\UserPhoto;
use App\Models\UserCoverPhoto;
use App\Models\Post;
use App\Models\Payment;
use App\Models\Hobby;
use App\Models\Procura;
use App\Models\Product;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\Event;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{

    use HasFactory, Notifiable, Billable;


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
        'bio',
        'role', // Adicionado role
        'active', // Adicionado active
        'last_seen',
        'status'
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

    // Relacionamento com "looking_for" via pivot - Temporarily commented out until model is created
    // public function lookingFor() {
    //     return $this->belongsToMany(LookingForOption::class, 'user_looking_for', 'user_id', 'looking_for_option_id');
    // }

    // Relacionamento com "preferences" via pivot - Temporarily commented out until model is created
    // public function preferences() {
    //     return $this->belongsToMany(PreferenceOption::class, 'user_preferences', 'user_id', 'preference_option_id');
    // }

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

    public function hobbies()
    {
       return $this->belongsToMany(Hobby::class, 'hobby_user');
    }

    public function procuras()
    {
        return $this->belongsToMany(Procura::class, 'procura_user');
    }

    /**
     * Produtos na wishlist do usuário (many-to-many).
     */
    public function wishlistedProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    /**
     * Mensagens enviadas pelo usuário
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Mensagens recebidas pelo usuário
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Retorna o número de mensagens não lidas
     */
    public function unreadMessagesCount()
    {
        return $this->receivedMessages()->whereNull('read_at')->count();
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
            'role' => 'string', // Adicionado cast para role
            'active' => 'boolean', // Adicionado cast para active
            'last_seen' => 'datetime',
            'status' => 'string',
        ];
    }

    /**
     * Accessor inteligente de status do usuário
     * Determina o status real do usuário com base em seu status definido e última atividade
     */
    public function getPresenceStatusAttribute()
    {
        // Se o status for definido manualmente como "dnd" (não perturbe), respeite isso
        if ($this->status === 'dnd') {
            return 'dnd';
        }

        // Se o status for definido manualmente como "away", respeite isso
        if ($this->status === 'away') {
            return 'away';
        }

        // Se o status for definido como "offline", respeite isso
        if ($this->status === 'offline') {
            return 'offline';
        }

        // Se o usuário estiver online mas não tiver atividade recente, considere-o offline
        if (!$this->last_seen) {
            return 'offline';
        }

        // Verifica se o usuário esteve ativo nos últimos 5 minutos
        if ($this->last_seen->diffInMinutes(now()) < 5) {
            return 'online';
        }

        // Se o usuário esteve ativo nos últimos 15 minutos, considere-o away
        if ($this->last_seen->diffInMinutes(now()) < 15) {
            return 'away';
        }

        // Caso contrário, considere-o offline
        return 'offline';
    }

    /**
     * Verifica se o usuário está em modo "não perturbe"
     */
    public function isDoNotDisturb()
    {
        return $this->status === 'dnd';
    }

    /**
     * Relacionamento com estatísticas de tempo online
     */
    public function onlineStats()
    {
        return $this->hasMany(UserOnlineStat::class);
    }

    /**
     * Obter estatísticas de tempo online para hoje
     */
    public function getTodayOnlineStatsAttribute()
    {
        return UserOnlineStat::getOrCreateForToday($this->id);
    }

    /**
     * Obter estatísticas de tempo online para a semana atual
     */
    public function getCurrentWeekOnlineStatsAttribute()
    {
        return UserOnlineStat::getCurrentWeekStats($this->id);
    }

    /**
     * Obter estatísticas de tempo online para o mês atual
     */
    public function getCurrentMonthOnlineStatsAttribute()
    {
        return UserOnlineStat::getCurrentMonthStats($this->id);
    }

    /**
     * Verifica se o usuário possui determinado papel
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Grupos que o usuário criou
     */
    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'creator_id');
    }

    /**
     * Grupos dos quais o usuário é membro
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
                    ->withPivot('role', 'is_approved', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Grupos dos quais o usuário é administrador
     */
    public function adminGroups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
                    ->wherePivot('role', 'admin')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Grupos dos quais o usuário é moderador
     */
    public function moderatedGroups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
                    ->wherePivot('role', 'moderator')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Convites de grupo recebidos pelo usuário
     */
    public function groupInvitations()
    {
        return $this->hasMany(GroupInvitation::class, 'user_id');
    }

    /**
     * Convites de grupo enviados pelo usuário
     */
    public function sentGroupInvitations()
    {
        return $this->hasMany(GroupInvitation::class, 'invited_by');
    }

    /**
     * Verifica se o usuário é membro de um grupo
     */
    public function isMemberOf(Group $group)
    {
        return $this->groups()
                    ->where('group_id', $group->id)
                    ->where('is_approved', true)
                    ->exists();
    }

    /**
     * Verifica se o usuário é administrador de um grupo
     */
    public function isAdminOf(Group $group)
    {
        return $this->adminGroups()
                    ->where('group_id', $group->id)
                    ->exists();
    }

    /**
     * Verifica se o usuário é moderador de um grupo
     */
    public function isModeratorOf(Group $group)
    {
        return $this->moderatedGroups()
                    ->where('group_id', $group->id)
                    ->exists();
    }

    /**
     * Verifica se o usuário pode gerenciar um grupo
     */
    public function canManageGroup(Group $group)
    {
        return $this->isAdminOf($group) || $this->isModeratorOf($group);
    }

    /**
     * Eventos criados pelo usuário
     */
    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Eventos que o usuário está participando
     */
    public function attendingEvents()
    {
        return $this->belongsToMany(Event::class, 'event_attendees')
                    ->withPivot('status', 'ticket_code', 'payment_status', 'payment_method', 'payment_id', 'amount_paid', 'paid_at', 'checked_in_at')
                    ->withTimestamps();
    }

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Matches que o usuário iniciou (deu like ou pass)
     */
    public function initiatedMatches()
    {
        return $this->hasMany(UserMatch::class, 'user_id');
    }

    /**
     * Matches em que o usuário é o alvo
     */
    public function receivedMatches()
    {
        return $this->hasMany(UserMatch::class, 'target_user_id');
    }

    /**
     * Usuários que deram match com este usuário
     */
    public function matchedUsers()
    {
        return $this->belongsToMany(User::class, 'user_matches', 'user_id', 'target_user_id')
                    ->wherePivot('matched', true)
                    ->withPivot('matched_at')
                    ->withTimestamps();
    }

    /**
     * Verifica se o usuário deu match com outro usuário
     */
    public function hasMatchWith(User $user)
    {
        return $this->initiatedMatches()
                    ->where('target_user_id', $user->id)
                    ->where('matched', true)
                    ->exists();
    }

    /**
     * Get the user's initials
     */
    public function initials()
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Pedidos do usuário
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's wallet
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get the user's wallet transactions
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get or create the user's wallet
     */
    public function getWalletAttribute()
    {
        // First try to find the existing wallet
        $wallet = $this->wallet()->first();

        if ($wallet) {
            // Existing wallet found, return it
            return $wallet;
        } else {
            // No wallet found, create a new one with default values
            logger()->info('Creating new wallet for user', [
                'user_id' => $this->id,
                'initial_balance' => 0.00
            ]);

            return $this->wallet()->create([
                'balance' => 0.00,
                'active' => true,
            ]);
        }
    }

    /**
     * Get the user's wallet balance formatted
     */
    public function getFormattedWalletBalanceAttribute()
    {
        return 'R$ ' . number_format($this->wallet->balance, 2, ',', '.');
    }
}
