<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Класс кабинетов
 *
 * @property int $id
 * @property int $number
 * @property string $description
 *
 * @property User[] $users
 *
 * Class Room
 * @package App\Models
 */
class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'description',
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
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
