<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;

/**
 * Класс отзыва
 *
 * @property int $id
 * @property string $description
 * @property int $valueClient
 * @property int $valueOther
 * @property int $order_id
 * @property int $critic_user_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Order $orders
 * @property User $critic_users
 *
 * Class Review
 * @package App\Models
 */
class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'valueClient',
        'valueOther',
        'order_id',
        'critic_user_id',
    ];

    /**
     * Защищенные данные
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Отвечает за то, как данные преобразуются для записи бд
     *
     * @var array
     */
    protected $casts = [];

    public $timestamps = true;

    /**
     *
     * @return BelongsTo
     */
    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id','order_id');
    }

    /**
     *
     * @return BelongsToMany
     */
    public function criticUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users','critic_user_id');
    }
}
