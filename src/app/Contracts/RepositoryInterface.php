<?php

namespace ChuPhong\Repository\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @param array|string[] $columns
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model[]|\Illuminate\Support\Collection|mixed
     */
    public function all(array $columns = ['*']);

    /**
     * @param array $attributes
     * @return Model
     * @throws \Throwable
     */
    public function create(array $attributes): Model;

    /**
     * @param Model $model
     * @param array $attributes
     * @return Model
     * @throws \Throwable
     */
    public function update($model, array $attributes): Model;

    /**
     * @param Model $model
     * @return Model
     * @throws \ChuPhong\Repository\Exceptions\ModelDeleteException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete($model): Model;

    /**
     * @param int|null $limit
     * @param string[] $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $limit = null, array $columns = ['*']);

    /**
     * @param array|string $relations
     * @return \ChuPhong\Repository\Contracts\RepositoryInterface
     */
    public function with($relations): RepositoryInterface;

    /**
     * @param mixed $relations
     * @return RepositoryInterface
     */
    public function withCount($relations): RepositoryInterface;

    /**
     * @param int $limit
     * @return mixed
     */
    public function limit(int $limit);

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
