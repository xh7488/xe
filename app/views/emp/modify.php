<html>
<img  src="/res/images/tu.jpg" width="50" height="50" />
<hr/>
<h1>修改用户</h1>
<form action="/emp/modify" method="post">
<table>
<tr><td>id</td><td><input readonly="readonly" type="text" name="id" value="<?=$arr['id']?>"></td></tr>
<tr><td>名字</td><td><input type="text" name="name" value="<?=$arr['name']?>"></td></tr>
<tr><td>级别</td><td><input type="text" name="grade" value="<?=$arr['grade']?>"/></td></tr>
<tr><td>电邮</td><td><input type="text" name="email" value="<?=$arr['email']?>"/></td></tr>
<tr><td>薪水</td><td><input type="text" name="salary" value="<?=$arr['salary']?>"/></td></tr>
<tr>
<td><input type="submit" value="修改用户" /></td>
<td><input type="reset" value="重新填写" /></td>
<td><a href="index.php">返回界面</a></td>
</tr>
</table>
</form>
</html>