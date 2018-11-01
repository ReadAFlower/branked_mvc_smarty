<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>后台首页</title>
    <link rel="stylesheet" type="text/css" href="/style/icfont/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/style/css/admin.css">
    <script type="text/javascript" src="/style/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/style/js/admin.js"></script>
    <script type="text/javascript" src="/style/js/branked.js"></script>
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
    <div class="login_content">
        <div class="login_left">
            <ul class="login_nav">

                <li>功能列表</li>
                <li><span {if $smarty.get.e=='index'}class="login_nav_active"{/if}><a href="/index.php?m=Personal&c=Personal&e=index">系统首页</a><i class="icon iconfont"></i></span></li>
                <li><span {if $smarty.get.e=='wordList'}class="login_nav_active"{/if}><a href="/index.php?m=Personal&c=Personal&e=wordList">关键词列表</a><i class="icon iconfont"></i></span></li>
                <li><span {if $smarty.get.e=='wordAdd'}class="login_nav_active"{/if}><a href="/index.php?m=Personal&c=Personal&e=addWords">添加关键词</a><i class="icon iconfont"></i></span></li>
                <li><span {if $smarty.get.e=='userUpdate'}class="login_nav_active"{/if}><a href="/index.php?m=Personal&c=Personal&e=userUpdate">信息修改</a><i class="icon iconfont"></i></span></li>


            </ul>
        </div>
        <div class="login_right">


            <div class="login_right_header"></div>
                {if  $smarty.get.e=='index'}
                    {include 'userInfo.tpl'}
                {elseif $smarty.get.e=='wordList' }
                    {include 'userWordList.tpl' }
                {elseif $smarty.get.e=='getHistory' }
                    {include 'userHistoryKeywordsList.tpl' }
                {elseif $smarty.get.e=='addWords' }
                    {include 'userWordAdd.tpl' }
                {elseif $smarty.get.e=='wordUpdate' }
                    {include 'wordUpdate.tpl' }
                {elseif $smarty.get.e=='userUpdate' }
                    {include 'userUpdate.tpl' }
                {/if}


            {if isset($pagesNav) && !empty($pagesNav)}<div class="list_pages">{$pagesNav}</div>{/if}

        </div>
    </div>
</body>
</html>