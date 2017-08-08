<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
use Task\App\Core\Controller;
use Task\App\Core\Route;
use Task\TaskBase;

class ControllerUser extends Controller
{
    /**
     * ControllerUser constructor.
     * @param TaskBase $app
     */
    public function __construct(TaskBase $app) {
        $this->setModel(new ModelUser($app));

        parent::__construct($app);
    }

    /**
     * @param int $id
     */
    function actionView($id)
    {
        if (!is_int($id)) {
            Route::ErrorPage404();
        }
        /** @var ModelUser $user */
        $user = $this->getModel()->get($id);
        if (!$user) {
            echo "<p>User doesn't exist</p>";
            exit;
        }

        if (!$this->_checkAuth($user)) {
            echo "<p>Access denied</p><br>";
            exit;
        }

        /** @var array $errors */
        $errors = $this->getApp()->getSession()['errors'];
        $this->getApp()->setSessionVar('errors', "");

        if ($user->getToken() ==  $this->getApp()->getSession()['token']
            && !is_null($user->getPasswordHash())
        ) {
            include("app/views/user.php");
        } else {
            header('Location:/user/login');
        }
    }

    function actionLogin()
    {
        if(isset($_POST['login']) && isset($_POST['password']))
        {
            /** @var string $login */
            $login = $_POST['login'];
            /** @var string $password */
            $password =$_POST['password'];
            /** @var ModelUser $user */
            $user = $this->getModel()->getByLogin($login);

            if (password_verify($password, $user->getPasswordHash()))
            {
                $this->getApp()->setSessionVar("login_status","access_granted");
                //It will be token generation and putting it into a database there
                $this->getApp()->setSessionVar('token',$user->getToken());
                $this->getApp()->setSessionVar('user_id', $user->getId());
                header('Location:/user/view/'.$user->getId());
            }
            else
            {
                $this->getApp()->setSessionVar("login_status","access_denied");
            }
        }
        else
        {
            if ( $this->getApp()->getSession()['token']
                &&  $this->getApp()->getSession()["login_status"] == "access_granted") {
                header('Location:/user/view/'. $this->getApp()->getSession()['user_id']);
            }
        }

        include("app/views/login.php");
    }

    function actionLogout()
    {
        session_start();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');

        header("Location: /");
    }

    /**
     * @param int $id
     */
    function actionCheckout(int $id)
    {
        /** @var array $errors */
        $errors = [];
        /** @var ModelUser $user */
        $user = $this->getModel()->get($id);

        if (!$this->_checkAuth($user)) {
            header('Location:/');
        }
        /** @var float $amount */
        $amount = number_format((float)($_POST['amount']), 2);;

        if ($amount <= 0) {
            $errors[] = "Amount should be greater then zero";
        }

        if (!count($errors)) {
            if (!$this->getModel()->checkout($id, $amount)) {
                $errors[] = "Operation is failed";
            }
        }

        $this->getApp()->setSessionVar('errors', $errors);
        header('Location:/user/view/'.$id);
    }

    /**
     * @param ModelUser $user
     * @return bool
     */
    private function _checkAuth(ModelUser $user): bool
    {
        $isAuth =  $this->getApp()->getSession()['user_id'] === $user->getId()
            &&  $this->getApp()->getSession()['token'] === $user->getToken()
            &&  $this->getApp()->getSession()['login_status'] === 'access_granted';

        return $isAuth;
    }
}