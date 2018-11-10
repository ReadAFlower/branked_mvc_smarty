{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
            <table border="1" cellpadding="0" cellspacing="0" class="managerList">
                <tr class="table_h">
                    <th class="ord">序号</th>
                    <th class="name">管理员名</th>
                    <th class="lev">级别</th>
                    <th class="status">激活状态</th>
                    <th class="crt">注册时间</th>
                    <th class="last_time">最后一次登录时间</th>
                    <th class="contact">联系方式</th>
                    <th class="do">管理员操作</th>
                </tr>
                {foreach from=$adminList key=i item=list}
                    <tr>
                        <td>{$i+1}</td>
                        <td>{$list.admin_name}</td>
                        <td>{$list.level}</td>
                        <td>{$list.status}</td>
                        <td>{$list.created_at|date_format:"%Y-%m-%d"}</td>
                        <td>{$list.lastlogintime|date_format:"%Y-%m-%d"}</td>
                        <td>{if !empty($list.phone)}{$list.phone}/{/if}{$list.email}</td>
                        <td><a href="/index.php?m=admin&c=admin&e=managerUpdate&id={$list.admin_id}">修改</a> | <a class="doDel" href="/index.php?m=admin&c=admin&e=managerDel&id={$list.admin_id}">删除</a></td>
                    </tr>
                {/foreach}
            </table>
            {if isset($pagesNav) && !empty($pagesNav)}<div class="list_pages">{$pagesNav}</div>{/if}
    </div>

</div>

{include '../common/footer.tpl'}