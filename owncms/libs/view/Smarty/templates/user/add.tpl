{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
        <form action="" method="post" id="user_add">
            <table class="right_menu_add">
                <tr>
                    <th class="add_title">用户名</th>
                    <td class="add_value">
                        <input type="text" name="user_name" id="user_add_name" value="" placeholder="请输入用户名">
                    </td>
                </tr>
                <tr>
                    <th class="add_title">所属行业</th>
                    <td class="add_value">
                        <select name="type_num" id="user_type">
                            {foreach from=$industryList key=i item=list}
                                <option value="{$list.type_num}">{$list.type_name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="add_title">用户级别</th>
                    <td class="add_value">
                        <select name="level" id="user_level">
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
                        <input type="text" name="password" id="user_password" value="" placeholder="请输入用户查询密码"><span>*必填</span>
                    </td>
                </tr>
                <tr>
                    <th class="add_title">邮箱</th>
                    <td class="add_value"><input type="text" name="email" id="user_email" value="" placeholder="请输入用户邮箱"><span>*必填</span></td>
                </tr>
                <tr>
                    <th class="add_title">手机号码</th>
                    <td class="add_value"><input type="text" name="phone" id="user_phone" value="" placeholder="请输入用户手机号码"><span>*必填</span></td>
                </tr>

            </table>
            <div class="form_submit">
                <input type="reset" name="doreset" class="doreset" value="重置">
                <input type="submit" name="dosubmit" id="userAddbtn" class="dosubmit" value="提交">
            </div>
        </form>
    </div>

</div>

{include '../common/footer.tpl'}