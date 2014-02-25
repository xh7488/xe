<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div id="top">
 <div class="wrap"> <a href="javascript:AddFavorite('<?=ROOT_URL?>', '<?=$cfg['site_name']?>')"><?=$lang['add_favorite']?></a>
 <?php foreach($nav_top_list as $nav_top):?>
 | <a href="<?=$nav_top['url']?>"><?=$nav_top['nav_name']?></a>
 <?php endforeach;?>
</div>
</div>
<div id="header">
 <div class="wrap">
  <div class="logo"><a href="<?=ROOT_URL?>"><img src="/res/images/<?=$cfg['site_logo']?>" alt="<?=$cfg['site_name']?>" title="<?=$cfg['site_name']?>" /></a></div>
 </div>
</div>
<div id="mainNav">
 <ul class="wrap">
  <li class="first"><a href="<?=ROOT_URL?>" <?=(!empty($is_home))?'class="cur"':''?>><?=$lang['home']?></a></li>
  <?php $i=0;foreach($nav_list as $nav):?>
  <li <?php if($i===6)echo 'class="last"';?>>
  <a href="<?=$nav['url']?>" <?=(!empty($nav['cur']))?'class="cur"':''?>><?=$nav['nav_name']?></a>
   <dl>
   <?php foreach($nav['child'] as $child):?>
    <dd><a href="<?=$child['url']?>"><?=$child['nav_name']?></a></dd>
    <?php endforeach;?>
   </dl>
  </li>
  <?php $i++;endforeach;?>
  <div class="clear"></div>
 </ul>
</div>
