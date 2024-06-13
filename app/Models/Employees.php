<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    public $timestamps = false;   //VERY MINISCULE BUT TOOK ME HOURS TO FIGURE OUT
    protected $primaryKey = 'employeeNo';
    public $incrementing = true;             //THESE TOO
    protected $keyType = 'int';
    use HasFactory;
    protected $fillable = [
        'employeeNo',
        'employeeRole',
        'fname',
        'lname',
        'phoneNo',
        'salary',
        'img',
    ];
}
