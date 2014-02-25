<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="incBox">
 <h3><a href="<?=$index['product_link']?>"><?=$lang['product_news']?></a></h3>
 <ul class="recommendProduct">
 <?php $i=0;foreach ($recommend_product as $product):?>
  <li <?php if(($i+1) % 4===0)echo 'class="clearBorder"' ?>>
  <p class="img"><a href="<?=$product['url']?>"><img src="<?=$product['thumb']?>" width="135" height="135" /></a></p>
  <p class="name"><a href="<?=$product['url']?>"><?=$product['name']?></a></p>
  <p class="price"><?=$product['price']?></p>
  </li>
  <?php $i++;endforeach;?>
  <div class="clear"></div>
 </ul>
</div>
