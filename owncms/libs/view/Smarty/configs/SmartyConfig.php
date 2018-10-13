<?php

    define('SMARTY', 'libs/view/Smarty/');

    return $arry = [
          'template_dir' => SMARTY.'templates/',
          'compile_dir' => SMARTY.'templates_c/',
          'config_dir' => SMARTY.'configs/',
          'cache_dir' => SMARTY.'cache/',
          'left_delimiter' => '{',
          'right_delimiter' => '}'
      ];