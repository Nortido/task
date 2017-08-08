<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

use Task\App\Core\Model;
use Task\TaskBase;

class ModelUser extends Model
{
    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password_hash;

    /**
     * @var float
     */
    private $balance;

    /**
     * @var string
     */
    private $token;

    /**
     * ModelUser constructor.
     * @param TaskBase $app
     */
    public function __construct(TaskBase $app)
    {
        $this->setTable('users');

        parent::__construct($app);
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return $this
     */
    public function setLogin(string $login): ModelUser
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    /**
     * @param string $password_hash
     * @return $this
     */
    public function setPasswordHash(string $password_hash): ModelUser
    {
        $this->password_hash = $password_hash;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     * @return $this
     */
    public function setBalance(float $balance): ModelUser
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): ModelUser
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param string $login
     * @return $this
     */
    public function getByLogin(string $login): ModelUser
    {
        $user = $this->app->getDb()->findByField("login", $login, $this->getTable());

        if (is_array($user)) {
            try {
                $this->transformFromArray($user);
            } catch (InvalidArgumentException $e) {
                echo $e->getMessage();
                exit;
            }
        }

        return $this;
    }

    /**
     * @param int $id
     * @param float $amount
     * @return bool
     * @throws PDOException
     */
    public function checkout(int $id, float $amount): bool
    {
        /** @var PDO $conn */
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
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @param PDO $conn
     * @param int $id
     * @param float $amount
     * @return bool
     */
    private function _checkUserBalanceGreater(PDO $conn, int $id, float $amount): bool
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
    private function _updateUserBalance(PDO $conn, int $id, float $amount): bool
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