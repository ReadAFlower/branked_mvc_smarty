<?php
/* Smarty version 3.1.33, created on 2018-10-16 16:12:09
  from 'D:\phpstudy\WWW\mvc.branked.com\owncms\libs\view\Smarty\templates\login.php' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5bc59d5958c9f7_88545370',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5c6df67c768d693b988ce971e6b94f79c810a0ab' => 
    array (
      0 => 'D:\\phpstudy\\WWW\\mvc.branked.com\\owncms\\libs\\view\\Smarty\\templates\\login.php',
      1 => 1539677513,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5bc59d5958c9f7_88545370 (Smarty_Internal_Template $_smarty_tpl) {
echo '<?php

';?>require_once ('../../../functions/globals.fun.php');
<?php echo '?>';?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/style/icfont/iconfont.css">
	<?php echo '<script'; ?>
 type="text/javascript" src="/style/js/jquery-1.7.2.min.js"><?php echo '</script'; ?>
>
    <link rel="stylesheet" type="text/css" href="/style/css/admin.css">

</head>
<body style=" background: url('/style/images/login_bg.jpg') no-repeat;background-size: 100%;">
	<div class="login_box">
        <h1>后 台 管 理</h1>
        <p>&nbsp;</p>
		<form action="/index.php?m=<?php echo $_smarty_tpl->tpl_vars['m']->value;?>
&c=<?php echo $_smarty_tpl->tpl_vars['c']->value;?>
&e=login&dosubmit=<?php echo $_smarty_tpl->tpl_vars['loginType']->value;?>
" method="post" id="login_form">
			<input type="hidden" name="login_type" value="<?php echo $_smarty_tpl->tpl_vars['loginType']->value;?>
">
			<div class="login_input uname">
				<i class="icon iconfont icon-icon"></i>
				<input type="text" name="uname" id="uname" placeholder="用户名">
			</div>
			<div class="login_input pwd">
				<i class="icon iconfont icon-mima"></i>
				<input type="password" name="pwd" id="pwd" placeholder="密码">
			</div>
			<div class="login_input code">

                <i class="icon iconfont icon-securityCode-b"></i>
                <input type="text" name="code" id="code" placeholder="验证码">

                <img class="code_img" src="http://mvc.branked.com/api/codeImg.php" onclick="this.src='http://mvc.branked.com/api/codeImg.php?'+Math.random();">

			</div>
			<div class="login_input submit">
				<input type="submit" name="submit" id="dosubmit" value=" 登 录 ">
			</div>
		</form>
	</div>
   <!-- <?php echo '<script'; ?>
 type="text/javascript" src="/style/js/checkform.js">    <?php echo '</script'; ?>
> -->
</body>
</html><?php }
}
