<?php

namespace Mojtaba\Chatable\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mojtaba\Chatable\Helper\FilePathHelper;
use Mojtaba\Chatable\Tests\TestCase;

class FilePathHelperTest extends TestCase
{
    public function test_config_file_path_exists()
    {

        $this->assertEquals('chat_medias/{year}/{month}/{chat_uuid}', config('chatable.file_path'));
    }

    /**@test */
    public function test_regex_for_file_path()
    {
        $filepath = config('chatable.file_path');

        $options = (new FilePathHelper())->getOptions($filepath);

        $this->assertIsArray($options);
        $this->assertEquals(['year', 'month', 'chat_uuid'], $options[1]);
    }

    /**@test */
    public function test_return_values_of_file_path_helper()
    {
        $this->assertEquals('chat_medias/2022/2/', (new FilePathHelper())->generate());
    }

    /**@test */
    public function test_return_override_values_of_file_path_helper()
    {
        $uuid = Str::uuid()->toString();
        $path = (new FilePathHelper([
            'month' => 1,
            'chat_uuid' => $uuid
        ]))->generate();

        $this->assertEquals("chat_medias/2022/1/$uuid", $path);
    }
}