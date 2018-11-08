$(document).ready(function () {
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
                var preg = "/^[a-zA-Z0-9\u4E00-\u9FA5]{"+minL+","+maxL+"}$/";
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

    //word add
    var uwordsName = null;
    $("#u_words_name").blur(function(){
        if(inputCheck('u_words_name','words',2,30)){
            uwordsName=1;
        }else{
            uwordsName=0;
        }
    });
    //提交验证
    $("#u_wordsAddbtn").bind("click",function(event){
        if(uwordsName){
            return true;
        }else{
            alert('请输入正确的信息');
            return false;
        }
    })
})