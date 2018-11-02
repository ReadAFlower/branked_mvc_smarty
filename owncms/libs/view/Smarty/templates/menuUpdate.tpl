{if isset($menuUpdateRes) && !empty($menuUpdateRes)}
    <span class="right_res">{$menuUpdateRes}</span>
{/if}

{if isset($updateDate) && !empty($updateDate)}
<form action="" method="post" id="menu_update">
    <input type="hidden" name="update_id" value="{$userID}">
    <table class="right_menu_update">
        <tr>
            <th class="update_title">上级菜单名</th>
            <td class="update_value">
                <select name="parentID">
                    <option value="0">一级菜单</option>
                    {if isset($menuList) && !empty($menuList)}
                        {foreach from=$menuList key=i item=list}
                            {if $list.parentID==0}
                                <option value="{$list.id}" {if $updateDate.parentName==$list.zh_name}selected{/if}>{$list.zh_name}</option>
                                {foreach from=$menuList key=j item=list2}
                                    {if $list2.parentID==$list.id}
                                        <option value="{$list2.id}" {if $list2.zh_name==$updateDate.parentName}selected{/if}>└{$list2.zh_name}</option>
                                    {/if}
                                {/foreach}
                            {/if}
                        {/foreach}
                    {/if}
                </select>
            </td>
        </tr>
        <tr>
            <th class="update_title">菜单中文名称</th>
            <td class="update_value"><input type="text" name="zh_name" value="{$updateDate.zh_name}"></td>
        </tr>
        <tr>
            <th class="update_title">菜单英文名称</th>
            <td class="update_value"><input type="text" name="cn_name" value="{$updateDate.cn_name}"></td>
        </tr>
        <tr>
            <th class="update_title">权限等级</th>
            <td class="update_value">
                <select name="level">
                    <option value=""></option>

                    {if isset($allLevel) && !empty($allLevel)}
                        {foreach $allLevel as $value}
                            {if $smarty.session.{'level'|cat:""|cat:$smarty.session.haship}<=$value@key-1}
                                <option value="{$value@key}" {if $updateDate.level==$value}selected{/if}>{$value}</option>
                            {/if}
                        {/foreach}
                    {/if}
                </select>
            </td>
        </tr>
        <tr>
            <th class="update_title">模块名称</th>
            <td class="update_value"><input type="text" name="m" value="{$updateDate.m}"></td>
        </tr>
        <tr>
            <th class="update_title">控制器名称</th>
            <td class="update_value"><input type="text" name="c" value="{$updateDate.c}" ></td>
        </tr>
        <tr>
            <th class="update_title">方法名称</th>
            <td class="update_value"><input type="text" name="e" value="{$updateDate.e}"></td>
        </tr>
        <tr>
            <th class="update_title">附加参数</th>
            <td class="update_value"><input type="text" name="data" value="{$updateDate.data}"></td>
        </tr>
        <tr>
            <th class="update_title">是否在菜单栏显示</th>
            <td class="update_value">
                <select name="ismenu">
                    <option value="1" {if $updateDate.ismenu=='是'}selected{/if}>是</option>
                    <option value="2" {if $updateDate.ismenu=='否'}selected{/if}>否</option>
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