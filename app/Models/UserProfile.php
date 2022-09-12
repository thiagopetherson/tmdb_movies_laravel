<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname',
        'alias',
        'gender',
        'birth_date',
        'telephone',
        'profession',
        'profile_phrase',
        'biografy',
        'avatar',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
