<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employeeroles extends Model
{
    use HasFactory;
    public $timestamps = false;  
    protected $primaryKey = 'employeeRole';
    public $incrementing = false;        
    protected $keyType = 'string';
    protected $fillable = [
        'employeeRole',
    ];
}
