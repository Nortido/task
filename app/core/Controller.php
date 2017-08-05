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
    public $model;
    /**
     * @var TaskBase
     */
    public $app;

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