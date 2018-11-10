{if !isset($menu)}
{assign var="menu" value=json_decode(file_get_contents(SMARTY_DIR|cat:''|cat:'cache/menu.txt'),true) }
{/if}
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
        {foreach from=$menu key=i item=list}
            {if $list.parentID==0 && $list.ismenu=='显示'}
                <li><span {if $list.e==$smarty.get.e}class="login_nav_active"{/if}><a href="/index.php?m={$list.m}&c={$list.c}&e={$list.e}{$list.data}">{$list.zh_name}</a><i class="icon iconfont {if $list.m==$smarty.get.m}icon-zhankai{else}icon-you{/if}"></i></span>
                    <ul class="login_child_nav" {if $list.m==$smarty.get.m}style="display: block;"{else}icon-you{/if}>
                        {foreach from=$menu key=k item=list2}
                            {if $list2.parentID==$list.id && $list2.ismenu=='显示'}
                                <li><span {if $list2.e==$smarty.get.e}class="login_nav_active"{/if}><a href="/index.php?m={$list2.m}&c={$list2.c}&e={$list2.e}{$list2.data}">{$list2.zh_name}</a></span></li>
                            {/if}
                        {/foreach}
                    </ul>
                </li>
            {/if}
        {/foreach}

    </ul>
</div>