<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
use Task\App\Core\Controller;

class ControllerMain extends Controller
{
    function actionIndex()
    {
        if ($this->getApp()->getSession()['user_id']
            && $this->getApp()->getSession()['token']
            && $this->getApp()->getSession()['access_granted']
        ) {
            include("app/views/login.php");
        }

        header('Location:/user/login');
    }
}