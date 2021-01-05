<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    use HasFactory;

    public function city(){
        return $this->belongsTo(Region::class,'city_code','code');
    }

    public function filesUploadeds(){
        return $this->hasMany(FilesUploadeds::class);
    }
}
