<?php
defined('IN_OWNCMS') or exit('No permission resources.');
pcBase::loadSysClass('ValidateCode','',0);
//require_once('../owncms/libs/classes/ValidateCode.class.php');
$validateCode = new ValidateCode();

$validateCode->doImg();