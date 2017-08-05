<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

use PDO;

class DB
{
    private $connection;

    public function __construct() {
        $this->connection = new PDO('mysql:host=mysql;dbname=taksdb', 'root', 'JV4yLWsPlzQkCvMz3E5j');
    }

    public function get($id, $table)
    {
        return $this->connection
            ->query("SELECT * FROM ".$table." WHERE id = ".$id)
            ->fetchObject()
        ;
    }

    public function findByField($field, $value, $table)
    {
        return $this->connection
            ->query("SELECT * FROM ".$table." WHERE ".$field." = '".$value."' LIMIT 1")
            ->fetchObject()
        ;
    }
}