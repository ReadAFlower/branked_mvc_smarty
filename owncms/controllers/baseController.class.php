<?php
/**
 * 基础控制器
 */

class baseController
{
        public function __construct()
        {
            $db = pcBase::loadSysClass('db_mysqli');
        }

        public static function checkLogin($userId,$userName){
            $manager = isset($_GET['dosubmit']) && empty($_GET['dosubmit']) ? safe_replace($_GET['dosubmit']) : 'user';
            $type = null;
            switch ($manager){
                case 'user':
                    $userModel = new userModel();
                    $res = $userModel->checkUser($userId,$userName);
                    if($res) $type = 'user';
                    break;
                case 'admin':
                    $adminModel = new adminModel();
                    $res = $adminModel->checkAdmin($userId,$userName);
                    if($res) $type = 'admin';
                    break;
                default:
                    $type = false;
                    break;
            }

            return $type;
        }

        public static function controllerType($type){
            switch ($type){
                case 'user':
                    header('location:');
                    break;
                case 'manager':
                    $view->display();
                    break;
                default:
                    break;

            }
        }
}