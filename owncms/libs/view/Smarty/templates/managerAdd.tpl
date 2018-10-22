{if isset($managerAddRes) && !empty($managerAddRes)}
    <span class="right_res">{$managerAddRes}</span>
{/if}
<form action="" method="post" id="manager_add">
    <table class="right_manager_add">
        <tr>
            <th class="add_title">管理员名</th>
            <td class="add_value">
                <input type="text" name="admin_name" id="admin_name" value="" placeholder="请输入管理员登录名"><span>*必填</span>
            </td>
        </tr>
        <tr>
            <th class="add_title">管理员密码</th>
            <td class="add_value"><input type="text" name="password" value="" placeholder="请输入管理员登录密码"><span>*必填</span></td>
        </tr>
        <tr>
            <th class="add_title">邮箱</th>
            <td class="add_value"><input type="text" name="email" value="" placeholder="请输入管理员邮箱"><span>*必填</span></td>
        </tr>
        <tr>
            <th class="add_title">手机号码</th>
            <td class="add_value">
                <input type="text" name="phone" id="phone" value="" placeholder="请输入管理员手机号码"><span>*必填</span>
            </td>
        </tr>
        <tr>
            <th class="add_title">模块名称</th>
            <td class="add_value"><input type="text" name="m" value="" placeholder="请输入模块名称"><span>*必填</span></td>
        </tr>

    </table>
    <div class="form_submit">
        <input type="reset" name="doreset" class="doreset" value="重置">
        <input type="submit" name="dosubmit" class="dosubmit" value="提交">
    </div>
</form>
