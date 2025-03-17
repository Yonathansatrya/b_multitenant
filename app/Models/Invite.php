<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'invite_code',
        'expires_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invite) {
            $invite->invite_code = Str::random(8);
            $invite->expires_at = now()->addDays(7);
        });
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'invite_user')->withTimestamps();
    }
}
