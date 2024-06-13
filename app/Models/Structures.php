<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structures extends Model
{
    use HasFactory;
    public $timestamps = false;  
    protected $primaryKey = 'structureName';
    public $incrementing = false;        
    protected $keyType = 'string';
    protected $fillable = [
        'structureName',
        'structureType',
        'rentalNo',
    ];
}
