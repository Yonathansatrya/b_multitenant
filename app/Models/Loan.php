<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_loan',
        'loan_date',
        'loan_end_date',
        'description',
        'status',
        'organization_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function items()
    {
        return $this->belongsToMany(Items::class, 'loan_items')->withPivot('quantity');
    }
    public function organizationLoan()
    {
        return $this->belongsTo(Organization::class, 'organization_loan');
    }

}
