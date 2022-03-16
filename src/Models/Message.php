<?php

namespace Mojtaba\Chatable\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chatable_messages';

    protected $guarded = ['id'];

    public function chatable()
    {
        return $this->morphTo();
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    public function reply()
    {
        return $this->belongsTo(Message::class);
    }

    public function reads()
    {
        return $this->hasMany(Readables::class);
    }
}