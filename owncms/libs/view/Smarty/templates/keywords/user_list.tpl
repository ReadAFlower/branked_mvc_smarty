{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
        <div style="width: 96%;clear: both;margin: 0 auto;font-family: 'Adobe 楷体 Std R';text-align: center;">
            <h2 style="font-weight: normal;font-size: 14px;">{$userBaseRes.user_name}</h2>
            <div style="text-align: right;margin-right: 20px;"><a href="/index.php?m=keywords&c=keywords&e=wordsAdd&userID={$userBaseRes.user_id}">添加关键词</a> &nbsp;|&nbsp; <a href="javascript:;" onclick="autoUpdate('update_btn')">批量更新排名</a> &nbsp;|&nbsp; <a href="#">批量删除关键词</a></div>
        </div>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr class="table_h">
                <th class="ord">序号</th>
                <th class="word_keyword">关键词</th>
                <th class="word_br">排名</th>
                <th class="word_status">状态</th>
                <th class="upd">更新时间</th>
                <th class="do">操作</th>
            </tr>
            {foreach from=$wordRes key=i item=list}
                <tr>
                    <td>{$i+1}</td>
                    <td>{$list.word_name}</td>
                    <td>{if isset($list.word_branked) && !empty($list.word_branked)}{$list.word_branked}{else}暂无排名{/if}</td>
                    <td>{$list.word_status}</td>
                    <td>{$list.updated_at|date_format:"%Y-%m-%d"}</td>
                    <td>{if $list.word_status=='监控'}<a class="update_btn" href="javascript:;" onclick="updateBR(this,{$list.word_id},3,5)">更新排名</a> |{/if}<a href="/index.php?m=history&c=history&e=historyBranked&wordID={$list.word_id}&userID={$userBaseRes.user_id}">历史排名</a> | <a href="/index.php?m=keywords&c=keywords&e=wordUpdate&wordID={$list.word_id}">修改</a> | <a class="doDel" href="/index.php?m=keywords&c=keywords&e=wordDel&wordID={$list.word_id}&isBranked={if isset($list.word_branked) && !empty($list.word_branked)}1{else}2{/if}">删除</a></td>
                </tr>
            {/foreach}
        </table>
        {if isset($pagesNav) && !empty($pagesNav)}<div class="list_pages">{$pagesNav}</div>{/if}
    </div>

</div>

{include '../common/footer.tpl'}