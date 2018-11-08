$(document).ready(function () {

    //左侧二级菜单栏
    $('.login_nav li .iconfont').each(function () {
        $(this).click(function () {
            // console.log($(this).hasClass('icon-you'));
            // console.log($(this).index());
            // console.log();
            if($(this).hasClass('icon-you')){
                $(this).removeClass('icon-you');
                $(this).addClass('icon-zhankai');
                $(this).parent().next('.login_child_nav').css('display','block');
            }else{
                $(this).removeClass('icon-zhankai');
                $(this).addClass('icon-you');
                $(this).parent().next('.login_child_nav').css('display','none');
            }
        })

    })

    //左侧菜单栏高亮
    $('.login_nav a').each(function () {
        $(this).click(function () {
            $('.login_nav a').parent().removeClass('login_nav_active');
            $(this).parent().addClass('login_nav_active');
        })
    })

     //分页导航高亮
    $('.list_pages span').each(function () {

        $(this).click(function () {
            var mark=$('.list_pages .cur').index();
            var n=$(this).index();
            //console.log(mark);
            var str=$('.list_pages span:eq('+n+') a').html()
            if(str){
                var preg=/^[1-9][0-9]*$/;
                if(str.match(preg)){

                    $('.list_pages .cur').html('<a href="#">'+parseInt($('.list_pages .cur').html())+'</a>')
                    $('.list_pages span').removeClass('cur');
                    $(this).addClass('cur');
                    $(this).html(str);
                }
            }else{
                return false;
            }
        })
    })

    //删除确认
    $('.doDel').each(function () {
        $(this).click(function () {
            if (confirm('此操作不可逆，是否确定执行')) {
                return true;
            }else {
                return false;
            }
        })
    })
})