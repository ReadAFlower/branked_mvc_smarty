<?php

/**
 * admin控制器
 */

pcBase::loadSysClass('baseController','controllers/',0);
class adminController extends baseController
{
    public function __construct()
    {
        $this->dbConfig = pcBase::loadConfig('database');
        $this->table_name = 'admin';
        parent::__construct();
    }

    public function init()
    {
//        $userId = $_SESSION['userid'.HASH_IP];
//        $userNaem = $_SESSION['username'.HASH_IP];
        $view = viewEngine();
//        var_dump($view->getTemplateDir());
//        exit();
        $view->display('login.php');


    }

    public function login()
    {

    }

    public function index()
    {

    }



}