<?php

namespace App\Models;
use App\Models\Contact;
use App\Models\Message;
use App\Models\UserGhost;
use App\Models\Conversation;
use App\Models\UserAttendance;
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
        'notification_status'
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
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



    protected static function booted()
    {
        // Event for decrypting the content after a Post is retrieved
        static::created(function ($user) {
            $user->notify(new CustomVerifyEmail);
        });
    }


    public function contacts()
    {
        return $this->hasMany(Contact::class,'user_id','id');
    }
    public function ghost()
    {
        return $this->hasOne(UserGhost::class, 'user_id', 'id');
    }

    // ================================= ratings restaurant ============================================

    public function restaurantRatings()
    {
        return $this->hasMany(RestaurantRating::class,'user_id','id');
    }

    // ================================= message ============================================

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function sentMessagesFromConversation($conversationId)
    {
        return $this->hasMany(Message::class, 'sender_id')
            ->where('conversation_id', $conversationId)
            ->latest('created_at');
    }

    public function receivedMessagesFromConversation($conversationId)
    {
        return $this->hasMany(Message::class, 'receiver_id')
            ->where('conversation_id', $conversationId)
            ->latest('created_at');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id','id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user');
    }

    public function canAccessConversation($conversationId)
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


    public function canAccessRestaurant($restaurantId)
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
    public function canAccessRoom($roomId)
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
    public function canAccessGame($gameId)
    {
        if (!auth('api')->check()) {
            return false;
        }
        // Check if the user is make room
        $game = Room::find($gameId);
        if (!$game) {
            return false;
        }
        // Check if the user is either the sender or receiver of the game
        return $this->id == $game->player_x_id || $this->id == $game->player_o_id;
    }


}
