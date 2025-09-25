<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contract extends Model
{
    protected $table = 'contracts';
    protected $fillable = [
        'id',
        'customer_id',
        'contract_details',
        'contract_amount',
    ];
}
