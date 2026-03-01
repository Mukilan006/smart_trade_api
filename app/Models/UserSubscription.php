<?php

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'UserSubscription';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'start_date',
        'end_date',
        'renew_date',
        'status'
    ];
}
