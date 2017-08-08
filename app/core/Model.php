<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

use InvalidArgumentException;
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
    private $table;

    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function setTable(string $table): Model
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Model constructor.
     * @param TaskBase $app
     */
    public function __construct(TaskBase $app)
    {
        $this->app = $app;
    }

    /**
     * @param array $data
     * @return $this
     * @throws InvalidArgumentException
     */
    public function transformFromArray(array $data): Model
    {
        foreach ($data as $key => $value) {
            $str = str_replace(' ', '',
                ucwords(str_replace('_', ' ', $key))
            );
            $methodName = "set".($str);

            if (!method_exists($this, $methodName)) {
                throw new InvalidArgumentException("Method ".$methodName." does not exist in model ".__CLASS__);
            }
            $this->$methodName($this->getNumeric($value));
        }
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function get(int $id): Model
    {
        $data = $this->app->getDb()->get($id, $this->table);

        if (is_array($data)) {
            try {
                $this->transformFromArray($data);
            } catch (InvalidArgumentException $e) {
                echo $e->getMessage();
                exit;
            }
        }

        return $this;
    }

    /**
     * @param string $val
     * @return mixed
     */
    private function getNumeric(string $val) {
        if (is_numeric($val)) {
            return $val + 0;
        }
        return $val;
    }
}