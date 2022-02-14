<?php

namespace Mojtaba\Chatable\Helper;

class MimeTypeHelper
{
    public static function isImage($type)
    {
        return in_array($type, config('chatable.mime_types.image'));
    }

    public static function isAudio($type)
    {
        return in_array($type, config('chatable.mime_types.audio'));
    }

    public static function isVideo($type)
    {
        return in_array($type, config('chatable.mime_types.video'));
    }

    public static function isPdf($type)
    {
        return $type === config('chatable.mime_types.pdf');
    }

    public static function getType($type)
    {
        if (static::isImage($type))
            return 'image';
        elseif (static::isAudio($type))
            return 'audio';
        elseif (static::isVideo($type))
            return 'video';
        elseif (static::isPdf($type))
            return 'pdf';
        else
            return 'file';
    }
}