<?php

class Controller
{

    public function renderView($module, $action, $params = [])
    {

        View::renderView($module, $action, $params);

    }


    public function renderLayout($layout, $module, $action, $params = [])
    {

        View::renderLayout($layout, $module, $action, $params);

    }


    public static function redirect($path)
    {

        header("Location: {$path}");

    }


    public function auth()
    {

        if (isset($_SESSION['kullanici_id'])) {

            return true;

        } else {

            return false;

        }

    }


    public function _post()
    {

        if (isset($_POST) && $_SERVER["REQUEST_METHOD"] == "POST") {

            return true;

        } else {

            return false;

        }

    }

    public function _get()
    {

        if (isset($_GET) && $_SERVER["REQUEST_METHOD"] == "GET") {

            return true;

        } else {

            return false;

        }

    }

}