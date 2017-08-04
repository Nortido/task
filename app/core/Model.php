<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

class Model
{
    public $db;
    public $table;

    public function __construct(DB $db) {
        $this->db = $db;
    }

    public function get($id) {
        return $this->db->get($id, $this->table);
    }
}