<?php
/**
 *  index.php API 入口
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-7-26
 */
define('APP_PATH', str_replace('\\','/',dirname(__FILE__).DIRECTORY_SEPARATOR));
require_once APP_PATH.'owncms/base.php';

$config = require_once APP_PATH.'configs/aipconfig.php';
$param = pcbase::loadSysClass('param');

$op = isset($_GET['op']) && trim($_GET['op']) ? trim($_GET['op']) : exit('Operation can not be empty');
if (isset($_GET['callback']) && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]+$/', $_GET['callback']))  unset($_GET['callback']);
if (@in_array($config['file'][$op],$config['file']) && file_exists(APP_PATH.'api/'.$config['file'][$op])) {
    require_once APP_PATH.'api/'.$config['file'][$op];
} else {
    exit('API handler does not exist');
}
?>