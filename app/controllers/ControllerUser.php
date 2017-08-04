<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
use Task\App\Core\Controller;
use Task\App\Core\DB;

class ControllerUser extends Controller
{
    public function __construct(DB $db) {
        $this->model = new ModelUser($db);

        parent::__construct($db);
    }

    function actionView($id)
    {
        $data = $this->model->get($id);

        session_start();
        if ($data->password == $_SESSION['hash']) {
            include("app/views/user.php");
        } else {
            header('Location:/login');
        }
    }

    function actionLogin()
    {
        if(isset($_POST['login']) && isset($_POST['password']))
        {
            $login = $_POST['login'];
            $password =$_POST['password'];

            var_dump($this->db->findByField('login', $login, 'users'));
            if (password_verify($password, '$2y$10$cXdlMTIzTElHREEqXiFAIuT3BqDUjZMhSxbNOGueYMNgijgxpt3k2'))
            {
                $data["login_status"] = "access_granted";

                header('Location:/user/view/1');
                session_start();
                $_SESSION['user'] = '$2y$10$cXdlMTIzTElHREEqXiFAIuT3BqDUjZMhSxbNOGueYMNgijgxpt3k2';
                session_write_close();
            }
            else
            {
                $data["login_status"] = "access_denied";
            }
        }
        else
        {
            $data["login_status"] = "";
        }

        include("app/views/login.php");
    }
}