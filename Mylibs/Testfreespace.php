<?php

namespace Mylibs;

use \Exception;
use Mylibs\TestHealthInterface;

class TestFreeSpace implements TestHealthInterface
{
    protected $error;
    protected $status;
    protected $threshold;

    public function testing($data = [], $page = false)
    {
        $this->error = '';
        $this->threshold = $data['threshold'];

        try {
            $diskSpace = disk_free_space("/");
            $mess = "disk space: available $diskSpace bytes need more than $this->threshold bytes";

            if ($this->threshold < $diskSpace) {
                $this->status = "Free " . $mess;
            } else {
                $this->error = "There is not enough " . $mess;
            }
        } catch (Exception $e) {
            $this->error = ($e->getMessage());
        }
        if ($page) {
            return $this->status ?? $this->error;
        } else {
            return $this->error;
        }
    }
}
