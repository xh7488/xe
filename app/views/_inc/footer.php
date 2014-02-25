<div id="footer">
 <div class="wrap">
  <div class="footNav">
  <?php foreach ($nav_bottom_list as $nav_bottom):?>
  <a href="<?=$nav_bottom['url']?>"><?=$nav_bottom['nav_name']?></a>
  <?php endforeach;?>
  </div>
  <div class="copyRight"><?=$lang['copyright']?> <?=$lang['powered_by']?> 
  <?php if (!empty($cfg['icp'])) echo $cfg['icp'] ?></div>
  </div>
</div>
<?php if (!empty($cfg['code'])):?>
<div style="display:none"><?=$cfg['code']?></div>
<?php endif;?>
