{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
        <form action="" method="post" id="industry_add">
            <table class="right_menu_add">
                <tr>
                    <th class="add_title">行业名称</th>
                    <td class="add_value"><input type="text" name="type_name" id="type_name" value="" placeholder="请输入行业名称"><span>*必填</span></td>
                </tr>
                <tr>
                    <th class="add_title">行业编号</th>
                    <td class="add_value"><input type="text" name="type_num" id="type_num" value="" placeholder="请输入行业编号"><span>*必填</span></td>
                </tr>

            </table>
            <div class="form_submit">
                <input type="reset" name="doreset" class="doreset" value="重置">
                <input type="submit" name="dosubmit" id="typeAddbtn" class="dosubmit" value="提交">
            </div>
        </form>
    </div>

</div>

{include '../common/footer.tpl'}