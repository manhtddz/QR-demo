<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'id',
        'name',
    ];
}
