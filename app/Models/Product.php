<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'price' => 'float'
    ];

    public function scopeFilter($query, $filter)
    {
        if (isset($filter['name']) && $filter['name']) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if (isset($filter['user_id']) && $filter['user_id']) {
            $query->where('user_id', $filter['user_id']);
        }

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
