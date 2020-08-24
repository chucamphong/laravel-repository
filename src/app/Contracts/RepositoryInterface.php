<?php

namespace ChuPhong\Repository\Contracts;

interface RepositoryInterface
{
    /**
     * @param string[] $columns
     * @return mixed
     */
    public function all(array $columns = ['*']);

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $method, array $arguments);

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments);
}
