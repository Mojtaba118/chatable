<?php

namespace Mojtaba\Chatable\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FilePathHelper
{
    protected string $configKey = 'file_path';

    public function __construct($preValues = [])
    {
        foreach ($preValues as $key => $value) {
            $keyCamelCase = Str::camel($key);

            $this->{$keyCamelCase} = $value;
        }
    }

    public function generate()
    {
        $configValue = config('chatable.' . $this->configKey);

        $options = $this->getOptions($configValue);

        if (!count($options[1]))
            return $configValue;

        $fullOptions = $options[0];
        $options = $options[1];

        $values = [];

        foreach ($options as $key => $option) {
            $optionCamelCase = Str::camel($option);
            $methodName = "get" . Str::ucfirst($optionCamelCase) . "Option";

            $values[$fullOptions[$key]] = $this->{$optionCamelCase} ?? (method_exists($this, $methodName) ? $this->{$methodName}() : '');
        }

        return Str::replace(array_keys($values), array_values($values), $configValue);
    }

    public function getOptions($path)
    {
        preg_match_all('/{([a-zA-Z_]+)}/', $path, $options);

        return $options;
    }

    protected function getYearOption()
    {
        return now()->year;
    }

    protected function getMonthOption()
    {
        return now()->month;
    }

    protected function getDayOption()
    {
        return now()->day;
    }

    protected function getTimeOption()
    {
        return time();
    }
}