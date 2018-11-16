{include '../common/header.tpl'}

<div class="login_content">

    {include '../common/left.tpl'}

    <div class="login_right">
        <div class="login_right_header"></div>
        {if $searchInfo}
        <div style="width: 96%;clear: both;margin: 0 auto;font-family: 'Adobe 楷体 Std R';text-align: center;">
            <form action="" method="post" id="baidu_search" class="res_form">
                <div class="search_input">
                    <input type="text" class="input_word" name="word" value="{$word}">
                    <input type="submit" name="doubmit" id="search_btn" class="dosubmit" value=" 搜&nbsp; &nbsp; 索 ">
                </div>
            </form>
        </div>

        <form action="/api.php?op=searchInfo&do=part" method="post" id="res_create" class="res_create">
            <input type="hidden" name="op" value="searchInfo">
            <input type="hidden" name="do" value="part">
            <div class="create_all">
                <a  href="/api.php?op=searchInfo&do=part&resID=all">全部导出</a>

                <input type="submit" id="part_file" name="part_file" value="导出EXECL">
            </div>
            <div class="res_create_tab">
                <table border="1" cellpadding="0" cellspacing="0" >
                    <tr class="table_h">
                        <th class="ord">序号</th>
                        <th class="name">用户名</th>
                        <th class="description">描述</th>
                        <th class="url">快照</th>
                        <th class="do">操作</th>
                    </tr>
                    {assign var="k" value=1}
                    {foreach from=$searchInfo key=i item=list}
                            <tr>
                                <td>{$k}</td>
                                <td>{$list.title}</td>
                                <td>{$list.description}</td>
                                <td>{$list.snapshot}</td>
                                <td>
                                    <input type="checkbox" name="resID[]" value="{$i}">
                                </td>
                            </tr>
                            {assign var="k" value=$k+1}
                    {/foreach}
                </table>
            </div>
        </form>
        {if isset($pagesNav) && !empty($pagesNav)}<div class="list_pages">{$pagesNav}</div>{/if}
        {/if}
    </div>

</div>
<script>
    $(document).ready(function () {

        $("#file_part").click(function () {
            var temp = $('input:checkbox:checked');
            var len = $('input:checkbox:checked').length;
            var resID = [];
            for(var i=0;i<len;i++){
                resID.push(temp[i].value);
            }
            console.log(resID);

            $.ajax({
                type:'GET',
                url:'/api.php?op=searchInfo&do=part',
                data:'infoID='+resID,
                dataType:'text',
                success:function (e) {
                    console.log(e);
                },
                error:function () {
                    alert('请求错误');
                }
            });
        })


    })

</script>
{include '../common/footer.tpl'}