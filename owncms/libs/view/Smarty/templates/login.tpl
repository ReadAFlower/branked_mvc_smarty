<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/style/icfont/iconfont.css">
	<script type="text/javascript" src="/style/js/jquery-1.7.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/style/css/admin.css">

</head>
<body style=" background: url('/style/images/login_bg.jpg') no-repeat;background-size: 100%;">
	<div class="login_box">
        <h1>后 台 管 理</h1>
        <p>&nbsp;</p>
		<form action="/index.php?m={$m}&c={$c}&e=login&dosubmit={$loginType}" method="post" id="login_form">
			<input type="hidden" name="login_type" value="{$loginType}">
			<div class="login_input uname">
				<i class="icon iconfont icon-icon"></i>
				<input type="text" name="uname" id="uname" placeholder="用户名">
			</div>
			<div class="login_input pwd">
				<i class="icon iconfont icon-mima"></i>
				<input type="password" name="pwd" id="pwd" placeholder="密码">
			</div>
			<div class="login_input code">

                <i class="icon iconfont icon-securityCode-b"></i>
                <input type="text" name="code" id="code" placeholder="验证码">

                <img class="code_img" src="/api.php?op=codeImg" onclick="this.src='/api.php?op=codeImg&rand='+Math.random();">

			</div>
			<div class="login_input type">
				<label>
					<span><input type="radio" id="typeAdmin" name="login_type" value="admin" {if $loginType=='admin'}checked{/if}>管 理 员</span>
					<span><input type="radio" id="typeUser" name="login_type" value="user" {if $loginType=='user'}checked{/if}>普通用户</span>
				</label>
			</div>
			<div class="login_input submit">
				<input type="submit" name="submit" id="dosubmit" value=" 登 录 ">
			</div>
		</form>
	</div>
   <script type="text/javascript" src="/style/js/checkform.js"></script>

</body>
</html>