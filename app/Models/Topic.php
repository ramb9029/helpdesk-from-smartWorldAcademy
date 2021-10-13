<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

/**
 * Класс категорий
 *
 * @property int $id
 * @property string $name
 * @property int $code
 *
 * @property Order[] $orders
 *
 * Class Topic
 * @package App\Models
 */
class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';

    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_topics', 'topic_id', 'order_id');
    }
}
