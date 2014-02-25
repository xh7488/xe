<div class="pager">
<?=$lang['pager_1']?><?=$pager->total?><?=$lang['pager_2']?>,<?=$lang['pager_3']?>
<?=$pager->pageToatl?><?=$lang['pager_4']?>,<?=$lang['pager_5']?><?=$pager->page?>
<?=$lang['pager_4']?> |
  <?php  $links=$pager->links;
  if($links['first']!=='#'):?>
 <a href="<?=$links['first']?>"><?=$lang['pager_first']?></a> 
 <?php else : echo $lang['pager_first']; endif;?>
 <?php if($links['up']!=='#' && $pager->page>1):?>
 <a href="<?=$links['up']?>"><?=$lang['pager_previous']?></a>
 <?php else : echo  $lang['pager_previous']; endif;?>
 <?php if($links['down']!=='#' && $pager->page<$pager->total):?>
 <a href="<?=$links['down']?>"><?=$lang['pager_next']?></a>
 <?php else : echo $lang['pager_next'];endif;?>
 <?php if($links['ending']!=='#'):?>
 <a href="<?=$links['ending']?>"><?=$lang['pager_last']?></a>
 <?php else: echo $lang['pager_last']; endif;?>
 </div>
 