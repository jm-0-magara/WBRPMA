<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenditures extends Model
{
    use HasFactory;
    //removed timestamps
    protected $primaryKey = 'expenditureID';
    public $incrementing = false;        
    protected $keyType = 'int';
    protected $fillable = [
        'expenditureID',
        'expenditureType',
        'userID',
        'amount',
        'timeRecorded',
        'timePaid',
    ];
}
