<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

use Task\App\Core\DB;
use Task\App\Core\Route;

require_once 'core/Model.php';
require_once 'core/Controller.php';
require_once 'core/DB.php';
require_once 'core/Route.php';

Route::start(new DB());