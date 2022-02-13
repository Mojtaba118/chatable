<?php

namespace Mojtaba\Chatable\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = ['id'];

    protected $table = 'chatable_medias';

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}