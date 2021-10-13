<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckListOrder extends Model
{
    use HasFactory;
    protected $table = 'check_list_orders';

    protected $fillable = [
        'description',
        'order_id',
    ];

    protected $hidden = [];

    protected $casts = [];

    public $timestamps = true;

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
