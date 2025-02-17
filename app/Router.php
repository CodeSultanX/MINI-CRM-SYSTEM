<?php
namespace app;


use Exception;

class Router
{
    private $routes = [
        '/^\/?$/' => [ 'controller' => 'home\\HomeController', 'action' => 'index'],
        '/^\/pages(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => [ 'controller' => 'pages\\PageController'],
        '/^\/roles(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => [ 'controller' => 'roles\\RoleController'],
        '/^\/users(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => [ 'controller' => 'users\\UserController'],
        '/^\/auth(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => [ 'controller' => 'auth\\AuthController'],
        '/^\/todo\/category(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => [ 'controller' => 'todo\category\\CategoryController'],
        '/^\/todo\/tasks(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => [ 'controller' => 'todo\task\\TaskController'],
        '/^\/todo\/tasks\/by-tag(\/(?P<id>\d+))?$/' => [ 'controller' => 'todo\task\\TaskController', 'action' => 'tasksBytag'],
        '/^\/todo\/tasks\/update-status(\/(?P<id>\d+))?$/' => [ 'controller' => 'todo\task\\TaskController', 'action' => 'updateStatus'],
        '/^\/todo\/tasks\/task(\/(?P<id>\d+))?$/' => [ 'controller' => 'todo\task\\TaskController', 'action' => 'task'],
    ];

    public function run(){
        $uri = $_SERVER['REQUEST_URI'];
        $controller = null;
        $action = null;
        $params = null;
        foreach($this->routes as $pattern => $route){
            if(preg_match($pattern,$uri,$matches)){
                $controller = 'controllers\\'. $route['controller'];
                $action = $route['action'] ?? $matches['action'] ?? 'index'; 
                $params = array_filter($matches,'is_string',ARRAY_FILTER_USE_KEY);
                break;
            }
        }

        
        if(!$controller){
            http_response_code(404);
            echo 'Page not found';
            return;
        }

        $controllerInstance = new $controller();
        

        if(!method_exists($controllerInstance,$action)){
            http_response_code(404);
            echo 'Page not found';
            return;
        }
        call_user_func_array([$controllerInstance,$action],[$params]);
    }


}