{assign var="urlAdd" value='urlAdd'|cat:''|cat:$smarty.session.haship }
{if isset($smarty.session.{$urlAdd}) && !empty($smarty.session.{$urlAdd})}
    <span class="right_res">{$smarty.session.{$urlAdd}}</span>
    {myunset var=$urlAdd}
{/if}
{assign var="wordDelRes" value='wordDelRes'|cat:''|cat:$smarty.session.haship }
{if isset($smarty.session.{$wordDelRes}) && !empty($smarty.session.{$wordDelRes})}
    <span class="right_res">{$smarty.session.{$wordDelRes}}</span>
    {myunset var=$wordDelRes}
{/if}
{assign var="userDelRes" value='userDelRes'|cat:''|cat:$smarty.session.haship }
{if isset($smarty.session.{$userDelRes}) && !empty($smarty.session.{$userDelRes})}
    <span class="right_res">{$smarty.session.{$userDelRes}}</span>
    {myunset var=$userDelRes}
{/if}
{if isset($userListRes) && !empty($userListRes)}
    <span class="right_res">{$userListRes}</span>
{elseif isset($userList) && !empty($userList)}

<!-- user list -->
<div style="text-align: right;margin-right: 20px;"><a href="javascript:;" onclick="autoUpdate('reCount')">一键重新统计</a> </div>
<table border="1" cellpadding="0" cellspacing="0">
    <tr class="table_h">
        <th class="ord">序号</th>
        <th class="name">用户名</th>
        <th class="url">域名</th>
        <th class="lev">级别</th>
        <th class="use_word_num">关键词数</th>
        <th class="use_word_br_num">已有排名词数</th>
        <th class="crt">添加时间</th>
        <th class="do">用户操作</th>
    </tr>
    {foreach from=$userList key=i item=list}
    <tr>
        <td>{$i+1}</td>
        <td>{$list.user_name}</td>
        <td>{if isset($list.url_name) && !empty($list.url_name)}{$list.url_name}{else}<a style="text-decoration:underline; " href="/index.php?m=url&c=url&e=addURL&userID={{$list.user_id}}&userName={$list.user_name}">添加域名信息</a> {/if}</td>
        <td>{$list.level}</td>
        <td>{if isset($list.word_num) && !empty($list.word_num)}{$list.word_num}{else}0{/if}</td>
        <td>{if isset($list.word_branked_num) && !empty($list.word_branked_num)}{$list.word_branked_num}{else}0{/if}</td>
        <td>{$list.created_at|date_format:"%Y-%m-%d"}</td>
        <td>
            {if isset($list.url_name) && !empty($list.url_name)}
            <a class="reCount" href="javascript:;" onclick="reCount(this,{$list.user_id},5,6)">重新统计</a> |
            <a href="/index.php?m=keywords&c=keywords&e=wordList&userID={$list.user_id}">关键词列表</a> |
            <a href="/index.php?m=keywords&c=keywords&e=wordsAdd&userID={$list.user_id}">添加关键词</a> | {/if}
            <a href="/index.php?m=user&c=user&e=userUpdate&userID={$list.user_id}">修改</a> |
            <a class="doDel" href="/index.php?m=user&c=user&e=userDel&userID={$list.user_id}">删除</a> </td>
    </tr>
    {/foreach}

</table>
{/if}