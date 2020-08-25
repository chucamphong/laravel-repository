<?php

namespace ChuPhong\Repository\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @param array|string[] $columns
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model[]|\Illuminate\Support\Collection|mixed
     * @throws \ChuPhong\Repository\Exceptions\RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function all(array $columns = ['*']);

    /**
     * @param array $attributes
     * @return Model
     * @throws \ChuPhong\Repository\Exceptions\RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Throwable
     */
    public function create(array $attributes): Model;

    /**
     * @param Model $model
     * @param array $attributes
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \ChuPhong\Repository\Exceptions\RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Throwable
     */
    public function update(Model $model, array $attributes): Model;

    /**
     * @param Model $model
     * @return Model
     * @throws \ChuPhong\Repository\Exceptions\ModelDeleteException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Exception
     */
    public function delete(Model $model): Model;

    /**
     * @param int|null $limit
     * @param string[] $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \ChuPhong\Repository\Exceptions\RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function paginate(int $limit = null, array $columns = ['*']);

    /**
     * @param mixed $relations
     * @return RepositoryInterface
     */
    public function withCount($relations): RepositoryInterface;

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
