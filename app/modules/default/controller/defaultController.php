<?php

class defaultController extends Controller implements FrontController{


    /**
     * @return mixed
     */
    public function indexAction()
    {
        $data = array();
        $this->renderView("default", "index", $data);

    }
}