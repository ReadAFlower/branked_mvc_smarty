{if isset($industryListRes) && !empty($industryListRes)}
    <span class="right_res">{$industryListRes}</span>
{else}
    {assign var="industryDelRes" value='industryDelRes'|cat:''|cat:$smarty.session.haship }
    {if isset($smarty.session.{$industryDelRes}) && !empty($smarty.session.{$industryDelRes})}
        <span class="right_res">{$smarty.session.{$industryDelRes}}</span>
        {myunset var=$industryDelRes}
    {/if}
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
            <td><a href="/index.php?m=industry&c=industry&e=industryUpdate&id={$list.type_id}">修改</a> | <a href="/index.php?m=industry&c=industry&e=industryDel&id={$list.type_id}">删除</a></td>
        </tr>
            {/foreach}
        {/if}
    </table>
</div>
{/if}