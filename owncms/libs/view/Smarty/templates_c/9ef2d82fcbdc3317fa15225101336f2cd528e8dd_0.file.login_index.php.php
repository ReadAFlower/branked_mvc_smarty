<?php
/* Smarty version 3.1.33, created on 2018-10-17 16:12:31
  from 'D:\phpstudy\WWW\mvc.branked.com\owncms\libs\view\Smarty\templates\login_index.php' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5bc6eeef9acbb5_67984581',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9ef2d82fcbdc3317fa15225101336f2cd528e8dd' => 
    array (
      0 => 'D:\\phpstudy\\WWW\\mvc.branked.com\\owncms\\libs\\view\\Smarty\\templates\\login_index.php',
      1 => 1539763446,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:menuList.php' => 1,
    'file:menuAdd.php' => 1,
  ),
),false)) {
function content_5bc6eeef9acbb5_67984581 (Smarty_Internal_Template $_smarty_tpl) {
?><html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>后台首页</title>
    <link rel="stylesheet" type="text/css" href="/style/icfont/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/style/css/admin.css">
    <?php echo '<script'; ?>
 type="text/javascript" src="/style/js/jquery-1.7.2.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="/style/js/admin.js"><?php echo '</script'; ?>
>

</head>
<body>
<!--<?php echo (('123').('')).('456');?>

<?php echo var_dump($_SESSION[(('menu').('')).($_SESSION['haship'])]);?>

<?php echo var_dump($_SESSION);?>
-->
    <header class="login_header">
        <div class="header_left"></div>
        <div class="header_right">
            <ul>
                <li><a href="/index.php?m=<?php echo $_SESSION[(('m').('')).($_SESSION['haship'])];?>
&c=<?php echo $_SESSION[(('c').('')).($_SESSION['haship'])];?>
&e=index">系统首页</a></li>
                <li><a href="/index.php?m=<?php echo $_SESSION[(('m').('')).($_SESSION['haship'])];?>
&c=<?php echo $_SESSION[(('c').('')).($_SESSION['haship'])];?>
&e=loginOut">安全退出</a></li>
            </ul>
        </div>
    </header>
    <div class="login_content">
        <div class="login_left">
            <ul class="login_nav">
                <li><span class="login_nav_active"><a href="/index.php?m=menu&c=menu&e=menuList">菜单管理</a><i class="icon iconfont icon-you"></i></span>
                    <ul class="login_child_nav">
                        <li><span ><a href="/index.php?m=menu&c=menu&e=menuList">菜单列表</a></span></li>
                        <li><span><a href="/index.php?m=menu&c=menu&e=menuAdd">添加菜单</a></span></li>
                    </ul>
                </li>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_SESSION[(('menu').('')).($_SESSION['haship'])], 'list', false, 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value => $_smarty_tpl->tpl_vars['list']->value) {
?>
                    <?php if ($_smarty_tpl->tpl_vars['list']->value['parentID'] == 0) {?>
                <li><span <?php if ($_smarty_tpl->tpl_vars['list']->value['e'] == $_GET['e']) {?>class="login_nav_active"<?php }?>><a href="/index.php?m=<?php echo $_smarty_tpl->tpl_vars['list']->value['m'];?>
&c=<?php echo $_smarty_tpl->tpl_vars['list']->value['c'];?>
&e=<?php echo $_smarty_tpl->tpl_vars['list']->value['e'];
echo $_smarty_tpl->tpl_vars['list']->value['data'];?>
"><?php echo $_smarty_tpl->tpl_vars['list']->value['zh_name'];?>
</a><i class="icon iconfont <?php if ($_smarty_tpl->tpl_vars['list']->value['m'] == $_GET['m']) {?>icon-zhankai<?php } else { ?>icon-you<?php }?>"></i></span>
                    <ul class="login_child_nav">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_SESSION[(('menu').('')).($_SESSION['haship'])], 'list2', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['list2']->value) {
?>
                        <?php if ($_smarty_tpl->tpl_vars['list2']->value['parentID'] == $_smarty_tpl->tpl_vars['list']->value['id']) {?>
                        <li><span <?php if ($_smarty_tpl->tpl_vars['list2']->value['e'] == $_GET['e']) {?>class="login_nav_active"<?php }?>><a href="/index.php?m=<?php echo $_smarty_tpl->tpl_vars['list2']->value['m'];?>
&c=<?php echo $_smarty_tpl->tpl_vars['list2']->value['c'];?>
&e=<?php echo $_smarty_tpl->tpl_vars['list2']->value['e'];
echo $_smarty_tpl->tpl_vars['list2']->value['data'];?>
"><?php echo $_smarty_tpl->tpl_vars['list2']->value['zh_name'];?>
</a></span></li>
                        <?php }?>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </ul>
                </li>
                    <?php }?>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <li><span><a href="#">管理员管理</a><i class="icon iconfont icon-you"></i></span>
                    <ul class="login_child_nav">
                        <li><span><a href="#">管理员列表</a></span></li>
                        <li><span><a href="#">添加管理员</a></span></li>
                    </ul>
                </li>
                <li><span><a href="#">用户管理</a><i class="icon iconfont icon-you"></i></span>
                    <ul class="login_child_nav">
                        <li><span><a href="#">管理员列表</a></span></li>
                        <li><span><a href="#">添加管理员</a></span></li>
                    </ul>
                </li>
                <li><span><a href="#">关键词管理</a><i class="icon iconfont icon-you"></i></span></li>
            </ul>
        </div>
        <div class="login_right">


            <div class="login_right_header"></div>
            <?php if ((($_SESSION[(('level').('')).($_SESSION['haship'])] !== null )) && $_GET['e'] == 'menuList') {?>
                <?php $_smarty_tpl->_subTemplateRender('file:menuList.php', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php } elseif ((($_SESSION[(('level').('')).($_SESSION['haship'])] !== null )) && $_GET['e'] == 'menuAdd') {?>
                <?php $_smarty_tpl->_subTemplateRender('file:menuAdd.php', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php }?>
            <!--
            <form action="" method="post" id="act_form">
                <input type="hidden" name="" value="">
                <table class="data_act" border="1" cellpadding="0" cellspacing="0">
                    <tr class="table_h">
                        <th class="data_act_ord">序号</th>
                        <th class="data_act_key">内容项</th>
                        <th class="data_act_val">内容值</th>
                    </tr>
                    <tr>
                        <th class="ord">1</th>
                        <td>用户名</td>
                        <td><input type="text" name="" id="" placeholder=""></td>
                    </tr>
                    <tr>
                        <th class="ord">2</th>
                        <td>用户名</td>
                        <td><input type="text" name="" id="" placeholder=""></td>
                    </tr>

                </table>
                <div class="form_submit">
                    <input type="reset" name="doreset" class="doreset" value="重置">
                    <input type="submit" name="dosubmit" class="dosubmit" value="提交">
                </div>
            </form>-->

            <!--
            <div class="list_pages">
                <span>共X条记录/X页</span>
                <span><a href="#">首页</a></span>
                <span><a href="#">上一页</a></span>
                <span><a href="#">1</a> </span>
                <span class="cur">2</span>
                <span><a href="#">3</a> </span>
                <span><a href="#">4</a> </span>
                <span><a href="#">5</a> </span>
                <span><a href="#">6</a> </span>
                <span><a href="#">7</a> </span>
                <span><a href="#">8</a> </span>
                <span><a href="#">9</a> </span>
                <span><a href="#">10</a> </span>
                <span><a href="#">下一页</a></span>
                <span><a href="#" >末页</a></span>
            </div>-->

        </div>
    </div>
</body>
</html><?php }
}
