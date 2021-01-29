<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class ExpressMailNotSendFor extends Model
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
    protected $fillable = [
        'express_mail_uid',
        'group_uid'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'express_mail_not_send_for';

    public function expressMail()
    {
        return $this->belongsTo(ExpressMail::class, 'express_mail_uid', 'uid');
    }

    public function group()
    {
        return $this->hasOne(Group::class, 'group_uid');
    }
}
