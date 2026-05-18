<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    protected $table = 'user_roles';
    protected $fillable = ['user_id', 'role'];
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
