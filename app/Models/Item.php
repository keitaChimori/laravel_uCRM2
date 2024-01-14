<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'memo',
        'price',
        'is_selling',
    ];

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withPivot('quantity');
    }

    public function scopeIsSelling($query, $input = null)
    {
        if($input) {
            return $query->where('is_selling', true);
        }
    }
}
