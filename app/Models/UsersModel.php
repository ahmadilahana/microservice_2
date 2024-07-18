<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;
use Laravel\Passport\HasApiTokens;

class UsersModel extends Authenticable
{
    use HasFactory, HasApiTokens;
    
    protected $table = "users";

    protected $fillable = ["name", "email", "password"];

    protected $hidden = ["password"];

    public $timestamps = true;
}
