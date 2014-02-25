<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<img src="/res/images/tu.jpg" width="50" height="50" />
<hr />
<h1>雇员管理系统</h1>
<form action="/login" method="post" id="signupForm">
	<table>
	<?php if(!empty($errors)):?>
		<tr>
			<td>出错了:</td>
			<td>
			<?php foreach($errors as $error):?>
			<p><?=$error?></p>
			<?php endforeach;?>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<td>用户</td>
			<td><input type="text" name="name" id="name" /></td>
		</tr>
		<tr>
			<td>密&nbsp;码</td>
			<td><input type="password" id="password" name="password" /></td>
		</tr>
		<tr>
			<td colspan="2">是否保存用户id<input type="checkbox" value="kep"
				name="keep" /></td>
		
		
		<tr>
			<td><input type="submit" value="用户登录" /></td>
			<td><input type="reset" value="重新填写" /></td>
		</tr>
	</table>
</form>
<script src="/res/js/lib/jquery.js"></script>
<script src="/res/js/jquery.validate.js"></script>
<script type="text/javascript">
$().ready(function() {
	$("#signupForm").validate({
		rules: {
			name: {
				required: true,
				minlength: 5
			},
			password: {
				required: true,
				minlength: 6
			}
		},
		messages: {
			name: {
				required: "请输入登录名",
				minlength: "登录名至少6个字符"
			},
			password: {
				required: "请输入密码",
				minlength: "密码至少6个字符"
			}
		}
	});
	
});
</script>
</html>
