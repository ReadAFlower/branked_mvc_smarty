{if isset($wordAddRes) && !empty($wordAddRes)}
    <span class="right_res">{$wordAddRes}</span>
{/if}
{if isset($wordRes) && !empty($wordRes)}
    <form action="" method="post" id="word_update">
        <input type="hidden" name="url_id" value="{$wordRes.urlID}">
        <table class="right_menu_add">
            <tr>
                <th class="add_title">用户名</th>
                <td class="add_value">
                    <input type="text" name="user_name" value="{$wordRes.userName}" disabled>
                </td>
            </tr>
            <tr>
                <th class="add_title">关键词</th>
                <td class="add_value">
                    <input type="text" name="word_name" id="words_name" value="" placeholder="请输入添加关键词"><span>*多个关键词以全角逗号“ , ”隔开</span>
                </td>
            </tr>

        </table>
        <div class="form_submit">
            <input type="reset" name="doreset" class="doreset" value="重置">
            <input type="submit" name="dosubmit" id="wordsAddbtn" class="dosubmit" value="提交">
        </div>
    </form>
{/if}