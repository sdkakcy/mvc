<?php
date_default_timezone_set("Europe/Istanbul");

define("DIR", "./");
define("APP_DIR",  DIR . "app/");
define("CORE", APP_DIR . "core/");
define("CONFIG", APP_DIR . "config/");
define("HOMEPATH", "");

// Veritabanı ayarları
define("HOST", "");
define("USER", "");
define("PASS", "");
define("DBNAME", "");

// SMTP ayarları
define("SITEISMI", "");
define("SMTPHOST", "");
define("SMTPUSER", "");
define("SMTPPASS", "");
define("FROM", "");


define("APPVER", "1");

define("FCSSV", "1.1.1.0");
define("FJSV", "1.1.1.0");
define("PCSSV", "1.1.1.0");
define("PJSV", "1.1.1.0");

ini_set("display_errors", "off");

require_once CORE . "Model.php";
require_once CORE . "Controller.php";
require_once CORE . "View.php";
require_once CORE . "App.php";
require_once "routing.php";
require_once DIR . "vendor/autoload.php";

global $config;

$config = array(

    "authentication" => array(
        "auth_urls" => array(
            "panel" => "/panel/giris",
        ),

        "auth_files" => array(
            "panel" => "kullanici_id",
        ),

    ),

    "debug" => "yes",

);

spl_autoload_register(function ($class_name) {

    $module = explode("Model", $class_name);

    if (file_exists(APP_DIR . "modules/{$module[0]}/model/{$class_name}.php")) {

        require_once APP_DIR . "modules/{$module[0]}/model/{$class_name}.php";

    }

    if (file_exists(CORE . "interface/{$class_name}.php")) {

        require_once CORE . "interface/{$class_name}.php";

    }

    if(file_exists(APP_DIR . "event/{$class_name}.php")){

        require_once APP_DIR . "event/{$class_name}.php";

    }

    if(file_exists(APP_DIR . "listener/{$class_name}.php")){

        require_once APP_DIR . "listener/{$class_name}.php";

    }

});

function fatal_handler()
{

    global $config;

    $error = error_get_last();

    if ($error != NULL && $error["type"] != 8192) {

        if ($config["debug"] == "yes") {

            echo "<pre>";
            print_r($error);
            echo "</pre>";

        } else if ($config["debug"] == "no" && $error["type"] != 8) {

            echo "Sistemsel bir hata oluştu";

        }

    }

}

register_shutdown_function("fatal_handler");