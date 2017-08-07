<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

use PDO;
use Task\TaskBase;

class DB
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * DB constructor.
     */
    public function __construct(array $config) {
        $this->connection = new PDO(
            'mysql:host=mysql;dbname=taskdb',
            'root',
            $config['mysql_password']
        );
    }

    /**
     * @param int $id
     * @param string $table
     * @return mixed
     */
    public function get(int $id,string $table)
    {
        return $this->findByField('id', $id, $table);
    }

    /**
     * @param string $field
     * @param string|int $value
     * @param string $table
     * @return mixed
     */
    public function findByField(string $field, $value,string $table)
    {
        return $this->connection
            ->query("SELECT * FROM ".$table." WHERE ".$field." = '".$value."'")
            ->fetchObject()
        ;
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}