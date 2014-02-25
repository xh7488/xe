<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?=$lang['login']?></title>
<link href="/res/admin/public.css" rel="stylesheet" type="text/css">
</head>
<body>
<script language="javascript" type="text/javascript">
function refreshimage()
{
  var cap =document.getElementById("captcha");
  cap.src=cap.src+'?';
}
</script>
<form action="/admin/login/post" method="post">
<div id="login">
  <div class="dologo"></div>
  <ul>  
   <li class="inpLi"><b><?=$lang['login_user_name']?>：</b><input type="text" name="user_name" class="inpLogin"></li>
   <li class="inpLi"><b><?=$lang['login_password']?>：</b><input type="password" name="password" class="inpLogin"></li>
  <?php if($cfg['captcha']):?>
   <li class="vcodePic">
     <div class="inpLi"><b><?=$lang['login_vcode']?>：</b><input type="text" name="vcode" class="vcode"></div>
     <img id="captcha" src="/admin/login/captcha" alt="CAPTCHA" border="1" onClick="refreshimage()" title="<?=$lang['login_vcode_refresh']?>">
   </li>
   <?php endif;?>
   <li><input type="submit" name="submit" class="btn" value="<?=$lang['login_submit']?>"></li> 
  </ul>
</div>
</form>
</body>
</html>