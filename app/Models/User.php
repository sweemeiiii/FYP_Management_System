<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'name',
        'email',
        'password',
        'usertype',
        'department',
        'supervisor_id',
        'active_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
    }
    
    public function registration()
    {
        return $this->hasOne(\App\Models\Registration::class, 'student_id', 'id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'student_id');
    }

    public function getFyp($currentYear, $currentSemester)
    {
        $fyp1 = $this->registrations()->orderBy('created_at')->first();
        // Normalize semester into numeric
        $fyp1Sem = $fyp1->semester === 'Semester 1' ? 1 : 2;
        $currSem = $currentSemester === 'Semester 1' ? 1 : 2;

        // Convert to numeric value for easy comparison
        $fyp1Code = ((int)$fyp1->year * 10) + $fyp1Sem;
        $currCode = ((int)$currentYear * 10) + $currSem;

        if ($currCode === $fyp1Code) {
        return 'FYP1';
        } elseif ($currCode === $fyp1Code + 1) {
            return 'FYP2';
        } else {
            return 'Completed or Invalid';
        }
    }

}
