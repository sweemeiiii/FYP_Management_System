<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'document_path',
        'sent_at',
    ];

    protected $dates = ['sent_at'];
    
    protected $casts = [
    'sent_at' => 'datetime',
];
}



