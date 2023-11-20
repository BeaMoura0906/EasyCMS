<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\IndexManager;

class IndexController extends Controller
{
    private $_manager;

    public function __construct()
    {
        $this->_manager = new IndexManager();
        parent::__construct();
    }

    public function defaultAction()
    {
        $this->render('index');
    }

    public function loginAction()
    {
        $loginSpace = true;
        $data = [
            'loginSpace' => $loginSpace
        ];
        $this->render('index', $data);
    }
}