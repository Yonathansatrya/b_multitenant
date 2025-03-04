<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'organization_id',
    ];

    public function items()
    {
        return $this->hasMany(Items::class, 'item_type_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
