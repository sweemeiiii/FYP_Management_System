<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'file_path',
        'submitted_at',
        'is_late',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
