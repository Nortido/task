<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

use Task\App\Core\Model;

class ModelUser extends Model
{
    public $table = 'users';

    public function getByLogin($login)
    {
        return $this->app->db->findByField("login", $login, $this->table);
    }

    public function checkout($id, $amount)
    {
        $conn = $this->app->db->getConnection();
        try {
            $conn->beginTransaction();
            $conn->exec('SELECT * FROM users WHERE id = :id FOR UPDATE');
            $stmt = $conn->prepare("
              UPDATE users SET balance = balance - :amount WHERE id = :id AND balance >= :amount
            ");
            $stmt->execute([
                ':amount' => $amount,
                ':id' => $id
            ])
            ;
            $conn->commit();

            return true;
        } catch(PDOException $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}