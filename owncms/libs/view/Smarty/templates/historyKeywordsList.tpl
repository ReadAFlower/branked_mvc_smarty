{if isset($getHistoryBranked) && !empty($getHistoryBranked)}
    <span class="right_res">{$getHistoryBranked}</span>
{elseif isset($historyWordRes) && !empty($historyWordRes)}
    <!-- word list -->
    <div style="width: 96%;clear: both;margin: 0 auto;font-family: 'Adobe 楷体 Std R';text-align: center;">
        <h2 style="font-weight: normal;font-size: 14px;">{$wordBaseRes.user_name}下 <span style="color: #ff0000;text-decoration: underline;">{$wordBaseRes.word_name}</span> 历史排名数据</h2>

        <div style="width:96%;margin:5px 20px;"><span style="display: block;float: left;">默认显示一个月内有排名数据，最多保留半年内有排名数据</span><span span style="display: block;float: right;"><a href="index.php?m=history&c=history&e=historyBranked&smallTime=30">一个月内排名</a> &nbsp;|&nbsp; <a href="index.php?m=history&c=history&e=historyBranked&smallTime=90">三个月内排名</a> &nbsp;|&nbsp; <a href="index.php?m=history&c=history&e=historyBranked&smallTime=180">半年内排名</a></span></div>
    </div>
    <table border="1" cellpadding="0" cellspacing="0">
        <tr class="table_h">
            <th class="ord">序号</th>
            <th class="word_br">排名</th>
            <th class="upd">时间</th>
            <th class="do">操作</th>
        </tr>

        {foreach from=$historyWordRes key=i item=list}
            {if $list.word_id}
            <tr>
                <td>{$i+1}</td>

                <td>{if empty($list.word_branked)}暂无排名{else}{$list.word_branked}{/if}</td>
                <td>{$list.updated_at|date_format:"%Y-%m-%d"}</td>
                <td>

                </td>
            </tr>
            {/if}
        {/foreach}
    </table>
    <div class="list_pages">{$pagesNav}</div>
{/if}