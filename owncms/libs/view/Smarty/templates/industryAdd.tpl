{if isset($industryAddRes) && !empty($industryAddRes)}
    <span class="right_res">{$industryAddRes}</span>
{/if}
<form action="" method="post" id="menu_add">
    <table class="right_menu_add">
        <tr>
            <th class="add_title">行业名称</th>
            <td class="add_value"><input type="text" name="type_name" value="" placeholder="请输入行业名称"><span>*必填</span></td>
        </tr>
        <tr>
            <th class="add_title">行业编号</th>
            <td class="add_value"><input type="text" name="type_num" value="" placeholder="请输入行业名称"><span>*必填</span></td>
        </tr>

    </table>
    <div class="form_submit">
        <input type="reset" name="doreset" class="doreset" value="重置">
        <input type="submit" name="dosubmit" class="dosubmit" value="提交">
    </div>
</form>
