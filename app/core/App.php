<?php

class App
{

    protected static $routes = [];

    protected static $config;

    protected $activePath;

    protected $activeMethod;

    protected $notFound;

    protected $auth;

    public function __construct($activePath, $activeMethod, $config)
    {

        $this->activePath = $activePath;
        $this->activeMethod = $activeMethod;
        self::$config = $config;
        $this->auth = self::$config["authentication"];

        $this->notFound = function () {

            $data = "Hata kodu: 404";
            return View::renderView("panel", "page404", $data);

        };
    }

    public static function get($link, $path, $auth = false, $callback = null)
    {

        self::$routes[] = ["GET", $link, $path, $auth, $callback];

    }

    public static function post($link, $path, $auth = false, $callback = null)
    {

        self::$routes[] = ["POST", $link, $path, $auth, $callback];

    }


    public function run()
    {

        foreach (self::$routes as $route) {

            list($method, $link, $path, $auth, $params) = $route;

            $methodCheck = $this->activeMethod == $method;

            if (strlen($this->activePath) > 1 && substr($this->activePath, -1) == "/") {

                $this->activePath = substr($this->activePath, 0, -1);

            }

            //GET isteklerini ayırma
            $this->activePath = explode("?", $this->activePath)[0];

            $pathCheck = preg_match("~^{$link}$~", $this->activePath, $params);

            if ($methodCheck && $pathCheck) {

                $path = array_filter(explode("/", $path));

                if ($auth == true && isset($_SESSION[$this->auth["auth_files"][$path[0]]]) || $auth == false) {

                    $module = $path[0];
                    $controller = $path[0] . "Controller";
                    $action = $path[1] . "Action";

                }else{

                    Controller::redirect($this->auth["auth_urls"][$path[0]] . "?redirect=" . $this->activePath . "&auth=false");
                    exit;

                }


                if (file_exists($file = APP_DIR . "/modules/{$module}/controller/{$controller}.php")) {

                    require_once $file;

                    if (class_exists($controller)) {

                        $class = new $controller;

                        if (method_exists($class, $action)) {

                            array_shift($params);

                            return call_user_func_array([$class, $action], array_values($params));

                        } else {

                            echo "Method mevcut değil.";
                            return;

                        }

                    } else {

                        echo "Sınıf mevcut değil.";
                        return;

                    }

                } else {

                    echo "Controller mevcut değil.";
                    return;

                }

            }

        }

        return call_user_func($this->notFound);

    }

}