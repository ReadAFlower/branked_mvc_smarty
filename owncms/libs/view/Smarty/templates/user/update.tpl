{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
        <form action="" method="post" id="user_update">
            <input type="hidden" name="user_id" id="user_id" value="{$userRes.user_id}">
            <table class="right_menu_add">
                <tr>
                    <th class="add_title">用户名</th>
                    <td class="add_value">
                        <input type="text" name="user_name" value="{$userRes.user_name}">
                    </td>
                </tr>
                <tr>
                    <th class="add_title">所属行业</th>
                    <td class="add_value">
                        <select name="type_num">
                            {foreach from=$industryList key=i item=list}
                                <option value="{$list.type_num}" {if $list.type_num==$userRes.type_num}selected{/if}>{$list.type_name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="add_title">用户域名</th>
                    <td class="add_value">
                        <input type="text" name="url_name" value="{if isset($userRes.url_name) && !empty($userRes.url_name)}{$userRes.url_name}{/if}">
                    </td>
                </tr>
                {if isset($userRes.level)}<tr>
                    <th class="add_title">用户级别</th>
                    <td class="add_value">
                        <select name="level">
                            {foreach from=$userLevel key=i item=list}
                                <option value="{$list@key}" {if $list==$userRes.level}selected{/if}>{$list}</option>
                            {/foreach}
                        </select>
                    </td>
                    </tr>
                {/if}
                <tr>
                    <th class="add_title">用户状态</th>
                    <td class="add_value">
                        <select name="status">
                            <option value="1" {if $userRes.status=='停用'}selected{/if}>停用</option>
                            <option value="2" {if $userRes.status=='启用'}selected{/if}>启用</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="add_title">密码</th>
                    <td class="add_value">
                        <input type="text" name="password" value=""><span>*若不修改密码请留空</span>
                    </td>
                </tr>
                <tr>
                    <th class="add_title">邮箱</th>
                    <td class="add_value"><input type="text" name="email" value="{$userRes.email}"></td>
                </tr>
                <tr>
                    <th class="add_title">手机号码</th>
                    <td class="add_value"><input type="text" name="phone" value="{$userRes.phone}"></td>
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