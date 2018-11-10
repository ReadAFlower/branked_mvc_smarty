{if isset($menuAddRes) && !empty($menuAddRes)}
    <span class="right_res">{$menuAddRes}</span>
{/if}
<form action="" method="post" id="menu_add">
    <table class="right_menu_add">
        <tr>
            <th class="add_title">上级菜单</th>
            <td class="add_value">
                <select name="parentID" id="parentID">
                    <option value="0">作为一级菜单</option>
                    {if isset($menuList) && !empty($menuList)}
                        {foreach from=$smarty.session.{'menu'|cat:""|cat:$smarty.session.haship} key=i item=list}
                            {if $list.parentID==0}
                                <option value="{$list.id}" {if isset($smarty.get.id) && $smarty.get.id==$list.id}selected{/if}>{$list.zh_name}</option>
                                {foreach from=$smarty.session.{'menu'|cat:""|cat:$smarty.session.haship} key=j item=list2}
                                    {if $list2.parentID==$list.id}
                                        <option value="{$list2.id}" {if isset($smarty.get.id) && $smarty.get.id==$list2.id}selected{/if}>└{$list2.zh_name}</option>
                                    {/if}
                                {/foreach}
                            {/if}
                        {/foreach}
                    {/if}
                </select>
            </td>
        </tr>
        <tr>
            <th class="add_title">菜单中文名称</th>
            <td class="add_value"><input type="text" name="zh_name" id="zh_name" value="" placeholder="请输入菜单中文名称"><span>*必填</span></td>
        </tr>
        <tr>
            <th class="add_title">菜单英文名称</th>
            <td class="add_value"><input type="text" name="cn_name" id="cn_name" value="" placeholder="请输入菜单英文名称"><span>*必填</span></td>
        </tr>
        <tr>
            <th class="add_title">权限等级</th>
            <td class="add_value">
                <select name="level" id="level">
                    <option value=""></option>
                    {if isset($allLevel) && !empty($allLevel)}
                        {foreach $allLevel as $value}
                            {if $smarty.session.{'level'|cat:""|cat:$smarty.session.haship}<=$value@key}
                                <option value="{$value@key}" {if $smarty.session.{'level'|cat:""|cat:$smarty.session.haship}==$value@key}selected{/if}>{$value}</option>
                            {/if}
                        {/foreach}
                    {/if}
                </select>
            </td>
        </tr>
        <tr>
            <th class="add_title">模块名称</th>
            <td class="add_value"><input type="text" name="m" id="m" value="" placeholder="请输入模块名称"><span>*必填</span></td>
        </tr>
        <tr>
            <th class="add_title">控制器名称</th>
            <td class="add_value"><input type="text" name="c" id="c" value="" placeholder="请输入控制器名称"><span>*必填</span></td>
        </tr>
        <tr>
            <th class="add_title">方法名称</th>
            <td class="add_value"><input type="text" name="e" id="e" value="" placeholder="请输入方法名称"><span>*必填项</span></td>
        </tr>
        <tr>
            <th class="add_title">附加参数</th>
            <td class="add_value"><input type="text" name="data" id="data" value="" placeholder="请输入附加参数"></td>
        </tr>
        <tr>
            <th class="add_title">是否在菜单栏显示</th>
            <td class="add_value">
                <select name="ismenu" id="ismenu">
                    <option value="1" selected>是</option>
                    <option value="2">否</option>
                </select>
            </td>
        </tr>
    </table>
    <div class="form_submit">
        <input type="reset" name="doreset"  class="doreset" value="重置">
        <input type="submit" name="dosubmit" id="menuAddbtn" class="dosubmit" value="提交">
    </div>
</form>