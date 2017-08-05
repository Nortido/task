<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

use Task\TaskBase;

class Model
{
    public $app;
    public $table;

    public function __construct(TaskBase $app) {
        $this->app = $app;
    }

    public function get($id) {
        return $this->app->db->get($id, $this->table);
    }
}