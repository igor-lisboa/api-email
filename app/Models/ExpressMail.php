<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class ExpressMail extends Model
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
    protected $fillable = [
        'user_uid',
        'subject',
        'send_type_id',
        'mailer_id',
        'from_uid',
        'answer_to_uid',
        'template_uid',
        'show_online',
        'embed_image',
        'markdown',
        'send_moment'
    ];

    public function sendType()
    {
        return $this->hasOne(SendType::class, 'send_type_id');
    }

    public function mailer()
    {
        return $this->hasOne(Mailer::class, 'mailer_id');
    }

    public function from()
    {
        return $this->hasOne(Email::class, 'from_uid');
    }

    public function answerTo()
    {
        return $this->hasOne(Email::class, 'answer_to_uid');
    }

    public function template()
    {
        return $this->hasOne(Template::class, 'template_uid');
    }

    public function notSendFor()
    {
        return $this->hasMany(ExpressMailNotSendFor::class, 'express_mail_uid');
    }

    public function attachments()
    {
        return $this->hasMany(ExpressMailAttachment::class, 'express_mail_uid');
    }

    public function sends()
    {
        return $this->hasMany(Send::class, 'express_mail_uid');
    }
}
