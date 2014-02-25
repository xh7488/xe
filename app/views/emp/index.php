<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>雇员信息列表</title>
<script type="text/javascript">
<!--

    function confirmDele(val){
     return window.confirm("是否要删除id="+val+"的用户");
}
//-->
</script>
</head>
<table  border='1' bordercolor='red' cellspacing='0px' width='700px'>
<tr><th>id</th><th>name</th><th>grade</th>
   		<th>email</th><th>salary</th><th>删除用户</th><th>修改用户</th></tr>
<?php
$i=1;
foreach($data as $k=>$row):
$id=$row['id'];
?>
	
	<tr><td align='center'><?=$i++?></td><td align='center'><?=$row['name']?></td>
	<td align='center'><?=$row['grade']?></td><td align='center'><?=$row['email']?></td><td align='center'><?=$row['salary']?></td>
	<td align='center'><a return onclick='confirmDele(<?=$id?>)' href='/emp/del&id=<?=$id?>'>删除用户</a></td>
	<td align='center'><a href='/emp/modify?id=<?=$id?>'>修改用户</a></td></tr>
<?php 
endforeach;
?>
<h1>雇员信息列表</h1><br/>
</table>
<?php 
echo $page->pageShow();

?>
   <form action="/welcome/index">
        跳转到：<input type="text" name="" />
    <input type="submit" value="go">
    </form>

</html>