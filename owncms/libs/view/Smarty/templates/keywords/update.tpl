{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
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
    </div>

</div>

{include '../common/footer.tpl'}