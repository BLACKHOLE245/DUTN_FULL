<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name_user',
        'email',
        'password',
        'phone',
        'address',
        'images_user',
        'role_id',
        'status',
        'is_banned'
    ];

  
    protected $hidden = [
        'password',
        'remember_token',
    ];

 
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_banned' => 'boolean',
    ];

 
    public function getImagesUserAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return null;
    }


    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }


    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('name_user', 'LIKE', "%{$keyword}%")
              ->orWhere('email', 'LIKE', "%{$keyword}%")
              ->orWhere('phone', 'LIKE', "%{$keyword}%");
        });
    }

 
    public function scopeByRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }


    public function scopeBanned($query, $isBanned = true)
    {
        return $query->where('is_banned', $isBanned);
    }


    public function isBanned()
    {
        return $this->is_banned == 1;
    }


    public function ban()
    {
        $this->update(['is_banned' => 1]);
    }

    public function unban()
    {
        $this->update(['is_banned' => 0]);
    }
}