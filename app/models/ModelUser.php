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
            $conn->prepare("
                SELECT * FROM users WHERE id = :id FOR UPDATE
            ")->execute([
                ':id' => $id
            ]);

            if ($this->_checkUserBalanceGreater($conn, $id, $amount)) {
                $conn->rollBack();

                return false;
            }

            return $this->_updateUserBalance($conn, $id, $amount);
        } catch(PDOException $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @param float $amount
     * @return bool
     */
    private function _checkUserBalanceGreater(PDO $conn, $id, $amount) : bool
    {
        $balanceObject = $conn->query("
                SELECT balance FROM users WHERE id = ".$id." AND balance > ".$amount."
            ")->rowCount();

        return !$balanceObject;
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @param float $amount
     * @return bool
     */
    private function _updateUserBalance($conn, $id, $amount)
    {
        $stmt = $conn->prepare("
                UPDATE users SET balance = balance - :amount WHERE id = :id
            ");
        $stmt->execute([
            ':amount' => $amount,
            ':id' => $id
        ]);

        if ($conn->inTransaction()) {
            return $conn->commit();
        }

        return true;
    }
}