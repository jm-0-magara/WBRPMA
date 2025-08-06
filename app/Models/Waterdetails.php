<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waterdetails extends Model
{
    public $timestamps = false;   //VERY MINISCULE BUT TOOK ME HOURS TO FIGURE OUT
    protected $primaryKey = 'waterConsumedID'; 
    public $incrementing = true;             //THESE TOO
    protected $keyType = 'int';
    use HasFactory;
    protected $fillable = [
        'waterConsumedID',
        'houseNo',
        'rentalNo',
        'unitsConsumed',
        'date',
        'notes',
    ];
}
