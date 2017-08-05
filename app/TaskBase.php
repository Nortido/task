<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task;

use Task\App\Core\DB;

class TaskBase
{
    /**
     * @var DB
     */
    public $db;
    /**
     * @var array
     */
    public $config;
}