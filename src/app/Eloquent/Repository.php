<?php

namespace ChuPhong\Repository\Eloquent;

use ChuPhong\Repository\Contracts\RepositoryInterface;
use ChuPhong\Repository\Exceptions\ModelDeleteException;
use ChuPhong\Repository\Exceptions\RepositoryExpcetion;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

abstract class Repository implements RepositoryInterface
{
    protected Application $app;

    /**
     * @var Model|EloquentBuilder
     */
    protected $model;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->model = $this->getModel();
    }

    abstract protected function model(): string;

    /**
     * @throws RepositoryExpcetion
     */
    protected function getModel(): Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryExpcetion(sprintf('Lớp %s phải kế thừa từ %s', $this->model(), Model::class));
        }

        return $model;
    }

    /**
     * @throws RepositoryExpcetion
     */
    protected function resetModel(): void
    {
        $this->model = $this->getModel();
    }

    public function all(array $columns = ['*'])
    {
        if ($this->model instanceof EloquentBuilder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetModel();

        return $results;
    }

    public function create(array $attributes): Model
    {
        $model = $this->model->newInstance($attributes);

        $model->saveOrFail();

        $this->resetModel();

        return $model;
    }

    public function update($model, array $attributes): Model
    {
        if (!$model->exists) {
            throw new ModelNotFoundException("Không tìm thấy model có id = {$model->getKey()}");
        }

        $model->fill($attributes);

        $model->saveOrFail();

        $this->resetModel();

        return $model;
    }

    public function delete($model): Model
    {
        if (!$model->exists) {
            throw new ModelNotFoundException("Không tìm thấy model có id = {$model->getKey()}");
        }

        $originalModel = clone $model;

        $this->resetModel();

        if (!$model->delete()) {
            throw new ModelDeleteException("Không thể xóa model có id = {$model->getKey()}");
        }

        return $originalModel;
    }

    public function paginate(int $limit = null, array $columns = ['*'])
    {
        $limit = $limit ?? Config::get('repository.pagination.limit');

        $results = $this->model->paginate($limit, $columns);

        $this->resetModel();

        return $results;
    }

    public function with($relations): RepositoryInterface
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    public function withCount($relations): RepositoryInterface
    {
        $this->model = $this->model->withCount($relations);

        return $this;
    }

    public function limit(int $limit)
    {
        $results = $this->model->limit($limit)->get();

        $this->resetModel();

        return $results;
    }

    public function orderByAsc(string $column): RepositoryInterface
    {
        $this->model = $this->model->orderBy($column);

        return $this;
    }

    public function orderByDesc(string $column): RepositoryInterface
    {
        $this->model = $this->model->orderByDesc($column);

        return $this;
    }

    public function whereLike($attributes, string $searchTerm): RepositoryInterface
    {
        /** @var string[] $attributes */
        $attributes = Arr::wrap($attributes);

        $this->model = $this->model->where(function (EloquentBuilder $query) use ($attributes, $searchTerm) {
            foreach ($attributes as $attribute) {
                $query->when(
                    Str::contains($attribute, '.'),
                    function (EloquentBuilder $query) use ($attribute, $searchTerm) {
                        [$relationName, $relationAttribute] = Str::of($attribute)->explode('.');

                        $query->orWhereHas($relationName,
                            function (EloquentBuilder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                    },
                    function (EloquentBuilder $query) use ($attribute, $searchTerm) {
                        $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                    }
                );
            }
        });

        return $this;
    }

    public function latest(string $column = null): RepositoryInterface
    {
        $this->model = $this->model->latest($column);

        return $this;
    }

    public function oldest($column = 'created_at'): RepositoryInterface
    {
        $this->model = $this->model->oldest($column);

        return $this;
    }

    public static function __callStatic(string $method, array $arguments)
    {
        return call_user_func_array([new static, $method], $arguments);
    }

    public function __call(string $method, array $arguments)
    {
        return call_user_func_array([$this->model, $method], $arguments);
    }
}
