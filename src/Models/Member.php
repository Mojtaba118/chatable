<?php

namespace Mojtaba\Chatable\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $guarded = ['id'];

    protected $table = 'chatable_members';

    public function membres()
    {
        return $this->morphTo();
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}