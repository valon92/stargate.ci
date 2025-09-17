<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingHistory extends Model
{
    protected $table = 'billing_history';
    
    protected $fillable = [
        'user_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'currency',
        'status',
        'type',
        'description',
        'due_date',
        'paid_date',
        'line_items',
        'taxes',
        'metadata',
    ];

    protected $casts = [
        'line_items' => 'array',
        'taxes' => 'array',
        'due_date' => 'date',
        'paid_date' => 'date',
        'metadata' => 'array',
    ];
}
