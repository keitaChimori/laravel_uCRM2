<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'kana', 
        'tel', 
        'email', 
        'postcode', 
        'address', 
        'birthday', 
        'gender', 
        'memo'
    ];

    /**
     * 顧客一覧の検索機能
     */
    public function scopeSearchCustomers($query, $input = null)
    {
        if(!empty($input)){
            $data = Customer::where('name', 'LIKE', '%'.$input.'%')
            ->orWhere('kana', 'LIKE', '%'.$input.'%')
            // ->orWhere('tel', 'LIKE', '%'.$input.'%')
            ;
            if($data){
                return $query->where('name', 'LIKE', '%'.$input.'%')
                ->orWhere('kana', 'LIKE', '%'.$input.'%')
                // ->orWhere('tel', 'LIKE', '%'.$input.'%')
                ;
            }
        }
    }

    public function Purchases()
    {
        return $this->hasMany(Purchase::class);
    }

}
