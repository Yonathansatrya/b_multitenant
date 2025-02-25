<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

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
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Organization::class, 'tenant_id');
    }
}
