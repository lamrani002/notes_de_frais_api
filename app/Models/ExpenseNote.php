<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'note_date',
        'amount',
        'type'
    ];

    /**
     * Relationship => Note belongs to one and only one User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship => Note belongs to one and only one Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
