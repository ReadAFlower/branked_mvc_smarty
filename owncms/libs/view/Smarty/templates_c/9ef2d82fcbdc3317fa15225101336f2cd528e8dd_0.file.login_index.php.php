<?php
/* Smarty version 3.1.33, created on 2018-10-16 18:06:51
  from 'D:\phpstudy\WWW\mvc.branked.com\owncms\libs\view\Smarty\templates\login_index.php' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5bc5b83b881064_14992286',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9ef2d82fcbdc3317fa15225101336f2cd528e8dd' => 
    array (
      0 => 'D:\\phpstudy\\WWW\\mvc.branked.com\\owncms\\libs\\view\\Smarty\\templates\\login_index.php',
      1 => 1539684388,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5bc5b83b881064_14992286 (Smarty_Internal_Template $_smarty_tpl) {
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
    <header class="login_header">
        <div class="header_left"></div>
        <div class="header_right">
            <ul>
                <li><a href="/index.php?m=<?php echo $_smarty_tpl->tpl_vars['m']->value;?>
&c=<?php echo $_smarty_tpl->tpl_vars['c']->value;?>
&e=loginOut">安全退出</a></li>
                <li><a href="/index.php?m=<?php echo $_smarty_tpl->tpl_vars['m']->value;?>
&c=<?php echo $_smarty_tpl->tpl_vars['c']->value;?>
&e=loginOut">安全退出</a></li>
                <li><a href="/index.php?m=<?php echo $_smarty_tpl->tpl_vars['m']->value;?>
&c=<?php echo $_smarty_tpl->tpl_vars['c']->value;?>
&e=loginOut">安全退出</a></li>
            </ul>
        </div>
    </header>
    <div class="login_content">
        <div class="login_left">
            <ul class="login_nav">
                <li><span class="login_nav_active"><a href="#">管理员管理</a><i class="icon iconfont icon-you"></i></span>
                    <ul class="login_child_nav">
                        <li><span><a href="#">管理员列表</a></span></li>
                        <li><span><a href="#">添加管理员</a></span></li>
                    </ul>
                </li>
                <li><span><a href="#">用户管理</a><i class="icon iconfont icon-you"></i></span></li>
                <li><span><a href="#">关键词管理</a><i class="icon iconfont icon-you"></i></span></li>
            </ul>
        </div>
        <div class="login_right">
            <div class="login_right_header"></div>

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
