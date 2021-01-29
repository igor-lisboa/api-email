<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
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
    protected $fillable = ['user_uid', 'slug', 'content', 'active'];

    public function variables()
    {
        return $this->hasMany(TemplateVariables::class, 'template_uid', 'uid');
    }
}
