<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';

    protected $fillable = [
        'title',
        'description',
    ];

    protected $hidden = [];

    protected $casts = [];

    public $timestamps = true;

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
