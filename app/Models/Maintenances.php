<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenances extends Model
{
     public $timestamps = false;   //VERY MINISCULE BUT TOOK ME HOURS TO FIGURE OUT
    protected $primaryKey = 'maintenanceNo'; 
    public $incrementing = true;             //THESE TOO
    protected $keyType = 'int';
    use HasFactory;
    protected $fillable = [
        'maintenanceNo',
        'houseNo',
        'rentalNo',
        'maintenanceDate',
        'amount',
        'maintenanceDescription',
    ];
}
