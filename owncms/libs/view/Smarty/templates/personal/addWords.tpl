{include '../common/personal_header.tpl'}
<div class="login_content">

    {include '../common/personal_menu.tpl'}

    <div class="login_right">


        <div class="login_right_header"></div>
        <form action="" method="post" id="user_word_add">
            <table>
                <tr>
                    <th class="add_title">关键词</th>
                    <td class="add_value">
                        <input type="text" name="word_name" id="u_words_name" value="" placeholder="请输入添加关键词"><span>*多个关键词以全角逗号“ , ”隔开</span>
                    </td>
                </tr>

            </table>
            <div class="form_submit">
                <input type="reset" name="doreset" class="doreset" value="重置">
                <input type="submit" name="dosubmit" id="u_wordsAddbtn" class="dosubmit" value="提交">
            </div>
        </form>
    </div>
</div>
{include '../common/personal_footer.tpl'}