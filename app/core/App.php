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

    public static function get($path, $auth = false, $callback = null)
    {

        self::$routes[] = ["GET", $path, $auth, $callback];

    }

    public static function post($path, $auth = false, $callback = null)
    {

        self::$routes[] = ["POST", $path, $auth, $callback];

    }


    public function run()
    {

        foreach (self::$routes as $route) {

            list($method, $path, $auth, $params) = $route;

            $methodCheck = $this->activeMethod == $method;

            if (strlen($this->activePath) > 1 && substr($this->activePath, -1) == "/") {

                $this->activePath = substr($this->activePath, 0, -1);

            }

            //GET isteklerini ayırma
            $this->activePath = explode("?", $this->activePath)[0];

            $pathCheck = preg_match("~^{$path}$~", $this->activePath, $params);


            if ($methodCheck && $pathCheck) {

                $url = array_filter(explode("/", $path));

                if (count($url) == 0) {

                    $module = "efendi";
                    $controller = "efendiController";
                    $action = "indexAction";

                } else if (count($url) == 1) {

                    if ($auth == true && isset($_SESSION[$this->auth["auth_files"][$url[1]]]) || $auth == false) {

                        $module = $url[1];
                        $controller = $url[1] . "Controller";
                        $action = "indexAction";

                    } else {

                        Controller::redirect($this->auth["auth_urls"][$url[1]] . "?redirect=" . $this->activePath . "&auth=false");
                        exit;

                    }

                } else {

                    if ($auth == true && isset($_SESSION[$this->auth["auth_files"][$url[1]]]) || $auth == false) {

                        $module = $url[1];
                        $controller = $url[1] . "Controller";
                        $action = $url[2] . "Action";


                    } else {

                        Controller::redirect($this->auth["auth_urls"][$url[1]] . "?redirect=" . $this->activePath . "&auth=false");
                        exit;

                    }

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