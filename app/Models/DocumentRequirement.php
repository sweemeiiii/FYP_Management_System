<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $title
 * @property string $due_date
 */

class DocumentRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];
    

    // Accessor for nicely formatted due date
    public function getFormattedDueDateAttribute()
    {
        return optional($this->due_date)->format('d M Y');
    }
}
