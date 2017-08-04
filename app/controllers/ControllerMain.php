<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
use Task\App\Core\Controller;

class ControllerMain extends Controller
{
    function actionIndex()
    {
        include("app/views/login.php");
    }
}