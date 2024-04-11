<?php

namespace App\Models;
use App\Models\XOGame;
use App\Models\Contact;
use App\Models\Message;
use App\Models\UserGhost;
use App\Models\Conversation;
use App\Models\UserAttendance;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'photo',
        'phone',
        'ghost_mood',
        'device_token',
        'notification_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'phone' => 'string'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }



    protected static function booted(): void
    {
        // Event for decrypting the content after a Post is retrieved
        static::created(function ($user) {
            $user->notify(new CustomVerifyEmail);
        });
    }


    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class,'user_id','id');
    }
    public function ghost(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserGhost::class, 'user_id', 'id');
    }

    // ================================= ratings restaurant ============================================

    public function restaurantRatings(): HasMany
    {
        return $this->hasMany(RestaurantRating::class,'user_id','id');
    }

    // ================================= message ============================================

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function sentMessagesFromConversation($conversationId): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id')
            ->where('conversation_id', $conversationId)
            ->latest('created_at');
    }

    public function receivedMessagesFromConversation($conversationId): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id')
            ->where('conversation_id', $conversationId)
            ->latest('created_at');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'sender_id','id');
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'notification_user');
    }

//    ================================== otp ============================================
    public function otp(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OtpUser::class);
    }

    /**
     * Check if the user has an OTP and if it is expired
     *
     * @return bool
     */
    public function isOtpExpiry(): bool
    {
        if($this->otp && $this->otp->isExpired()){
            return true;
        }
        return false;
    }

//    ================================== user attendance ============================================

    public function userAttendancePublicPlace(): HasMany
    {
        return $this->hasMany(UserAttendance::class)->where('public_place_id', '!=', null);
    }



    /**
     * Check if the user can access the conversation
     *
     * @param int $conversationId
     * @return bool
     */
    public function canAccessConversation($conversationId): bool
    {
        // Check if the user is authenticated
        if (!auth('api')->check()) {
            return false;
        }
        // Check if the user is part of the conversation
        $conversation = Conversation::find($conversationId);
        if (!$conversation) {
            return false;
        }
        // Check if the user is either the sender or receiver of the conversation
        return $this->id == $conversation->sender_id || $this->id == $conversation->receiver_id;
    }


    /**
     * Check if the user can access the restaurant
     *
     * @param int $restaurantId
     * @return bool
     */
    public function canAccessRestaurant($restaurantId): bool
    {
        if (!auth('api')->check()) {
            return false;
        }
        // Check if the user is make reservation
        $reservation = UserAttendance::where('user_id', $this->id)->where('restaurant_id',$restaurantId)
            ->where('created_at', '>=', now()->subHour())
            ->first();
        if (!$reservation) {
            return false;
        }
        return true;
    }

    /**
     * Check if the user can access the public place
     *
     * @param int $placeId
     * @return bool
     */
    public function canAccessPlace(int $placeId): bool
    {
        if (!auth('api')->check()) {
            return false;
        }
        // Check if the user is make reservation
        $reservation = UserAttendance::where('user_id', $this->id)->where('public_place_id',$placeId)
            ->where('created_at', '>=', now())
            ->first();
        if (!$reservation) {
            return false;
        }
        return true;
    }




    /**
     * Check if the user can access the room
     *
     * @param int $roomId
     * @return bool
     */
    public function canAccessRoom(int $roomId): bool
    {
        if (!auth('api')->check()) {
            return false;
        }
        // Check if the user is make room
        $room = Room::find($roomId);
        if (!$room) {
            return false;
        }
        // Check if the user is either the sender or receiver of the room
        return $this->id == $room->sender_id || $this->id == $room->receiver_id;
    }


    /**
     * Check if the user can access the game
     *
     * @param int $gameId
     * @return bool
     */
    public function canAccessGame(int $gameId): bool
    {
        if (!auth('api')->check()) {
            return false;
        }
        // Check if the user is make room
        $game = XOGame::find($gameId);
        if (!$game) {
            return false;
        }
        // Check if the user is either the sender or receiver of the game
        return $this->id == $game->player_x_id || $this->id == $game->player_o_id;
    }


}
