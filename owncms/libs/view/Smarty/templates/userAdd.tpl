{if isset($userAddRes) && !empty($userAddRes)}
    <span class="right_res">{$userAddRes}</span>
{/if}
<form action="" method="post" id="menu_add">
    <table class="right_menu_add">
        <tr>
            <th class="add_title">用户名</th>
            <td class="add_value">
                <input type="text" name="user_name" value="" placeholder="请输入用户名">
            </td>
        </tr>
        <tr>
            <th class="add_title">所属行业</th>
            <td class="add_value">
                <select name="type_num">
                    {foreach from=$industryList key=i item=list}
                    <option value="{$list.type_num}">{$list.type_name}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th class="add_title">用户级别</th>
            <td class="add_value">
                <select name="level">
                    {var_dump($userLevel)}
                    {foreach from=$userLevel key=i item=list}
                    <option value="{$list@key}" {if $list@key==3}selected{/if}>{$list}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th class="add_title">密码</th>
            <td class="add_value">
                <input type="text" name="password" value="" placeholder="请输入用户查询密码"><span>*必填</span>
            </td>
        </tr>
        <tr>
            <th class="add_title">邮箱</th>
            <td class="add_value"><input type="text" name="email" value="" placeholder="请输入用户邮箱"><span>*必填</span></td>
        </tr>
        <tr>
            <th class="add_title">手机号码</th>
            <td class="add_value"><input type="text" name="phone" value="" placeholder="请输入用户手机号码"><span>*必填</span></td>
        </tr>

    </table>
    <div class="form_submit">
        <input type="reset" name="doreset" class="doreset" value="重置">
        <input type="submit" name="dosubmit" class="dosubmit" value="提交">
    </div>
</form>
