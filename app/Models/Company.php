<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * A Company Can have many notes 
     */
    public function expenseNotes()
    {
        return $this->hasMany(ExpenseNote::class);
    }

}
