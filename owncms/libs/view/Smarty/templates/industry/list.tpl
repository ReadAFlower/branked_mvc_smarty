{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
        <div class="industry_box">
            <table class="right_industry_list">
                <tr>
                    <th class="industry_id">ID</th>
                    <th class="industry_name">行业名</th>
                    <th class="industry_num">行业编号</th>
                    <th class="industry_do">管理操作</th>
                </tr>
                {if isset($industryList) && !empty($industryList)}
                    {foreach from=$industryList key=i item=list}
                        <tr>
                            <td>{$list.type_id}</td>
                            <td class="industry_name">{$list.type_name}</td>
                            <td>{$list.type_num}</td>
                            <td><a href="/index.php?m=industry&c=industry&e=industryUpdate&id={$list.type_id}">修改</a> | <a class="doDel" href="/index.php?m=industry&c=industry&e=industryDel&id={$list.type_id}">删除</a></td>
                        </tr>
                    {/foreach}
                {/if}
            </table>
        </div>
        {if isset($pagesNav) && !empty($pagesNav)}<div class="list_pages">{$pagesNav}</div>{/if}
    </div>

</div>

{include '../common/footer.tpl'}