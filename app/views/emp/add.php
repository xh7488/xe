<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加雇员</title>
</head>
<img  src="/res/images/tu.jpg" width="50" height="50" />
<hr/>
<h1>添加用户</h1>
<form action="/emp/add" method="post">
<table>
<tr><td>名字</td><td><input type="text" name="name" /></td></tr>
<tr><td>级别</td><td><input type="text" name="grade" /></td></tr>
<tr><td>电邮</td><td><input type="text" name="email" /></td></tr>
<tr><td>薪水</td><td><input type="text" name="salary" /></td></tr>
<tr><td><input type="hidden" name="flag" value="addemp" /></td></tr>
<tr>
<td><input type="submit" value="添加用户" /></td>
<td><input type="reset" value="重新填写" /></td>
<td><a href="/">返回界面</a></td>
</tr>
</table>
</form>
</html>