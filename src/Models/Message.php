<?php

namespace Mojtaba\Chatable\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = ['id'];

    public function chatable()
    {
        return $this->morphTo();
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function reply()
    {
        
    }
}