<div class="login_left">
    <ul class="login_nav">

        <li>功能列表</li>
        <li><span {if $smarty.get.e=='index'}class="login_nav_active"{/if}><a href="/index.php?m=Personal&c=Personal&e=index">系统首页</a><i class="icon iconfont"></i></span></li>
        <li><span {if $smarty.get.e=='wordList'}class="login_nav_active"{/if}><a href="/index.php?m=Personal&c=Personal&e=wordList">关键词列表</a><i class="icon iconfont"></i></span></li>
        <li><span {if $smarty.get.e=='addWords'}class="login_nav_active"{/if}><a href="/index.php?m=Personal&c=Personal&e=addWords">添加关键词</a><i class="icon iconfont"></i></span></li>
        <li><span {if $smarty.get.e=='userUpdate'}class="login_nav_active"{/if}><a href="/index.php?m=Personal&c=Personal&e=userUpdate">信息修改</a><i class="icon iconfont"></i></span></li>


    </ul>
</div>