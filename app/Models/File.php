<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['file_name', 'file_path', 'file_type'];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
