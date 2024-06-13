<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rentals extends Model
{
    public $timestamps = false;   //VERY MINISCULE BUT TOOK ME HOURS TO FIGURE OUT
    protected $primaryKey = 'rentalNo';
    public $incrementing = true;             //THESE TOO
    protected $keyType = 'int';
    use HasFactory;
    protected $fillable = [
        'rentalNo',
        'rentalName',
        'user_id',
        'rentalImage',
        'description',
    ];
}
