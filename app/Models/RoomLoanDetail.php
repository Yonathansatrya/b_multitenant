<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomLoanDetail extends Model
{
    use HasFactory;
    protected $table = 'room_loan_details';
    protected $fillable = [
        'room_loan_id',
        'room_id',
        'note',
    ];

    public function roomLoan()
    {
        return $this->belongsTo(RoomLoans::class, 'room_loan_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
