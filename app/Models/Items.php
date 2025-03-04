<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'item_type_id', 'stock', 'organization_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function typeItem()
    {
        return $this->belongsTo(TypeItem::class, 'item_type_id');
    }

    public function loans()
    {
        return $this->belongsToMany(Loan::class, 'loan_items')->withPivot('quantity');
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }
}
