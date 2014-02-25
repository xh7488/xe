<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php if($url):?>
<meta http-equiv="refresh" content="<?=$time?>; URL=<?=$url?>" />
<?php endif;?>
<title><?=$lang['home'];if ($ur_here) echo $lang['msg'];?></title>
<link href="/res/admin/public.css" rel="stylesheet" type="text/css">
<?php include 'views/admin/javascript.php';?>
<?php if(!$url):?>
<script language=javascript>
{literal}
function go()
{
window.history.go(-1);
}
setTimeout("go()",3000);
{/literal}
</script>
<?php endif;?>
</head>
<body>
<?php if($out!=='out'):?>
<div id="dcWrap">
<?php include 'views/admin/header.php';?>
 <div id="dcLeft"><?php include 'views/admin/menu.php';?></div>
 <div id="dcMain">
 <?php include 'views/admin/ur_here.php';?>
  <div id="mainBox">
   <h3><?=$ur_here?></h3>
   <div id="douMsg">
    <h2><?=$text?></h2>
    <dl>
     <dt><?=$cue?></dt>
     <?php if($check):?>
     <p><form action="<?=$check?>" method="post"><input name="confirm" class="btn" type="submit" value="<?=$lang['del_confirm']?>" /></form></p>
    <?php endif;?>
     <dd><a href="<?php if($rul) echo $url?javascript:history.go(-1);?>"><?=$lang['dou_msg_back']?></a></dd>
    </dl>
   </div>
  </div>
 </div>
 <?php include 'views/admin/footer.php';?>
</div>
<?php else :?>
<div id="outMsg">
 <h2><?=$text?></h2>
 <dl>
  <dt><?=$cue?></dt>
  <dd><a href="<?php if($rul) echo $url?javascript:history.go(-1);?>{if $url}{$url}{else}javascript:history.go(-1);{/if}"><?=$lang['dou_msg_back']?></a></dd>
 </dl>
</div>
<?php endif;?>
</body>
</html>