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
    private $db;

    /**
     * @var array
     */
    private $config;

    /**
     * @return DB
     */
    public function getDb() : DB
    {
        return $this->db;
    }

    /**
     * @param DB $db
     * @return TaskBase
     */
    public function setDb(DB $db) : TaskBase
    {
        $this->db = $db;
        return $this;
    }

    /**
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return TaskBase
     */
    public function setConfig(array $config) : TaskBase
    {
        $this->config = $config;
        return $this;
    }
}