{if isset($userInfo)}
<table class="right_userInfo">
    <tr>
        <th class="utitle">用户名</th>
        <td class="uvalue">{$userInfo.user_name}</td>
        <th class="utitle"></th>
        <td class="uvalue"></td>
    </tr>
    <tr>
        <th class="utitle">用户级别</th>
        <td class="uvalue">{$userInfo.level}</td>
        <th class="utitle">用户状态</th>
        <td class="uvalue">{$userInfo.status}</td>
    </tr>
    <tr>
        <th class="utitle">联系电话</th>
        <td class="uvalue">{$userInfo.phone}</td>
        <th class="utitle">电子邮箱</th>
        <td class="uvalue">{$userInfo.email}</td>
    </tr>
    <tr>
        <th class="utitle">用户网站</th>
        <td class="uvalue">{$userInfo.url_name}</td>
        <th class="utitle">所属行业</th>
        <td class="uvalue">{$userInfo.type_name}</td>
    </tr>
    <tr>
        <th class="utitle">关键词数</th>
        <td class="uvalue">{$userInfo.word_num}</td>
        <th class="utitle">已有排名词数</th>
        <td class="uvalue">{$userInfo.word_branked_num}</td>
    </tr>
    <tr>
        <th class="utitle">上次登录时间</th>
        <td class="uvalue">{$userInfo.lastlogintime|date_format:"%Y-%m-%d"}</td>
        <th class="utitle">上次登录地点</th>
        <td class="uvalue">{$userInfo.lastloginip}</td>
    </tr>
</table>
{/if}