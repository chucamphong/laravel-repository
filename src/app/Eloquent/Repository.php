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
     * @var Model|QueryBuilder|EloquentBuilder
     */
    protected $model;

    /**
     * @param Application $app
     * @throws RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->model = $this->getModel();
    }

    abstract protected function model(): string;

    /**
     * @throws RepositoryExpcetion
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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

    public static function __callStatic(string $method, array $arguments)
    {
        return call_user_func_array([new static, $method], $arguments);
    }

    public function __call(string $method, array $arguments)
    {
        return call_user_func_array([$this->model, $method], $arguments);
    }
}
