<?php

namespace Mojtaba\Chatable\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'chatable_groups';

    protected $guarded = ['id'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function readables()
    {
        return $this->morphMany(Readables::class, 'chatable');
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'chatable');
    }

    public function message()
    {
        return $this->morphMany(Message::class, 'chatable')->orderBy('id', 'DESC')->limit(1);
    }
}