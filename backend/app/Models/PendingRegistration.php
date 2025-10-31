<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingRegistration extends Model
{
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'password',
        'code',
        'expires_at',
        'phone'
    ];
    protected $dates = [
        'expires_at'
    ];
}
