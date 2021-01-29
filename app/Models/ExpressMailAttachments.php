<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class ExpressMailAttachments extends Model
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
    protected $fillable = ['express_mail_uid', 'file', 'name'];

    public function expressMail()
    {
        return $this->belongsTo(ExpressMail::class, 'express_mail_uid', 'uid');
    }
}
