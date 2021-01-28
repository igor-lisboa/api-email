<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_uid',
        'slug',
        'priority',
        'quota_qtd',
        'quota_qtd_max',
        'quota_renovation',
        'active'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
