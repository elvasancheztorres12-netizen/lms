<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $primaryKey = 'enrollment_id';

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
}