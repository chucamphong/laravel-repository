<?php

namespace ChuPhong\Repository\Eloquent;

use ChuPhong\Repository\Contracts\RepositoryInterface;
use ChuPhong\Repository\Exceptions\RepositoryExpcetion;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class Repository implements RepositoryInterface
{
    protected Application $app;

    /**
     * @var Model|QueryBuilder|EloquentBuilder|null
     */
    private $model;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    abstract public function model(): string;

    /**
     * @throws RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getModel(): Model
    {
        if (is_null($this->model)) {
            $model = $this->app->make($this->model());

            if ($model instanceof Model) {
                $this->model = $model;
            }

            throw new RepositoryExpcetion(sprintf('Lớp %s phải kế thừa từ %s', $this->model(), Model::class));
        }

        return $this->model;
    }

    protected function resetModel(): void
    {
        $this->model = null;
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

    public static function __callStatic(string $method, array $arguments)
    {
        return call_user_func_array([new static, $method], $arguments);
    }

    public function __call(string $method, array $arguments)
    {
        return call_user_func_array([$this->model, $method], $arguments);
    }
}
