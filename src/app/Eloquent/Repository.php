<?php

namespace ChuPhong\Repository\Eloquent;

use ChuPhong\Repository\Contracts\RepositoryInterface;

class Repository implements RepositoryInterface
{
    public static function __callStatic($method, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }

    public function __call($method, $arguments)
    {
        // TODO: Implement __call() method.
    }
}
