<?php

namespace App\Models;

use App\Traits\Uuid;
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
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['group_uid', 'email_uid'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_uid');
    }

    public function email()
    {
        return $this->hasOne(Email::class, 'email_uid');
    }
}
