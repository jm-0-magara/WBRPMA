<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deletedtenants extends Model
{
    public $timestamps = false;   //VERY MINISCULE BUT TOOK ME HOURS TO FIGURE OUT
    protected $primaryKey = 'houseNo';
    public $incrementing = false;             //THESE TOO
    protected $keyType = 'string';
    use HasFactory;
    protected $fillable = [
        'deletedTenantNo',
        'houseNo',
        'rentalNo',
        'names',
        'phoneNo',
        'debt',
        'dateDeleted',
    ];
}
