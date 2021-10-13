<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

/**
 * Класс отделов
 *
 * @property int $id
 * @property string $name
 * @property int $code
 *
 * @property User[] $users
 *
 * Class Department
 * @package App\Models
 */
class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
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
