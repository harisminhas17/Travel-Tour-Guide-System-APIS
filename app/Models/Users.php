<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model {
    // Specify the table if it's not the plural form of the model name
    protected $table = 'users';

    // Allow mass assignment for the following attributes
    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

    // Optionally, you can add hidden attributes to not include them in arrays or JSON
    protected $hidden = [
        'password',
        // other sensitive fields
    ];
}
