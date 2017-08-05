<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
use Task\App\Core\Controller;
use Task\TaskBase;

class ControllerUser extends Controller
{
    public function __construct(TaskBase $app) {
        $this->model = new ModelUser($app);

        parent::__construct($app);
    }

    function actionView($id)
    {
        $data = $this->model->get($id);
        session_start();
        if ($data->password_hash == $_SESSION['hash'] && !is_null($data->password_hash)) {
            include("app/views/user.php");
        } else {
            header('Location:/user/login');
        }
    }

    function actionLogin()
    {
        session_start();

        if(isset($_POST['login']) && isset($_POST['password']))
        {
            $login = $_POST['login'];
            $password =$_POST['password'];

            $user = $this->model->getByLogin($login);
            if (password_verify($password, $user->password_hash))
            {
                $_SESSION["login_status"] = "access_granted";
                $_SESSION['hash'] = $user->password_hash;
                header('Location:/user/view/1');
            }
            else
            {
                $_SESSION["login_status"] = "access_denied";
            }
        }
        else
        {
            if (isset($_SESSION['hash'])) {
                header('Location:/user/view/1');
            }
        }
        session_write_close();

        include("app/views/login.php");
    }

    function actionLogout()
    {
        session_start();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');

        header("Location: /user/login");
    }
}