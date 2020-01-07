<?php

namespace Mylibs;

use \Exception;
use Mylibs\TestHealthInterface;

class TestWritableFolder implements TestHealthInterface
{

    public function testing(){ return "TestWritableFolder"; }

    /*protected function testWritableFolder($parameters)
    {
        try {
            $this->status[] = 'Folder "' . $parameters['path'] . '" is writable = ' . (is_writable(
                    $parameters['path']
                ) ? "TRUE" : "FALSE");
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }*/
}
