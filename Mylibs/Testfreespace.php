<?php

namespace Mylibs;

use \Exception;
use Mylibs\TestHealthInterface;

class TestFreeSpace implements TestHealthInterface
{

    public function testing(){ return "TestFreeSpace"; }

    /*protected function testFreeSpace()
    {
        try {
            $this->status[] = "Free space = " . disk_free_space("/");
        } catch (Exception $e) {
            $this->errors[] = ($e->getMessage());
        }
    }

    protected function testWritableFolder($parameters)
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
