<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
  <?php include 'views/_inc/product_tree.php'; ?>
  </div>
  <div id="pageIn">
  <?php include 'views/_inc/ur_here.php'; ?>
   <div class="productList">
   <?php 
   $n=count($product_list);
   $i=1;
   foreach ($product_list as $product):?>
    <dl <?php if($i++ === $n) echo 'class="last"';?>>
    <dt><a href="<?=$product['url']?>"><img src="<?=$product['thumb']?>" alt="<?=$product['name']?>" width="158" height="158" /></a></dt>
    <dd>
     <p class="name"><a href="<?=$product['url']?>" title="<?=$product['name']?>"><?=$product['name']?></a></p>
     <p class="brief"><?=\Xe\Strings::truncate($product['description'], 25)?></p>
     <p class="price"><?=$lang['price']?><?=$product['price']?></p>
     <p><a href="<?=$product['url']?>" class="btn"><?=$lang['product_buy']?></a></p>
    </dd>
    </dl>
    <?php endforeach;?>
    <div class="clear"></div>
   </div>
   <?php include 'views/_inc/pager.php'; ?>
  </div>
  <div class="clear"></div>
 </div>
 <?php include 'views/_inc/online_service.php';?>
 <?php include 'views/_inc/footer.php';?>
  </div>
</body>
</html>