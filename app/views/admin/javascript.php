<script type="text/javascript" src="images/jquery.min.js"></script>
<!-- {if $no_user} -->
<script language="JavaScript">
<!--
// 这里把JS用到的所有语言都赋值到这里
{foreach from=$lang.js_lang key=key item=item}
var {$key} = "{$item}";
{/foreach}
//-->
</script>
<!-- {/if} -->
<script language="JavaScript">
{literal}
$(document).ready(function(){
  $('.M').hover(
    function(){
      $(this).addClass('active');
    },
    function(){
      $(this).removeClass('active');
    });
});
{/literal}
</script>
<!-- {if $cur eq 'index'} -->
<script type="text/javascript">
var dou_api = '{$dou_api}';
{literal}
if (typeof(json) == 'undefined') var json='';
function jsonCallBack(url, callback) {
    $.getScript(url,
    function() {
        callback(json);
    });
}
function dou_update() {
    jsonCallBack(dou_api,
    function(json) {
        document.getElementById('douApi').innerHTML = json;
    })
}
window.onload = dou_update;
{/literal}
</script>
<!-- {/if} -->
<!-- {if $cur eq 'article' || $cur eq 'product' || $cur eq 'backup'} -->
<script language="JavaScript">
{literal}
// 表单全选
function selectcheckbox(form)
{
	for(var i = 0;i < form.elements.length; i++) 
	{
		var e = form.elements[i];
		if(e.name != 'chkall' && e.disabled != true) e.checked = form.chkall.checked;
	}
}
{/literal}
</script>
<!-- {/if} -->
