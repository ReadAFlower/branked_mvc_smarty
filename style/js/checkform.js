$(document).ready(function () {
    function checkFrom(id,type){
        //用户名验证
        if(type=='username'){
            var preg=/^[a-zA-Z][0-9a-zA-Z]{5,12}$/;
            if($("#"+id).val().match(preg)){
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                return true;
            }else{
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                $("#"+id).after('<span class="color">*请输入正确的用户名,用户名以字母开头,由6到12位字符组成</span>');
                return false;
            }
        }

        //手机号码验证
        if(type=='phone'){
            var preg=/^1(3|5|7|8)[0-9]{9}$/;
            if($("#"+id).val().match(preg)){
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                return true;
            }else{
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                $("#"+id).after('<span class="color">*请输入正确的手机号码，示例格式：13456789231</span>');
                return false;
            }
        }

        //电话号码验证
        if(type=='tel'){
            var preg=/^0[0-9]{2,3}-[0-9]{7,8}$/;
            if($("#"+id).val().match(preg)){
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                return true;
            }else{
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                $("#"+id).after('<span class="color">*请输入正确的电话号码，实例：020-1234567</span>');
                return false;
            }
        }

        //邮箱验证
        if(type=='email'){
            var preg=/^[\w-]+(\.[\w-])*@[0-9a-zA-Z-]+\.[a-z]{2,}(\.[a-z]+)*$/;
            console.log($("#"+id).val().match(preg));
            if($("#"+id).val().match(preg)){
               // $("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                return true;
            }else{
               // $("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                $("#"+id).after('<span class="color">*请输入正确的电子邮箱</span>');
                return false;
            }
        }

        //验证码
        if(type=='code'){
            var preg=/^[0-9a-zA-Z]{6}$/;
            if($("#"+id).val().match(preg)){
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                return true;
            }else{
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                $("#"+id).next("img").after('<span class="color">*请输入正确的6位验证码，由数字字母组成</span>');
                return false;
            }
        }

        //密码
        if(type=='password'){
            var preg=/^[0-9a-zA-Z]{6,16}$/;
            if($("#"+id).val().match(preg)){
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                return true;
            }else{
                //$("."+id+" .color").remove();
                $("#"+id).siblings(".color").remove();
                $("#"+id).after('<span class="color">*请输入正确的密码，由6到12位数字字母组成</span>');
                return false;
            }
        }

    }

    var userName=null;
    var password=null;
    var code=null;
    $("#uname").blur(function(){
        if(checkFrom('uname','username')){
            userName=1;
        }else{
            userName=0;
        }
    });

    $("#pwd").blur(function(){
        if(checkFrom('pwd','password')){
            password=1;
        }else{
            password=0;
        }
    });

    $("#code").blur(function(){
        if(checkFrom('code','code')){
            code=1;
        }else{
            code=0;
        }
    });

    //提交验证 登录验证
    $("#dosubmit").bind("click",function(event){
        if(userName && password && code){
            return true;
        }else{
            alert('请输入正确的信息');
            return false;
        }
    })

    $('#typeAdmin').click(function () {
        $(window).attr('location','/index.php?m=admin&c=admin&e=login&dosubmit=admin');
    })
    $('#typeUser').click(function () {
        $(window).attr('location','/index.php?m=Personal&c=Personal&e=login&dosubmit=user');
    })

    /**
     * input输入校验
     * @param id input id
     * @param type
     * 1、chars 	纯字母
     * 2、varchars 	字母数字混合
     * 3、nums 		纯数字 Tnum 首位非0纯数字
     * 4、Fchars 	第一个是必须是字母
     * 5、Nchars 	第一个是数字 TNchars 首位是非零数字
     * 6、http 		域名
     * 7、data 		日期
     * 8、zn 		中文
     * 9、zncn		中英文
     * 10、zcn		中英文数字
     * @param length
     */
    function inputCheck(id,type,minL=6,maxL=30){

        switch (type){
            case 'chars':
                var preg = "/^[a-zA-Z]{"+minL+","+maxL+"}$/";
                console.log(eval(preg))
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'到'+maxL+'位字母的字符串</span>');
                    return false;
                }
                break;
            case 'varchars':
                var preg = "/^[a-zA-Z0-9]{"+minL+","+maxL+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'到'+maxL+'位字母/数字的字符串</span>');
                    return false;
                }
                break;
            case 'nums':
                var preg = "/^[0-9]{"+minL+","+maxL+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'到'+maxL+'位的数字串</span>');
                    return false;
                }
                break;
            case 'nums':
                var preg = "/^[1-9]{1}[0-9]{"+minL-1+","+maxL-1+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'到'+maxL+'位的数字串（首位不能为0）</span>');
                    return false;
                }
                break;
            case 'Fchars':
                var preg = "/^[a-zA-Z]{1}[a-zA-Z0-9]{"+minL-1+","+maxL-1+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'到'+maxL+'位字母/数字的字符串（第一位必须是字母）</span>');
                    return false;
                }
                break;
            case 'Nchars':
                var preg = "/^[0-9]{1}[a-zA-Z0-9]{"+minL-1+","+maxL-1+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'到'+maxL+'位字母/数字的字符串（第一位必须是数字）</span>');
                    return false;
                }
                break;
            case 'Nchars':
                var preg = "/^[1-9]{1}[a-zA-Z0-9]{"+minL-1+","+maxL-1+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'到'+maxL+'位字母/数字的字符串（第一位必须是非0数字）</span>');
                    return false;
                }
                break;
            case 'http':
                var preg = /^([0-9a-zA-Z]+\-?[0-9a-zA-Z]+.){1,3}([a-zA-Z\-]{2,}.)?([a-zA-Z\-]{2,})$/;
                if($("#"+id).val().match(preg)){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*域名格式不正确，请输入正确的域名</span>');
                    return false;
                }
                break;
            case 'date':
                var preg = /^([0-9]{4})[-/\. ](0[1-9]|1[0-2])[-/\. ](0[1-9]|[12][0-9]|3[01])$/;
                if($("#"+id).val().match(preg)){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*日期格式不正确，请输入正确的日期</span>');
                    return false;
                }
                break;
            case 'zn':
                var preg = "/^[\u4E00-\u9FA5]{"+minL+","+maxL+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'个以上中文</span>');
                    return false;
                }
                break;
            case 'zncn':
                var preg = "/^[a-zA-Z\u4E00-\u9FA5]{"+minL+","+maxL+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'个以上中文/字母字符串</span>');
                    return false;
                }
                break;
            case 'zcn':
                var preg = "/^[a-zA-Z0-9,\u4E00-\u9FA5]{"+minL+","+maxL+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*输入不正确，请输入'+minL+'个以上中文/字母/数字字符串</span>');
                    return false;
                }
                break;
            case 'words':
                var preg = "/^[a-zA-Z,\u4E00-\u9FA5]{"+minL+","+maxL+"}$/";
                if($("#"+id).val().match(eval(preg))){
                    $("#"+id).siblings(".color").remove();
                    return true;
                }else{
                    $("#"+id).siblings(".color").remove();
                    $("#"+id).after('<span class="color">*关键词输入不正确，请输入'+minL+'个以上中文或英文词，多个关键词以全角逗号 “,”隔开</span>');
                    return false;
                }
                break;
            default:break;
        }

    }


    //menu add
    var zh_name = null;
    var cn_name = null;
    var m = null;
    var c = null;
    var e = null;
    $("#zh_name").blur(function(){
        if(inputCheck('zh_name','zn',4,30)){
            zh_name=1;
        }else{
            zh_name=0;
        }
    });
    $("#cn_name").blur(function(){
        if(inputCheck('cn_name','chars',4,30)){
            cn_name=1;
        }else{
            cn_name=0;
        }
    });
    $("#m").blur(function(){
        if(inputCheck('m','chars',4,30)){
            m=1;
        }else{
            m=0;
        }
    });
    $("#m").blur(function(){
        if(inputCheck('m','chars',4,30)){
            c=1;
        }else{
            c=0;
        }
    });
    $("#m").blur(function(){
        if(inputCheck('m','chars',4,30)){
            e=1;
        }else{
            e=0;
        }
    });

    //提交验证
    $("#menuAddbtn").bind("click",function(event){
        if(zh_name && cn_name && m && c && e){
            return true;
        }else{
            alert('请输入正确的信息');
            return false;
        }
    })

    //industry add
    var typeName = null;
    var typeNum = null;
    $("#type_name").blur(function(){
        if(inputCheck('type_name','zn',2,30)){
            typeName=1;
        }else{
            typeName=0;
        }
    });
    $("#type_num").blur(function(){
        if(inputCheck('type_num','nums',4,12)){
            typeNum=1;
        }else{
            typeNum=0;
        }
    });
    //提交验证
    $("#typeAddbtn").bind("click",function(event){
        console.log(typeName);
        console.log(typeNum);
        if(typeName && typeNum){
            return true;
        }else{
            alert('请输入正确的信息');
            return false;
        }
    })

    //manager Add
    var mgrName = null;
    var mgrPassword = null;
    var mgrEmail = null;
    var mgrPhone = null;
    $("#mgr_name").blur(function(){
        if(checkFrom('mgr_name','username')){
            mgrName=1;
        }else{
            mgrName=0;
        }
    });
    $("#mgr_password").blur(function(){
        if(checkFrom('mgr_password','password')){
            mgrPassword=1;
        }else{
            mgrPassword=0;
        }
    });
    $("#mgr_email").blur(function(){
        if(checkFrom('mgr_email','email')){
            mgrEmail=1;
        }else{
            mgrEmail=0;
        }
    });
    $("#mgr_phone").blur(function(){
        if(checkFrom('mgr_phone','phone')){
            mgrPhone=1;
        }else{
            mgrPhone=0;
        }
    });
    //提交验证
    $("#mgrAddbtn").bind("click",function(event){
        if(mgrName && mgrPassword && mgrEmail && mgrPhone){
            if (!$("#mgr_Level").val()) {
                alert('请选择管理员级别');
                return false;
            }
            return true;
        }else{
            alert('请输入正确的信息');
            return false;
        }
    })

    //user Add
    var userAddName = null;
    var userPassword = null;
    var userEmail = null;
    var userPhone = null;
    $("#user_add_name").blur(function(){
        if(checkFrom('user_add_name','username')){
            userAddName=1;
        }else{
            userAddName=0;
        }
    });
    $("#user_password").blur(function(){
        if(checkFrom('user_password','password')){
            userPassword=1;
        }else{
            userPassword=0;
        }
    });
    $("#user_email").blur(function(){
        if(checkFrom('user_email','email')){
            userEmail=1;
        }else{
            userEmail=0;
        }
    });
    $("#user_phone").blur(function(){
        if(checkFrom('user_phone','phone')){
            userPhone=1;
        }else{
            userPhone=0;
        }
    });
    //提交验证
    $("#userAddbtn").bind("click",function(event){
        if(userAddName && userPassword && userEmail && userPhone){
            if (!$("#user_type").val()) {
                alert('请选用户所属行业类别');
                return false;
            }
            if (!$("#user_level").val()) {
                alert('请选择用户级别');
                return false;
            }
            return true;
        }else{
            alert('请输入正确的信息');
            return false;
        }
    })

    //url Add
    var urlName = null;
    $("#url_name").blur(function(){
        if(inputCheck('url_name','http')){
            urlName=1;
        }else{
            urlName=0;
        }
    });
    //提交验证
    $("#urlAddbtn").bind("click",function(event){
        if(urlName){
            return true;
        }else{
            alert('请输入正确的信息');
            return false;
        }
    })

    //word add
    var wordsName = null;
    $("#words_name").blur(function(){
        if(inputCheck('words_name','zcn',2,30)){
            wordsName=1;
        }else{
            wordsName=0;
        }
    });
    //提交验证
    $("#wordsAddbtn").bind("click",function(event){
        if(wordsName){
            return true;
        }else{
            alert('请输入正确的信息');
            return false;
        }
    })
})


