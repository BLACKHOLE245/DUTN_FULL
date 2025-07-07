<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'comment_content',
    ];

    const STATUS_SHOW = 0;
    const STATUS_HIDE = 1;
    const STATUS_PENDING = 2;

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_SHOW => 'Hiện thị',
            self::STATUS_HIDE => 'Ẩn',
            self::STATUS_PENDING => 'Chờ duyệt',
            default => 'Không xác định'
        };
    }

    public static function getStatusOptions()
    {
        return [
            self::STATUS_SHOW => 'Hiện thị',
            self::STATUS_HIDE => 'Ẩn',
            self::STATUS_PENDING => 'Chờ duyệt',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeVisible($query)
    {
        return $query->where('status', self::STATUS_SHOW);
    }

    public function scopeHidden($query)
    {
        return $query->where('status', self::STATUS_HIDE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isVisible()
    {
        return $this->status === self::STATUS_SHOW;
    }

    public function isHidden()
    {
        return $this->status === self::STATUS_HIDE;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function show()
    {
        $this->update(['status' => self::STATUS_SHOW]);
    }

    public function hide()
    {
        $this->update(['status' => self::STATUS_HIDE]);
    }

    public function setPending()
    {
        $this->update(['status' => self::STATUS_PENDING]);
    }
}