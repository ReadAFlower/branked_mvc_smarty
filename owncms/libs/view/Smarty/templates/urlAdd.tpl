{if isset($urlAddRes) && !empty($urlAddRes)}
    <span class="right_res">{$urlAddRes}</span>
{/if}
{if isset($userRes) && !empty($userRes)}
<form action="" method="post" id="url_add">
    <input type="hidden" name="user_id" value="{$userRes.userID}">
    <table class="right_menu_add">
        <tr>
            <th class="add_title">用户名</th>
            <td class="add_value">
                <input type="text" name="user_name" value="{$userRes.userName}" disabled>
            </td>
        </tr>

        <tr>
            <th class="add_title">用户域名</th>
            <td class="add_value">
                <input type="text" name="url_name" id="url_name" value="" placeholder="请输入用户域名"><span>*示例www.test.com</span>
            </td>
        </tr>

    </table>
    <div class="form_submit">
        <input type="reset" name="doreset" class="doreset" value="重置">
        <input type="submit" name="dosubmit" id="urlAddbtn" class="dosubmit" value="提交">
    </div>
</form>
{/if}