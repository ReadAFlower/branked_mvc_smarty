{if isset($getAllKeywordsRes) && !empty($getAllKeywordsRes)}
    <span class="right_res">{$getAllKeywordsRes}</span>
{elseif isset($allKeyWords) && !empty($allKeyWords)}
    <!-- word list -->
    <table border="1" cellpadding="0" cellspacing="0">
        <tr class="table_h">
            <th class="ord">序号</th>
            <th class="name">用户名</th>
            <th class="url">域名</th>
            <th class="word_keyword">关键词</th>
            <th class="word_br">排名</th>
            <th class="word_status">监控状态</th>
            <th class="upd">更新时间</th>
            <th class="do">操作</th>
        </tr>
        {assign var="k" value=1}
        {foreach from=$allKeyWords key=i item=list}
            {if $list.word_name}
            <tr>
                <td>{$k}</td>
                <td>{$list.user_name}</td>
                <td>{$list.url_name}</td>
                <td>{$list.word_name}</td>
                <td>{if empty($list.word_branked)}暂无排名{else}{$list.word_branked}{/if}</td>
                <td>{$list.word_status}</td>
                <td>{$list.updated_at|date_format:"%Y-%m-%d"}</td>
                <td>
                    {if $list.word_status=='监控'}<a href="javascript:;" onclick="updateBR(this,{$list.word_id},5,7)">更新排名</a> |{/if}
                    <a href="/index.php?m=keywords&c=keywords&e=wordUpdate&wordID={$list.word_id}">修改</a> |
                    <a href="/index.php?m=keywords&c=keywords&e=wordDel&wordID={$list.word_id}&isBranked={if isset($list.word_branked) && !empty($list.word_branked)}1{else}2{/if}">删除</a> |
                    <a href="/index.php?m=history&c=history&e=historyBranked&wordID={$list.word_id}&userID={$list.user_id}">历史排名</a>
                </td>
            </tr>
                {assign var="k" value=$k+1}
            {/if}
        {/foreach}
    </table>

{/if}