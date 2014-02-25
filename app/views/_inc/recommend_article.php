<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="incBox">
 <h3><a href="<?=$index['article_link']?>"><?=$lang['article_news']?></a></h3>
 <ul class="recommendArticle">
  <?php $i=1;$n=count($recommend_article);foreach($recommend_article as $article): ?>
  <li<?php if($i===$n) echo 'class="last"';?>><b>
  <?=$article['add_time_short']?></b><a href="<?=$article['url']?>"><?=$article['title']?></a></li>
  <?php $i++;endforeach;?> 
 </ul>
</div>
