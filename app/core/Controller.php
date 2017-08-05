<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

use Task\TaskBase;

class Controller
{
    public $model;
    public $app;

    public function __construct(TaskBase $app) {
        $this->app = $app;
    }

    function action_index()
    {
    }
}