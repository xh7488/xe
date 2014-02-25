<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?=$cfg['site_keywords'] ?>" />
<meta name="Description" content="<?=$cfg['site_description'] ?>" />
<title><?=$cfg['site_title'] ?></title>
<link href="/res/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/res/js/jquery.min.js"></script>
<script type="text/javascript" src="/res/js/global.js"></script>
</head>
<body>
<div id="wrapper">
<?php include 'views/_inc/header.php';?>
 <div class="wrap mb">
   <div id="pageLeft">
     <?php include 'views/_inc/product_tree.php';?>
   </div>
   <div id="pageIn">
    <?php include 'views/_inc/ur_here.php';?>
     <div id="product">
       <div class="productImg"><a href="<?=$product['thumb']?>" target="_blank"><img src="<?=$product['thumb']?>" width="300" height="300" /></a></div>
       <div class="productInfo">
         <h1><?=$product['product_name']?></h1>
         <ul>
           <li class="productPrice"><?=$lang['price']?>：<em class="price"><?=$product['price']?></em></li>
           
           <?php 
           if (!empty($defined)): foreach ($defined as $rs):?>
           <li><?=$rs['key']?>:<?=$rs['value']?></li>
           <?php  endforeach;endif;?>
         </ul>
         <dl class="btnAsk">
          <dt><?=$lang['product_buy']?>：</dt>
          <dd>
          <?php 
          $qqs=explode(';',$cfg['qq']);
          $qq=current($qqs);?>
          <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?=$qq?>&amp;site=qq&amp;menu=yes" target="_blank">
          <img src="/res/images/online_qq.jpg" />
          </a>
          <a href="mailto:<?=$cfg['email']?>"><img src="/res/images/online_email.jpg" /></a></dd>
         </dl>
       </div>
       <div class="clear"></div>
       <div class="productContent">
         <h3><?=$lang['product_content']?></h3>
         <ul><?=$product['content']?></ul>
       </div>
     </div>
   </div>
   <div class="clear"></div>
 </div> 
 <?php include 'views/_inc/online_service.php';?>
 <?php include 'views/_inc/footer.php';?>
 </div>
</body>
</html>