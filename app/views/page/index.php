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
   <?php include 'views/_inc/page_tree.php';?>
   </div>
			<div id="pageIn">
   <?php include 'views/_inc/ur_here.php';?>
     <div id="page">
					<h1><?=$page['page_name']?></h1>
					<div class="content"><?=$page['content']?> </div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include 'views/_inc/online_service.php';?>
		<?php include 'views/_inc/footer.php';?>
	</div>
</body>
</html>
