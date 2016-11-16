<?php /* Smarty version 3.1.28-dev/18, created on 2015-11-09 15:40:30
         compiled from "/var/www/templates/main.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:295837170564104be94d592_25325233%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f057ce135c3b1fad51971e965d15005e25fe4cda' => 
    array (
      0 => '/var/www/templates/main.tpl',
      1 => 1447011922,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '295837170564104be94d592_25325233',
  'variables' => 
  array (
    'body' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.28-dev/18',
  'unifunc' => 'content_564104be9a7b05_40334780',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_564104be9a7b05_40334780')) {
function content_564104be9a7b05_40334780 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '295837170564104be94d592_25325233';
?>
<html>
<head>
	<meta charset='utf-8'>
	<title>Hip</title>
	<?php echo '<script'; ?>
 src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src='js/script.js'><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src='js/ajax.js'><?php echo '</script'; ?>
>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<?php echo $_smarty_tpl->tpl_vars['body']->value;?>

<?php echo '<script'; ?>
 type="text/javascript" src='js/after_script.js'><?php echo '</script'; ?>
>
<!-- Yandex.Metrika counter -->
<?php echo '<script'; ?>
 type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter33468463 = new Ya.Metrika({
                    id:33468463,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    trackHash:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
<?php echo '</script'; ?>
>
<noscript><div><img src="https://mc.yandex.ru/watch/33468463" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</html>
<?php }
}
?>