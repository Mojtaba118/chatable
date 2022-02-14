<?php

return [


    /**
     * set storage_driver to one of the filesystem drivers
     * this option is for storing user files
     */
    'storage_driver' => 'public',


    /**
     * this option is for defining structure of file path
     *
     * year => current year
     * month => current month
     * day => current day
     * time => uses time() php function
     * chat_uuid => the uuid of chat
     */
    'file_path' => 'chat_medias/{year}/{month}/{chat_uuid}',


    /**
     * this option is for defining structure of file name
     *
     * params are like file_path params with additional params:
     * hashed_name => the hashed name of file (includes file extension)
     * original_name => the original user file name (includes file extension)
     * extension => the original user file extension
     */
    'file_name' => '{year}_{month}_{day}_{hashed_name}',


    /**
     * define mimetype for returning type of files
     * if file mime type is not one of below mime types, default type will be 'file'
     */
    'mime_types' => [
        'image' => [
            'image/bmp',
            'image/gif',
            'image/vnd.microsoft.icon',
            'image/jpeg',
            'image/png',
            'image/svg+xml'
        ],
        'audio' => [
            'audio/wav',
            'audio/webm',
            'audio/ogg',
            'audio/opus',
            'audio/mpeg',
            'audio/midi',
            'audio/x-midi',
            'audio/aac',
            'audio/3gpp',
            'audio/3gpp2'
        ],
        'video' => [
            'video/x-msvideo',
            'video/mp4',
            'video/mpeg',
            'video/ogg',
            'video/mp2t',
            'video/webm',
            'video/3gpp',
            'video/3gpp2',
            'video/x-matroska'
        ],
        'pdf' => 'application/pdf'
    ]
];