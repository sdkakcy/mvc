<?php

/**
 * Class View
 */
class View
{

    /**
     * @param $module
     * @param $action
     * @param null $data
     * @param bool $return
     * @return false|string
     */
    public static function renderView($module, $action, $data = null, $return = false)
    {

        if ($return == false) {

            require(APP_DIR . "modules/{$module}/view/{$action}View.php");

        } else {

            ob_start();
            require(APP_DIR . "modules/{$module}/view/{$action}View.php");
            $text = ob_get_contents();
            ob_end_clean();
            return $text;

        }

    }

    /**
     * @param $layout
     * @param $module
     * @param $action
     * @param null $data
     */
    public static function renderLayout($layout, $module, $action, $data = null)
    {

        $data["VIEW"] = $action != NULL ? view::renderView($module, $action, $data, true) : null;

        require(APP_DIR . "layout/{$layout}Layout.php");

    }
}