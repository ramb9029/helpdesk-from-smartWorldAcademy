<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;

/**
 * Класс юзеров
 *
 * @property int $id
 * @property string $lastName
 * @property string $firstName
 * @property string $middleName
 * @property string $role
 * @property string $email
 * @property string $password
 * @property int $department_id
 * @property int $position_id
 * @property int $room_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Review[] $reviews
 * @property Order[] $orders
 *
 * @property Department $department
 * @property Position $post
 * @property Room $room
 *
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    const USER_ROLE_ID = 4;
    const MODERATOR_ROLE_ID = 3;
    const ADMINISTRATOR_ROLE_ID = 2;
    const ARCHIVED_ROLE_ID = 1;

    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'lastName',
        'firstName',
        'middleName',
        'role',
        'email',
        'password',
        'department_id',
        'position_id',
        'room_id',

    ];

    /**
     * Защищенные данные
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

    public $timestamps = true;


   public function department():BelongsTo
   {
       return $this->belongsTo(Department::class);
   }

    public function position():BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function room():BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function executorOrders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_executor', 'user_id', 'order_id');
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     *
     * @return HasOne
     */
    public function reviews(): HasOne
    {
        return $this->hasOne(Review::class, 'reviewable');
    }

    /**
     *
     * @return HasMany
     */
    public function clientOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'client_user_id');
    }

    /**
     *
     * @return BelongsTo
     */

}
