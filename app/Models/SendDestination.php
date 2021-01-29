<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class SendDestination extends Model
{
    use Uuid;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'uid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['send_uid', 'email_uid', 'group_uid', 'destiny_type_id'];

    public function send()
    {
        return $this->belongsTo(Send::class, 'send_uid', 'uid');
    }

    public function email()
    {
        return $this->hasOne(Email::class, 'email_uid');
    }

    public function group()
    {
        return $this->hasOne(Group::class, 'group_uid');
    }

    public function destinyType()
    {
        return $this->hasOne(DestinyType::class, 'destiny_type_id');
    }
}
