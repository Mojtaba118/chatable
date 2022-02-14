<?php

namespace Mojtaba\Chatable\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileNameHelper extends FilePathHelper
{
    protected UploadedFile $file;

    protected string $configKey = 'file_name';

    public function __construct(UploadedFile $file, $preValues = [])
    {
        $this->file = $file;

        parent::__construct($preValues);
    }

    protected function getHashedNameOption(): string
    {
        return $this->file->hashName();
    }

    protected function getOriginalNameOption(): string
    {
        return $this->file->getClientOriginalName();
    }

    protected function getExtensionOption(): string
    {
        return $this->file->extension();
    }
}