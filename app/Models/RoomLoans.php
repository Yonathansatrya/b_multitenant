<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomLoans extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_code',
        'loan_name',
        'description',
        'start_date',
        'end_date',
        'loan_status',
        'organization_id',
    ];

    public function roomLoanDetails()
    {
        return $this->hasMany(RoomLoanDetail::class, 'room_loan_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_loan_details')
            ->withPivot('note')
            ->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
