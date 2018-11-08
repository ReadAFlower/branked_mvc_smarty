<?php
defined('IN_OWNCMS') or exit('No permission resources.');
pcBase::loadSysClass('ValidateCode','',0);

$validateCode = new ValidateCode();

$validateCode->doImg();