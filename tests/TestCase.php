<?php

namespace Mojtaba\Chatable\Tests;

use Mojtaba\Chatable\ChatableServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ChatableServiceProvider::class
        ];
    }
}
