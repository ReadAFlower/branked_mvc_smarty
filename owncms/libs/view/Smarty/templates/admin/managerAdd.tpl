{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
        <form action="" method="post" id="manager_add">
            <table class="right_manager_add">
                <tr>
                    <th class="add_title">管理员名</th>
                    <td class="add_value">
                        <input type="text" name="admin_name" id="mgr_name" value="" placeholder="请输入管理员登录名"><span>*必填</span>
                    </td>
                </tr>
                <tr>
                    <th class="add_title">管理员密码</th>
                    <td class="add_value"><input type="text" name="password" id="mgr_password" value="" placeholder="请输入管理员登录密码"><span>*必填</span></td>
                </tr>
                <tr>
                    <th class="add_title">管理员级别</th>
                    <td class="add_value">
                        <select name="level" id="mgr_Level">
                            <option value=""></option>
                            {if isset($allLevel) && !empty($allLevel)}
                                {foreach $allLevel as $value}
                                    {if $smarty.session.{'level'|cat:""|cat:$smarty.session.haship}<=$value@key}
                                        <option value="{$value@key}">{$value}</option>
                                    {/if}
                                {/foreach}
                            {/if}

                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="add_title">邮箱</th>
                    <td class="add_value"><input type="text" name="email" id="mgr_email" value="" placeholder="请输入管理员邮箱"><span>*必填</span></td>
                </tr>
                <tr>
                    <th class="add_title">手机号码</th>
                    <td class="add_value">
                        <input type="text" name="phone" id="mgr_phone" value="" placeholder="请输入管理员手机号码"><span>*必填</span>
                    </td>
                </tr>

            </table>
            <div class="form_submit">
                <input type="reset" name="doreset" class="doreset" value="重置">
                <input type="submit" name="dosubmit" id="mgrAddbtn" class="dosubmit" value="提交">
            </div>
        </form>

    </div>

</div>

{include '../common/footer.tpl'}