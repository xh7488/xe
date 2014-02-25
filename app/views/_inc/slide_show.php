<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div id="slideShow">
 <div class="slides">
 <?php $i=0; foreach ($show_list as $show):?>
  <div class="slide<?php if($i===0) echo ' current';?>" id="slide-<?=$i?>"> <a href="<?=$show['show_link']?>" target="_blank" ><img src="<?=$show['show_img']?>"/></a></div>
  <?php $i++;endforeach;?>
 </div>
 <ul class="controlBase">
 </ul>
 <ul class="controls">
 <?php $i=0; foreach ($show_list as $show):?>
  <li<?php if($i===0) echo ' class="active"';?>><a href="#" rel="slide-<?=$i?>"></a></li>
  <?php $i++;endforeach;?>
 </ul>
</div>