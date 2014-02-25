<div class="incBox">
 <h3><?=$index['about_name']?></h3>
 <div class="about">
  <p><img src="/res/images/img_company.jpg" /></p>
  <dl>
   <dt><?=$cfg['site_name'] ;?></dt>
   <dd><?=\Xe\Strings::truncate($index['about_content'],180,'...')?></dd>
  </dl>
  <div class="clear"></div>
  <a href="<?=$index ['about_link']?>" class="aboutBtn"><?=$lang['about_link']?><?=$index['about_name']?></a>
 </div>
</div>