{assign var="menuDelRes" value='menuDelRes'|cat:''|cat:$smarty.session.haship }
{if isset($smarty.session.{$menuDelRes}) && !empty($smarty.session.{$menuDelRes})}
    <span class="right_res">{$smarty.session.{$menuDelRes}}</span>
    {myunset var=$menuDelRes}
{/if}
{assign var="menuUpdateRes" value='menuUpdateRes'|cat:''|cat:$smarty.session.haship }
{if isset($smarty.session.{$menuUpdateRes}) && !empty($smarty.session.{$menuUpdateRes})}
    <span class="right_res">{$smarty.session.{$menuUpdateRes}}</span>
    {myunset var=$menuUpdateRes}
{/if}
<div class="menu_box">
    <table class="right_menu_list">
        <tr>
            <th class="menu_id">ID</th>
            <th class="menu_zh_name">菜单名</th>
            <th class="menu_cn_name">菜单英文名</th>
            <th class="menu_do">管理操作</th>
        </tr>
        {if isset($menuList) && !empty($menuList)}
        {foreach from=$menuList key=i item=list}
        {if $list.parentID==0}
        <tr>
            <td>{$list.id}</td>
            <td class="menu_name">{$list.zh_name}</td>
            <td>{$list.cn_name}</td>
            <td><a href="/index.php?m=menu&c=menu&e=menuAdd&id={$list.id}">添加子菜单</a> | <a href="/index.php?m=menu&c=menu&e=menuUpdate&id={$list.id}">修改</a> | <a class="doDel" href="/index.php?m=menu&c=menu&e=menuDel&id={$list.id}">删除</a></td>
        </tr>
            {foreach from=$menuList key=j item=list2}
            {if $list2.parentID==$list.id}
        <tr>
            <td>{$list2.id}</td>
            <td class="menu_name">└─ {$list2.zh_name}</td>
            <td>{$list2.cn_name}</td>
            <td><a href="/index.php?m=menu&c=menu&e=menuAdd&id={$list2.id}">添加子菜单</a> | <a href="/index.php?m=menu&c=menu&e=menuUpdate&id={$list2.id}">修改</a> | <a class="doDel" href="/index.php?m=menu&c=menu&e=menuDel&id={$list2.id}">删除</a></td>
        </tr>
                {foreach from=$menuList key=k item=list3}
                    {if $list3.parentID==$list2.id}
        <tr>
            <td>{$list3.id}</td>
            <td class="menu_name"> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; └─ {$list3.zh_name}</td>
            <td>{$list2.cn_name}</td>
            <td><a href="/index.php?m=menu&c=menu&e=menuUpdate&id={$list3.id}">修改</a> | <a href="/index.php?m=menu&c=menu&e=menuDel&id={$list3.id}">删除</a></td>
        </tr>
                        {/if}
                {/foreach}
            {/if}
            {/foreach}
        {/if}
        {/foreach}
        {/if}
    </table>
</div>