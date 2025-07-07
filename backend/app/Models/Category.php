<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function scopeActive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeHidden($query)
    {
        return $query->where('status', 1);
    }

    protected $appends = ['status_text'];

    public function getStatusTextAttribute()
    {
        return $this->status === 0 ? 'Hiển thị' : 'Ẩn';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
