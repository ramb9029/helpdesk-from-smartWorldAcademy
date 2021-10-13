<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

/**
 * Класс должностей
 *
 * @property int $id
 * @property string $name
 * @property int $code
 *
 * @property User[] $users
 *
 * Class Position
 * @package App\Models
 */
class Position extends Model
{
    use HasFactory;
    protected $table = 'positions';

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
