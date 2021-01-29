<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    public static function bootUuid()
    {
        static::creating(function ($model) {
            $uid = $model->getKeyName();
            if (!empty($model->$uid)) {
                return;
            }
            $model->$uid = Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
