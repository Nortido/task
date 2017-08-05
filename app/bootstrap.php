<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

use Task\App\Core\DB;
use Task\App\Core\Route;
use Task\TaskBase;

require_once 'TaskBase.php';
require_once 'core/Model.php';
require_once 'core/Controller.php';
require_once 'core/DB.php';
require_once 'core/Route.php';

$app = new TaskBase();
$app->db = new DB();
$app->config = include('config.php');
Route::start($app);