<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Housetypes extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;        
    protected $keyType = 'string';
    protected $fillable = [
        'houseType',
        'rentalNo',
        'price',
    ];
    
    //COMPOSITE PRIMARY KEY WORK-AROUND!
/**
     * Get the names of the primary key columns.
     *
     * @return array
     */
    public function getKeyName()
    {
        return ['houseType', 'rentalNo'];
    }

    /**
     * Override parent method to handle composite keys.
     *
     * @return array
     */
    protected function getKeyForSaveQuery()
    {
        $query = $this->newQueryWithoutScopes();

        foreach ($this->getKeyName() as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query;
    }

    /**
     * Override parent method to set the keys for a save update.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        foreach ($this->getKeyName() as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query;
    }
}
