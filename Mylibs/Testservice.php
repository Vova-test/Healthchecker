<?php

namespace Mylibs;

use Mylibs\TestHealthInterface;

class TestService
{
    public function run(TestHealthInterface $object, $test){ 
        return $object->testing($test); 
    }
}
