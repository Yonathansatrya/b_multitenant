<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role_id', 'status')
            ->withTimestamps();
    }

    public function invites()
    {
        return $this->hasMany(Invite::class);
    }

    public function organizationUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'organization_id');
    }

    public function items()
    {
        return $this->hasMany(Items::class);
    }

    public function typeItems()
    {
        return $this->hasMany(TypeItem::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function organization_loan()
    {
        return $this->hasOne(Loan::class, 'organization_loan');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function roomLoans()
    {
        return $this->hasMany(RoomLoans::class);
    }
}
