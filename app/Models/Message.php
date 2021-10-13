<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;

/**
 * Класс сообщений
 *
 * @property int $id
 * @property string $description
 * @property bool $is_read
 * @property int $order_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Order $order
 *
 * @property Notification[] $notifications
 *
 * Class Message
 * @package App\Models
 */
class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'is_read',
        'order_id'
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
     * @return HasOne
     */
    public function notifications(): HasOne
    {
        return $this->hasOne(Notification::class);
    }

    /**
     *
     * @return BelongsToMany
     */
    public function topic(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_id');
    }
}
