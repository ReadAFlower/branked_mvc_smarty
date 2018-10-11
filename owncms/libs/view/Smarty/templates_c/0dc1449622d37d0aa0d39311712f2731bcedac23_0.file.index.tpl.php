<?php
/* Smarty version 3.1.33, created on 2018-09-27 14:52:40
  from 'D:\phpstudy\WWW\test.smarty.com\smarty\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5bac7e38527d58_68768511',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0dc1449622d37d0aa0d39311712f2731bcedac23' => 
    array (
      0 => 'D:\\phpstudy\\WWW\\test.smarty.com\\smarty\\templates\\index.tpl',
      1 => 1538031154,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5bac7e38527d58_68768511 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\phpstudy\\WWW\\test.smarty.com\\smarty\\plugins\\function.html_select_date.php','function'=>'smarty_function_html_select_date',),1=>array('file'=>'D:\\phpstudy\\WWW\\test.smarty.com\\smarty\\plugins\\function.html_select_time.php','function'=>'smarty_function_html_select_time',),2=>array('file'=>'D:\\phpstudy\\WWW\\test.smarty.com\\smarty\\plugins\\function.mailto.php','function'=>'smarty_function_mailto',),));
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
</head>
<body>
<?php echo smarty_function_html_select_date(array('start_year'=>'-10','end_year'=>'+10'),$_smarty_tpl);?>

<br>
<?php echo smarty_function_html_select_time(array('use_24_hours'=>false),$_smarty_tpl);?>

<?php echo smarty_function_mailto(array('address'=>"test@admin.com",'encode'=>"hex"),$_smarty_tpl);?>

<table>
  <tr bgcolor="#ff0000">
    <td> ....etc.... </td>
  </tr>
</table>
</body>
</html><?php }
}
