<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Role extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    
    protected $primaryKey ='id';

    protected $keyType = 'int';

    protected$fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function IsAdmin()
    {
        return $this->role === 'admin';
    }

    public function IsStaff()
    {
        return $this->role === 'staff';
    }

    public function IsCustomer()
    {
        return $this->role === 'customer';
    }
}
