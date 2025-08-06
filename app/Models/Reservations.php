<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    public $timestamps = false;   //VERY MINISCULE BUT TOOK ME HOURS TO FIGURE OUT
    protected $primaryKey = 'reservationID'; 
    public $incrementing = true;             //THESE TOO
    protected $keyType = 'int';
    use HasFactory;
    protected $fillable = [
        'reservationID',
        'houseNo',
        'rentalNo',
        'clientName',
        'clientPhoneNo',
        'clientIDNo',
        'clientEmail',
        'status',
        'reservationDate',
        'timeRecorded',
        'notes',
    ];

    //The following is only for a functionality in the associated controller
    public function house()
    {
        return $this->belongsTo(Houses::class, 'houseNo', 'houseNo');
    }
}
