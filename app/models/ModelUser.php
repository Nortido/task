<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

use Task\App\Core\Model;

class ModelUser extends Model
{
    public $table = 'users';

    public function getByLogin($login) {
        return $this->app->db->findByField("login", $login, $this->table);
    }
}