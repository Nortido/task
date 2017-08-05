<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

use Task\App\Core\Model;

class ModelUser extends Model
{
    /**
     * @var string
     */
    public $table = 'users';

    /**
     * @param string $login
     * @return mixed
     */
    public function getByLogin(string $login)
    {
        return $this->app->getDb()->findByField("login", $login, $this->table);
    }

    /**
     * @param int $id
     * @param string $amount
     * @return bool
     * @throws PDOException
     */
    public function checkout($id, $amount)
    {
        $conn = $this->app->getDb()->getConnection();
        try {
            $conn->beginTransaction();
            $conn->exec("
                SELECT * FROM users WHERE id = :id FOR UPDATE
            ");
            $stmt = $conn->prepare("
                UPDATE users SET balance = balance - :amount WHERE id = :id AND balance >= :amount
            ");
            $stmt->execute([
                ':amount' => $amount,
                ':id' => $id
            ]);

            return $conn->commit();
        } catch(PDOException $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}