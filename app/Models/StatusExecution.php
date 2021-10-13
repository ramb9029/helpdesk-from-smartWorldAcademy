<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

/**
 * Класс статус исполнения
 *
 * @property int $id
 * @property string $name
 * @property string $description
 *
 * @property Order[] $orders
 *
 * Class StatusExecution
 * @package App\Models
 */
class StatusExecution extends Model
{
    use HasFactory;

    protected $table = 'statuses_execution';

    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Защищенные данные
     *
     * @var array
     */
    protected $hidden = [
        'description',
    ];

    /**
     * Отвечает за то, как данные преобразуются для записи бд
     *
     * @var array
     */
    protected $casts = [];

    public $timestamps = true;

    /**
     *
     * @return HasMany
     */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'statusExecution_id', 'id');
    }
}
