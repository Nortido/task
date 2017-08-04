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
}