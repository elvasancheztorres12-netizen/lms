<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $primaryKey = 'enrollment_id';

    public $timestamps = false;

    protected $fillable = [
        'training_id',
        'user_id',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }
}