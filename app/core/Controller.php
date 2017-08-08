<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

use Task\TaskBase;

class Controller
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var TaskBase
     */
    private $app;

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel(Model $model): Controller
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return TaskBase
     */
    public function getApp(): TaskBase
    {
        return $this->app;
    }

    /**
     * @param TaskBase $app
     * @return $this
     */
    public function setApp(TaskBase $app): Controller
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Controller constructor.
     * @param TaskBase $app
     */
    public function __construct(TaskBase $app) {
        $this->app = $app;
    }

    function action_index()
    {
    }
}