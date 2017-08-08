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
     * @var TaskBase
     */
    private $app;

    /**
     * DB constructor.
     */
    public function __construct(TaskBase $app)
    {
        $this->app = $app;

        $this->connection = new PDO(
            'mysql:host=mysql;dbname=taskdb',
            'root',
            $this->app->getConfig()['mysql_password']
        );
    }

    /**
     * @param int $id
     * @param string $table
     * @return array|bool
     */
    public function get(int $id, string $table)
    {
        return $this->findByField('id', $id, $table);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param string $table
     * @return array|bool
     */
    public function findByField(string $field, $value, string $table)
    {
        $stmt = $this->connection
            ->prepare("
                SELECT * FROM {$table} WHERE {$field} = '{$value}'
            ");
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}