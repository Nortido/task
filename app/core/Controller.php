<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

class Controller
{
    public $model;
    public $db;

    public function __construct(DB $db) {
        $this->db = $db;
    }

    function action_index()
    {
    }
}