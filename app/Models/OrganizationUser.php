<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationUser extends Model
{
    use HasFactory;

    protected $table = 'organization_user';
    protected $fillable = ['organization_id', 'user_id', 'role_id', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($organizationUser) {
            if ($organizationUser->role_id) {
                $user = $organizationUser->user;
                $role = Role::find($organizationUser->role_id);

                if ($user && $role) {
                    $user->syncRoles([$role->name]);
                }
            }
        });
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
