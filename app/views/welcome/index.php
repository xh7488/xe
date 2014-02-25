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
<script type="text/javascript" src="/res/js/slide_show.js"></script>
</head>
<body>
<div id="wrapper">
<?php include 'views/_inc/header.php';?>
 <div id="index" class="wrap mb">
 <?php include 'views/_inc/slide_show.php';?>
  <div id="indexLeft">
   <?php include 'views/_inc/recommend_product.php';?>
    <?php include 'views/_inc/about.php';?>
  </div>
  <div id="indexRight">
  <?php include 'views/_inc/recommend_article.php'?>
    <?php include 'views/_inc/contact.php'?>
  </div>
  <div class="clear"></div>
 </div>
 <?php include 'views/_inc/link.php' ?>
 <?php include 'views/_inc/online_service.php' ?>
 <?php include 'views/_inc/footer.php' ?> </div>
</body>
</html>