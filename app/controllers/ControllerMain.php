<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
use Task\App\Core\Controller;

class ControllerMain extends Controller
{
    function actionIndex()
    {
        session_start();

        if (isset($_SESSION['hash'])) {
            header('Location:/user/view/1');
        }

        include("app/views/login.php");
    }
}