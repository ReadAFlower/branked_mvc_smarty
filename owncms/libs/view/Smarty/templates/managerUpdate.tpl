{if isset($managerUpdateRes) && !empty($managerUpdateRes)}
    <span class="right_res">{$managerUpdateRes}</span>
{/if}
{if isset($managerRes) && !empty($managerRes)}
<form action="" method="post" id="manager_update">
    <input type="hidden" name="admin_id" value="{$managerRes.admin_id}">
    <table class="right_manager_add">
        <tr>
            <th class="add_title">管理员名</th>
            <td class="add_value">
                <input type="text" name="admin_name" id="admin_name" value="{$managerRes.admin_name}" >
            </td>
        </tr>
        <tr>
            <th class="add_title">管理员密码</th>
            <td class="add_value"><input type="text" name="password" value=""><span>*不修改密码请置空</span></td>
        </tr>
        <tr>
            <th class="add_title">账号状态</th>
            <td class="add_value">
                <select name="status">
                    <option value="1" {if $managerRes.status=='停用'}selected{/if}>停用</option>
                    <option value="2" {if $managerRes.status=='启用'}selected{/if}>启用</option>
                </select>
            </td>
        </tr>
        <tr>
            <th class="add_title">管理员级别</th>
            <td class="add_value">
                <select name="level">
                    <option value=""></option>
                    {if isset($allLevel) && !empty($allLevel)}
                        {foreach $allLevel as $value}
                            {if $smarty.session.{'level'|cat:""|cat:$smarty.session.haship}<=$value@key}

                                <option value="{$value@key}" {if $managerRes.level==$value}selected{/if}>{$value}</option>
                            {/if}
                        {/foreach}
                    {/if}

                </select>
            </td>
        </tr>
        <tr>
            <th class="add_title">邮箱</th>
            <td class="add_value"><input type="text" name="email" value="{$managerRes.email}" ></td>
        </tr>
        <tr>
            <th class="add_title">手机号码</th>
            <td class="add_value">
                <input type="text" name="phone" id="phone" value="{$managerRes.phone}" >
            </td>
        </tr>

    </table>
    <div class="form_submit">
        <input type="reset" name="doreset" class="doreset" value="重置">
        <input type="submit" name="dosubmit" class="dosubmit" value="提交">
    </div>
</form>
{/if}
