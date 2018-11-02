$(function(){
			function checkFrom(id,type){
				//用户名验证
				if(type=='username'){
					var preg=/^[a-zA-Z][0-9a-zA-Z]{5,12}$/;
					if($("#"+id).val().match(preg)){
						$("."+id+" .color").remove();
						return true;
					}else{
						$("."+id+" .color").remove();
						$("#"+id).after('<span class="color">*请输入正确的用户名,用户名以字母开头,由6到12位字符组成</span>');
						return false;
					}
				}

				//手机号码验证
				if(type=='phone'){
					var preg=/^1(3|5|7|8)[0-9]{9}$/;
					if($("#"+id).val().match(preg)){
						$("."+id+" .color").remove();
						return true;
					}else{
						$("."+id+" .color").remove();
						$("#"+id).after('<span class="color">*请输入正确的手机号码，示例格式：13456789231</span>');
						return false;
					}
				}

				//电话号码验证
				if(type=='tel'){
					var preg=/^0[0-9]{2,3}-[0-9]{7,8}$/;
					if($("#"+id).val().match(preg)){
						$("."+id+" .color").remove();
						return true;
					}else{
						$("."+id+" .color").remove();
						$("#"+id).after('<span class="color">*请输入正确的电话号码，实例：020-1234567</span>');
						return false;
					}
				}

				//邮箱验证
				if(type=='email'){
					var preg=/^[\w-]+(\.[\w-])*@[0-9a-zA-Z-]+\.[a-z]{2,}(\.[a-z]+)*$/
					if($("#"+id).val().match(preg)){
						$("."+id+" .color").remove();
						return true;
					}else{
						$("."+id+" .color").remove();
						$("#"+id).after('<span class="color">*请输入正确的电子邮箱</span>');
						return false;
					}
				}

                //验证码
                if(type=='code'){
                    var preg=/^[0-9a-zA-Z]{6}$/;
                    if($("#"+id).val().match(preg)){
                        $("."+id+" .color").remove();
                        return true;
                    }else{
                        $("."+id+" .color").remove();
                        $("#"+id).next("img").after('<span class="color">*请输入正确的6位验证码，由数字字母组成</span>');
                        return false;
                    }
                }

                //密码
                if(type=='password'){
                    var preg=/^[0-9a-zA-Z]{6,16}$/;
                    if($("#"+id).val().match(preg)){
                        $("."+id+" .color").remove();
                        return true;
                    }else{
                        $("."+id+" .color").remove();
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

			//提交验证
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

})
