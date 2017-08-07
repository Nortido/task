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
     * @var array
     */
    private $session;

    /**
     * @return array
     */
    public function getSession() : array
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     * @return TaskBase
     */
    public function setSession($session) : TaskBase
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @param mixed $session
     * @return TaskBase
     */
    public function setSessionVar($field, $value) : TaskBase
    {
        session_start();
        $this->session[$field] = $value;
        $_SESSION = $this->session;
        session_write_close();

        return $this;
    }

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