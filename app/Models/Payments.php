<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;
    //removed timestamps
    protected $primaryKey = 'paymentID';
    public $timestamps = false;
    public $incrementing = false;        
    protected $keyType = 'int';
    protected $fillable = [
        'paymentID',
        'houseno',
        'rentalNo',
        'paymenttype',
        'amount',
        'timeRecorded',
        'timePaid',
        'paymentMethod',
        'narration',
    ];
}
