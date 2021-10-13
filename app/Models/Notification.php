<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;

/**
 * Класс уведомлений
 *
 * @property int $id
 * @property int $description
 * @property int $message_id
 *
 * @property Message $message
 *
 * Class Notification
 * @package App\Models
 */
class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'message_id',
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
     * @return BelongsToMany
     */
    public function message(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'message_id');
    }
}
