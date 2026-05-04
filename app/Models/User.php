<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'person_id',
        'username',
        'password',
        'status'
    ];

    protected $hidden = [
        'password'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'person_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'teacher_id', 'user_id');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}