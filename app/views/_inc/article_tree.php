<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="treeBox">
 <h3><?=$lang['article_tree']?></h3>
 <ul>
 <?php foreach ($article_category as $cate):?>
  <li <?php if($cate['cur']) echo 'class="cur"'?>><a href="<?=$cate['url']?>"><?=$cate['cat_name']?></a></li>
  <?php endforeach;?>
 </ul>
</div> 