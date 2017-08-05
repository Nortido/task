<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */
namespace Task\App\Core;

use Task\TaskBase;

class Route
{
    static function start(TaskBase $app)
    {
        // контроллер и действие по умолчанию
        $controller_name = 'Main';
        $action_name = 'index';
        $action_params = [];
        $id = '0';

        $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $params = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        $routes = explode('/', $url);

        // получаем имя контроллера
        if ( !empty($routes[1]) )
        {
            $controller_name = ucfirst($routes[1]);
        }

        // получаем имя экшена
        if ( !empty($routes[2]) )
        {
            $action_name = ucfirst($routes[2]);
        }

        // получаем id сущности
        if ( !empty($routes[3]) )
        {
            $id = ucfirst($routes[3]);
        }

        // получаем имя экшена
        if ( !empty($params) )
        {
            parse_str($params, $action_params);
        }

        // добавляем префиксы
        $model_name = 'Model'.$controller_name;
        $controller_name = 'Controller'.$controller_name;
        $action_name = 'action'.$action_name;

        // подцепляем файл с классом модели (файла модели может и не быть)

        $model_file = $model_name.'.php';
        $model_path = "app/models/".$model_file;
        if(file_exists($model_path))
        {
            require_once "app/models/".$model_file;
        }

        // подцепляем файл с классом контроллера
        $controller_file = $controller_name.'.php';
        $controller_path = "app/controllers/".$controller_file;
        if(file_exists($controller_path))
        {
            require_once "app/controllers/".$controller_file;
        }
        else
        {
            Route::ErrorPage404();
        }

        // создаем контроллер
        $controller = new $controller_name($app);
        $action = $action_name;

        if(method_exists($controller, $action))
        {
            // вызываем действие контроллера
            $controller->$action($id, $action_params);
        }
        else
        {
            Route::ErrorPage404();
        }

    }

    static function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }
}