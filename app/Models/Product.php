<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'status',
        'user_id',
        'type',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function scopeFilter($query, $filter)
    {
        if (isset($filter['name'])) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if (isset($filter['status'])) {
            $query->where('status', $filter['status']);
        }

        if (isset($filter['type'])) {
            $query->where('type', $filter['type']);
        }

        if (isset($filter['user_id'])) {
            $query->where('user_id', $filter['user_id']);
        }

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
