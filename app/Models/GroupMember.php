<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
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
    protected $fillable = ['group_uid', 'email_uid'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_uid');
    }

    public function email()
    {
        return $this->hasOne(Email::class, 'email_uid');
    }
}
