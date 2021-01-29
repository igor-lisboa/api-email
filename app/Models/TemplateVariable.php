<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class TemplateVariable extends Model
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
    protected $fillable = ['template_uid', 'slug', 'description'];

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_uid', 'uid');
    }
}
