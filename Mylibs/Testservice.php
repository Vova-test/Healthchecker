<?php

namespace Mylibs;

use Mylibs\TestHealthInterface;

class TestService
{
    public function run(TestHealthInterface $object, $test, $page = false)
    {
        return $object->testing($test, $page);
    }
}
