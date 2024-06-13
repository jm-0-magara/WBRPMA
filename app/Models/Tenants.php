<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    use HasFactory;
    protected $fillable = [
        'tenantNo',
        'houseNo',
        'rentalNo',
        'names',
        'phoneNo',
        'IDNO',
        'email',
        'dateAdded',
    ];
}
