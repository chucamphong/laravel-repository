<?php

namespace ChuPhong\Repository\Contracts;

use ChuPhong\Repository\Exceptions\RepositoryExpcetion;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @param array|string[] $columns
     * @return EloquentBuilder[]|\Illuminate\Database\Eloquent\Collection|Model[]|\Illuminate\Support\Collection|mixed
     * @throws RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function all(array $columns = ['*']);

    /**
     * @param array $attributes
     * @return Model
     * @throws RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Throwable
     */
    public function create(array $attributes): Model;

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
