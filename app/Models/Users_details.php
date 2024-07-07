<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Users_details extends Model
{
    use HasFactory;

    protected $fillable = ['id','name','email','password','role'];

}
