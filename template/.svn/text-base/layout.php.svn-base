<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo App::$mConfig['TEMPLATE_CHARSET'];?>" />
<title><?php echo empty($TEMPLATE_TITLE) ? App::$mConfig['TEMPLATE_TITLE'] : $TEMPLATE_TITLE;?></title>
<meta name="author" content="<?php echo App::$mConfig['TEMPLATE_AUTHOR'];?>" />
<link rel="shortcut icon" href="/favicon.ico?v="<?php echo VERSION; ?>>
<?php App::outputCssJsMeta('META');?>
<!--<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/reset-fonts-grids/reset-fonts-grids.css" />-->
<?php //默认每一张显示出来的页面都加载jqeury?>
<?php App::outputCssJsMeta('JS', array('jquery', 'bootstrap','main'));//默认每个页面都有的两个JS ?>
<?php App::outputCssJsMeta('CSS', array('bootstrap', /*'bootstrap-responsive',*/'main'));//默认每个页面都有的两个CSS ?>
<?php App::outputCssJsMeta('CSS');?>
<?php //Enable IE6 with HTML5 Tag ?>
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<style>
body{
padding-top:60px;
padding-bottom:40px;
}
</style>
</head>
<body>
<!--[if lte IE 8]>
<div class='window'>
<div id="ie-warning" style="color:red;padding:2px 10px;display:<?php echo isset($_COOKIE['already_know_ie'])? 'none' : 'block';?>">你正在使用的浏览器版本太低，将不能正常浏览本站及使用本站的所有功能。请升级 <a target="_blank" href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie">Internet Explorer</a> 或使用 <a target='blank' href="http://www.google.com/chrome/">Google Chrome</a> 浏览器。如果您使用的是搜狗浏览器，请切换至"高速模式"。&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='close'><a href="#">知道了</a></span></div>
</div>
<script>
document.isLessThanIE9 = true;
</script>
<![endif]-->
<?php include_once $TEMPLATE_FILE;?>
<?php App::outputCssJsMeta('JS');?>
</body>
</html>
