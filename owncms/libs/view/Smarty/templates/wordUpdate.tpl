{if isset($wordStatusRes) && !empty($wordStatusRes)}
    <span class="right_res">{$wordStatusRes}</span>
{/if}
{if isset($wordRes) && !empty($wordRes)}
    <form action="" method="post" id="word_update">
        <input type="hidden" name="word_id" value="{$wordRes.word_id}">
        <table class="right_menu_add">
            <tr>
                <th class="add_title">关键词</th>
                <td class="add_value">
                    <input type="text" name="word_name" value="{$wordRes.word_name}" disabled>
                </td>
            </tr>
            <tr>
                <th class="add_title">监控状态</th>
                <td class="add_value">
                    <select name="word_status">
                        {foreach from=$statusRes  key=i  item=$list}
                        <option value="{$list@key}" {if $wordRes.word_status==$list}selected{/if}>{$list}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
        </table>
        <div class="form_submit">
            <input type="reset" name="doreset" class="doreset" value="重置">
            <input type="submit" name="dosubmit" class="dosubmit" value="提交">
        </div>
    </form>
{/if}