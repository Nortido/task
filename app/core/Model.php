<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

use Task\TaskBase;

class Model
{
    /**
     * @var TaskBase
     */
    public $app;
    /**
     * @var string
     */
    public $table;

    /**
     * Model constructor.
     * @param TaskBase $app
     */
    public function __construct(TaskBase $app) {
        $this->app = $app;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get(int $id) {
        return $this->app->getDb()->get($id, $this->table);
    }
}