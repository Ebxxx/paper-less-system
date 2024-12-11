<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Superadmin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'superadmin';

    protected $fillable = [
        'username', 
        'fullname', 
        'email', 
        'password', 
        'is_active',
        'maintenance_mode'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'maintenance_mode' => 'boolean'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function toggleMaintenanceMode()
    {
        $this->maintenance_mode = !$this->maintenance_mode;
        $this->save();
        return $this->maintenance_mode;
    }
}