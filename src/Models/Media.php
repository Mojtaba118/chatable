<?php

namespace Mojtaba\Chatable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Mojtaba\Chatable\Helper\MimeTypeHelper;

class Media extends Model
{
    protected $guarded = ['id'];

    protected $table = 'chatable_medias';

    protected $appends = [
        'file_url',
        'file_type'
    ];

    public function getFileUrlAttribute()
    {
        return Storage::drive(config('chatable.storage_driver'))->url($this->path);
    }

    public function getFileTypeAttribute()
    {
        return MimeTypeHelper::getType($this->type);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}