<?php

namespace Mylibs;

use Mylibs\TestHealthInterface;

class TestWritableFolder implements TestHealthInterface
{
    protected $error;
    protected $status;
    protected $path;

    public function testing($data = [], $page = false)
    {
        $this->error = '';
        $this->path = $data['path'];

        clearstatcache(true, $this->path);

        if (file_exists($this->path)) {
            if (is_writable($this->path)) {
                $this->status = "The folder or file $this->path is writable";
            } else {
                $this->error = "The folder or file $this->path is not writable";
            }
        } else {
            $this->error = "The folder or file $this->path does not exist";
        }

        if ($page) {
            return $this->status ?? $this->error;
        } else {
            return $this->error;
        }
    }
}
