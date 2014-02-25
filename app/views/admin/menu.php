<!-- 后台菜单 -->
<div id="menu">
 <ul class="top">
  <li><a href="index.php"><i class="home"></i><em>{$lang.menu_home}</em></a></li>
 </ul>
 <ul>
  <li{if $cur eq 'system'} class="cur"{/if}><a href="system.php"><i class="system"></i><em>{$lang.system}</em></a></li>
  <li{if $cur eq 'nav'} class="cur"{/if}><a href="nav.php"><i class="nav"></i><em>{$lang.nav}</em></a></li>
  <li{if $cur eq 'show'} class="cur"{/if}><a href="show.php"><i class="show"></i><em>{$lang.show}</em></a></li>
  <li{if $cur eq 'page'} class="cur"{/if}><a href="page.php"><i class="page"></i><em>{$lang.menu_page}</em></a></li>
 </ul>
 <ul>
  <li{if $cur eq 'product_category'} class="cur"{/if}><a href="product_category.php"><i class="productCat"></i><em>{$lang.product_category}</em></a></li>
  <li{if $cur eq 'product'} class="cur"{/if}><a href="product.php"><i class="product"></i><em>{$lang.product}</em></a></li>
 </ul>
 <ul>
  <li{if $cur eq 'article_category'} class="cur"{/if}><a href="article_category.php"><i class="articleCat"></i><em>{$lang.article_category}</em></a></li>
  <li{if $cur eq 'article'} class="cur"{/if}><a href="article.php"><i class="article"></i><em>{$lang.article}</em></a></li>
 </ul>
 <ul>
  <li{if $cur eq 'manager'} class="cur"{/if}><a href="manager.php"><i class="manager"></i><em>{$lang.manager}</em></a></li>
  <li{if $cur eq 'manager_log'} class="cur"{/if}><a href="manager.php?rec=manager_log"><i class="managerLog"></i><em>{$lang.manager_log}</em></a></li>
 </ul>
 <ul class="bot">
  <li{if $cur eq 'backup'} class="cur"{/if}><a href="backup.php"><i class="backup"></i><em>{$lang.backup}</em></a></li>
  <li{if $cur eq 'link'} class="cur"{/if}><a href="link.php"><i class="link"></i><em>{$lang.link}</em></a></li>
 </ul>
</div>