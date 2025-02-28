<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'tenant_id',
    ];

    /**
     * Relasi ke organisasi (tenant).
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Organization::class, 'tenant_id');
    }
}   
