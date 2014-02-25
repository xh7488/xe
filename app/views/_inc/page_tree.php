<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="treeBox">
 <h3><?=$lang['about_tree']?></h3>
 <ul>
  <li <?=($top_cur)? 'class="cur"':''?>><a href="<?=$top['url']?>"><?=$top['page_name']?></a></li>
  <?php foreach ($page_list as $list):?>
  <li <?=($list['cur'])? 'class="cur"':''?>> <a href="<?=$list['url']?>"><?=$list['page_name']?></a></li>
  <?php endforeach;?>
 </ul>
</div>
