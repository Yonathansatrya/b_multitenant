<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_code',
        'room_name',
        'room_description',
        'status',
        'organization_id',
    ];

    public function roomLoans()
    {
        return $this->belongsToMany(RoomLoans::class, 'room_loan_details')
            ->withPivot('note')
            ->withTimestamps();
    }

    public function roomLoanDetails()
    {
        return $this->hasMany(RoomLoanDetail::class, 'room_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
