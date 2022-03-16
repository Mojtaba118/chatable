<?php

namespace Mojtaba\Chatable\Models;

use Illuminate\Database\Eloquent\Model;

class Readables extends Model
{
    protected $table = 'chatable_readables';

    protected $guarded = ['id'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function member()
    {
        return $this->morphTo();
    }
}