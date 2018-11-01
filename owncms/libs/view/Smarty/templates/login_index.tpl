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
                {if $smarty.session.{'level'|cat:""|cat:$smarty.session.haship}==0 && $smarty.session.{'adminid'|cat:""|cat:$smarty.session.haship}}
                <li><span {if 'menuIndex'==$smarty.get.e}class="login_nav_active"{/if}><a href="/index.php?m=menu&c=menu&e=menuIndex">菜单管理</a><i class="icon iconfont {if 'menu'==$smarty.get.m}icon-zhankai{else}icon-you{/if}"></i></span>
                    <ul class="login_child_nav" {if 'menu'==$smarty.get.m}style="display: block;"{else}icon-you{/if}>
                        <li><span {if 'menuList'==$smarty.get.e}class="login_nav_active"{/if}><a href="/index.php?m=menu&c=menu&e=menuList">菜单列表</a></span></li>
                        <li><span {if 'menuAdd'==$smarty.get.e}class="login_nav_active"{/if}><a href="/index.php?m=menu&c=menu&e=menuAdd">添加菜单</a></span></li>
                    </ul>
                </li>
                {/if}
                {foreach from=$smarty.session.{'menu'|cat:""|cat:$smarty.session.haship} key=i item=list}
                    {if $list.parentID==0}
                <li><span {if $list.e==$smarty.get.e}class="login_nav_active"{/if}><a href="/index.php?m={$list.m}&c={$list.c}&e={$list.e}{$list.data}">{$list.zh_name}</a><i class="icon iconfont {if $list.m==$smarty.get.m}icon-zhankai{else}icon-you{/if}"></i></span>
                    <ul class="login_child_nav" {if $list.m==$smarty.get.m}style="display: block;"{else}icon-you{/if}>
                        {foreach from=$smarty.session.{'menu'|cat:""|cat:$smarty.session.haship} key=k item=list2}
                        {if $list2.parentID==$list.id}
                        <li><span {if $list2.e==$smarty.get.e}class="login_nav_active"{/if}><a href="/index.php?m={$list2.m}&c={$list2.c}&e={$list2.e}{$list2.data}">{$list2.zh_name}</a></span></li>
                        {/if}
                        {/foreach}
                    </ul>
                </li>
                    {/if}
                {/foreach}

            </ul>
        </div>
        <div class="login_right">


            <div class="login_right_header"></div>
            {if isset($smarty.session.{'level'|cat:""|cat:$smarty.session.haship})}

                {if  $smarty.get.e=='menuList'}
                    {include 'menuList.tpl'}
                {elseif $smarty.get.e=='menuAdd'}
                    {include 'menuAdd.tpl'}
                {elseif $smarty.get.e=='industryList'}
                    {include 'industryList.tpl'}
                {elseif $smarty.get.e=='addIndustry' }
                    {include 'industryAdd.tpl' }
                {elseif $smarty.get.e=='industryUpdate' }
                    {include 'industryUpdate.tpl' }
                {elseif $smarty.get.e=='menuUpdate' }
                    {include 'menuUpdate.tpl' }
                {elseif $smarty.get.e=='managerList' }
                    {include 'managerList.tpl' }
                {elseif $smarty.get.e=='managerAdd' }
                    {include 'managerAdd.tpl' }
                {elseif $smarty.get.e=='managerUpdate' }
                    {include 'managerUpdate.tpl' }
                {elseif $smarty.get.e=='userList' }
                    {include 'userList.tpl' }
                {elseif $smarty.get.e=='userAdd' }
                    {include 'userAdd.tpl' }
                {elseif $smarty.get.e=='userUpdate' }
                    {include 'userUpdate.tpl' }
                {elseif $smarty.get.e=='addURL' }
                    {include 'urlAdd.tpl' }
                {elseif $smarty.get.e=='wordList' }
                    {include 'wordList.tpl' }
                {elseif $smarty.get.e=='wordsAdd' }
                    {include 'wordAdd.tpl' }
                {elseif $smarty.get.e=='wordUpdate' }
                    {include 'wordUpdate.tpl' }
                {elseif $smarty.get.e=='keywordsList' }
                    {include 'allKeywordsList.tpl' }
                {elseif $smarty.get.e=='historyBranked' }
                    {include 'historyKeywordsList.tpl' }
                {/if}


            {/if}



            {if isset($pagesNav) && !empty($pagesNav)}<div class="list_pages">{$pagesNav}</div>{/if}

        </div>
    </div>
</body>
</html>