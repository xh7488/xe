<?php if (!empty($cfg['qq'])):?>
<div id="onlineService">
 <dl>
	<dt class="service"></dt>
	<dd id="pop">
	<?php 
	$qqs=explode(';',$cfg['qq']);
	foreach ($qqs as $qq):?>
	<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?=$qq?>&site=qq&menu=yes">
	<img src="/res/images/online_im.png" alt="点击这里给我发消息" title="点击这里给我发消息"/></a>
	<?php endforeach;?>
	</dd>
 </dl>
 <dl class="goTop"><a href="javascript:;" onfocus="this.blur();" class="goBtn"></a></dl>
</div>
<?php endif;?>
