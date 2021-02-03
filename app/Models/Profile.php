<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'full_name', 'phone_number', 'province_code', 'city_code', 'gender', 'is_smoker', 'created_at', 'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function province(){
        return $this->belongsTo(Region::class,'province_code','code');
    }
    public function city(){
        return $this->belongsTo(Region::class,'city_code','code');
    }
}
