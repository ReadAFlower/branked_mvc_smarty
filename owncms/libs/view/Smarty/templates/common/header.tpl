<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>后台首页</title>
    <link rel="stylesheet" type="text/css" href="/style/icfont/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/style/css/admin.css">
    <script type="text/javascript" src="/style/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/style/js/admin.js"></script>
    <script type="text/javascript" src="/style/js/branked.js"></script>
    <script type="text/javascript" src="/style/js/checkform.js"></script>
</head>
<body>
    <header class="login_header">
        <div class="header_left"></div>
        <div class="header_right">
            <ul>
                <li><a href="/index.php?m={$smarty.session.{'m'|cat:''|cat:$smarty.session.haship}}&c={$smarty.session.{'c'|cat:''|cat:$smarty.session.haship}}&e=index">系统首页</a></li>
                <li><a href="/index.php?m={$smarty.session.{'m'|cat:''|cat:$smarty.session.haship}}&c={$smarty.session.{'c'|cat:''|cat:$smarty.session.haship}}&e=loginOut">安全退出</a></li>
            </ul>
        </div>
    </header>