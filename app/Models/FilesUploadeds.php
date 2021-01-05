<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilesUploadeds extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'band_id', 'storage', 'file_type', 'file_name', 'path', 'created_at', 'updated_at'
    ];

    public function band() {
        return $this->belongsTo(Band::class);
    }
}
