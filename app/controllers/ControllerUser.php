<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
use Task\App\Core\Controller;
use Task\TaskBase;

class ControllerUser extends Controller
{
    /**
     * ControllerUser constructor.
     * @param TaskBase $app
     */
    public function __construct(TaskBase $app) {
        $this->model = new ModelUser($app);

        parent::__construct($app);
    }

    /**
     * @param int $id
     */
    function actionView($id)
    {
        $user = $this->model->get($id);

        if (!$user) {
            echo "<p>User doesn't exist</p>";
            exit;
        }

        if (!$this->_checkAuth($user)) {
            echo "<p>Access denied</p><br>";
            exit;
        }

        $errors = $this->app->getSession()['errors'];
        $this->app->setSessionVar('errors', null);

        if ($user->password_hash ==  $this->app->getSession()['hash'] && !is_null($user->password_hash)) {
            include("app/views/user.php");
        } else {
            header('Location:/user/login');
        }
    }

    function actionLogin()
    {
        if(isset($_POST['login']) && isset($_POST['password']))
        {
            $login = $_POST['login'];
            $password =$_POST['password'];

            $user = $this->model->getByLogin($login);
            if (password_verify($password, $user->password_hash))
            {
                $this->app->setSessionVar("login_status","access_granted");
                $this->app->setSessionVar('hash',$user->password_hash);
                $this->app->setSessionVar('user_id',$user->id);
                header('Location:/user/view/'.$user->id);
            }
            else
            {
                $this->app->setSessionVar("login_status","access_denied");
            }
        }
        else
        {
            if ( $this->app->getSession()['hash'] &&  $this->app->getSession()["login_status"] == "access_granted") {
                header('Location:/user/view/'. $this->app->getSession()['user_id']);
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
        $errors = [];

        $user = $this->model->get($id);

        if (!$this->_checkAuth($user)) {
            header('Location:/');
        }
        $amount = floatval($_POST['amount']);

        if ($amount <= 0) {
            $errors[] = "Amount should be greater then zero";
        }

        if (!count($errors)) {
            if (!$this->model->checkout($id, $amount)) {
                $errors[] = "Operation is failed";
            }
        }

        $this->app->setSessionVar('errors', $errors);
        header('Location:/user/view/'.$id);
    }

    /**
     * @param ModelUser $user
     * @return bool $isAuth
     */
    private function _checkAuth($user)
    {
        $isAuth =  $this->app->getSession()['user_id'] === $user->id
            &&  $this->app->getSession()['hash'] === $user->password_hash
            &&  $this->app->getSession()['login_status'] === 'access_granted';

        return $isAuth;
    }
}