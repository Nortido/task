<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
use Task\App\Core\Controller;
use Task\TaskBase;

class ControllerUser extends Controller
{
    private static $error;

    public function __construct(TaskBase $app) {
        $this->model = new ModelUser($app);

        parent::__construct($app);
    }

    function actionView($id)
    {
        $data = $this->model->get($id);

        session_start();
        $data->errors = $_SESSION['errors'];
        $_SESSION['errors'] = null;

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
                $_SESSION['user_id'] = $user->id;
                header('Location:/user/view/'.$user->id);
            }
            else
            {
                $_SESSION["login_status"] = "access_denied";
            }
        }
        else
        {
            if ($_SESSION['user_id'] && $_SESSION["login_status"] == "access_granted") {
                header('Location:/user/view/'.$_SESSION['user_id']);
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

        header("Location: /");
    }

    function actionCheckout($id)
    {
        $errors = [];

        $user = $this->model->get($id);
        $amount = $_POST['amount'];
        if ($amount > $user->balance) {
            $errors[] = "Amount is greater then your balance";
        }

        if ($amount <= 0) {
            $errors[] = "Amount should be greater then zero";
        }

        if (!count($errors)) {
            $this->model->checkout($id, $amount);
        } else {
            session_start();
            $_SESSION['errors'] = $errors;
        }
        header('Location:/user/view/'.$id);
    }
}