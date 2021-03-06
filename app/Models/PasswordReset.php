<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email'       ,
        'token'       ,
    ];




}
