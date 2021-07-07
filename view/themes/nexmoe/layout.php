<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
	<title><?php e($title.' - '.config('site_name'));?></title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aplayer/dist/APlayer.min.css">
	<script src="https://cdn.jsdelivr.net/npm/aplayer/dist/APlayer.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/meting@2/dist/Meting.min.js"></script>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.loli.net/ajax/libs/mdui/0.4.3/css/mdui.min.css">
	<script	src="https://cdnjs.loli.net/ajax/libs/mdui/0.4.3/js/mdui.min.js"></script>
	<style>
		body{background-color:#f2f5fa;background-image:url("<?php echo config('site_background') ?>") !important;padding-bottom:60px;background-position:center top;background-repeat:repeat;background-attachment:fixed}.nexmoe-item{margin:20px -8px 0!important;padding:15px!important;border-radius:5px;background-color:#fff;-webkit-box-shadow:0 .5em 3em rgba(161,177,204,.4);box-shadow:0 .5em 3em rgba(161,177,204,.4);background-color:#fff}.mdui-img-fluid,.mdui-video-fluid{border-radius:5px;border:1px solid #eee}.mdui-list{padding:0}.mdui-list-item{margin:0!important;border-radius:5px;padding:0 10px 0 5px!important;border:1px solid #eee;margin-bottom:10px!important}.mdui-list-item:last-child{margin-bottom:0!important}.mdui-list-item:first-child{border:none}.mdui-toolbar{width:auto;margin-top:60px !important}.mdui-appbar
		.mdui-toolbar{height:56px;font-size:16px}.mdui-toolbar>*{padding:0 6px;margin:0 2px;opacity:.5}.mdui-toolbar>
		.mdui-typo-headline{padding:0 16px 0 0}.mdui-toolbar>i{padding:0}.mdui-toolbar>a:hover,a
		.mdui-typo-headline,a.active{opacity:1}.mdui-container{max-width:980px}.mdui-list>.th{background-color:initial}.mdui-list-item>a{width:100%;line-height:48px}.mdui-toolbar>a{padding:0 16px;line-height:30px;border-radius:30px;border:1px solid #eee}.mdui-toolbar>a:last-child{opacity:1;background-color:#1e89f2;color:#ffff}@media screen and (max-width:980px){.mdui-list-item .mdui-text-right{display:none}.mdui-container{width:100%!important;margin:0}.mdui-toolbar>*{display:none}.mdui-toolbar>a:last-child,.mdui-toolbar>.mdui-typo-headline,.mdui-toolbar>i:first-child{display:block}}.mc-drawer{background-color:rgba(255,255,255,0.5);}.mdui-container{max-width:1024px;}.mdui-list-item{-webkit-transition:none;transition:none;}.mdui-list-item> a{width:100%;line-height:45px}.mdui-list>.th{background-color:initial;}.mdui-row>.mdui-list>.mdui-list-item{margin:0px 0px 0px 0px;padding:0;}#instantclick-bar{background:}.mdui-video-fluid{height:-webkit-fill-available;}.dplayer-video-wrap .dplayer-video{height:-webkit-fill-available !important;}.gslide iframe,.gslide video{height:-webkit-fill-available;}@media screen and (max-width:800px){.mdui-list-item .mdui-text-right{display:none;}.mdui-container{width:100% !important;margin:0px;}}.spec-col{padding:.9em;display:flex;align-items:center;white-space:nowrap;flex:1 50%;min-width:225px}.spec-type{font-size:1.35em}.spec-value{font-size:1.25em}.spec-text{float:left}.device-section{padding-top:30px}.spec-device-img{height:auto;height:340px;padding-bottom:30px}#dl-header{margin:0}#dl-section{padding-top:10px}#dl-latest{position:relative;top:50%;transform:translateY(-50%)}.mdui-typo.mdui-shadow-3{background-color:rgba(255,255,255,0.5);}.nexmoe-item{background-color:rgba(255,255,255,0.5);}.mdui-row{margin-right:1px;margin-left:1px;}.thumb .th{display:none;}.thumb .mdui-text-right{display:none;}.thumb .mdui-list-item a,.thumb .mdui-list-item{width:213px;height:230px;float:left;margin:10px 10px !important;}.thumb .mdui-col-xs-12,.thumb .mdui-col-sm-7{width:100% !important;height:230px;}.thumb .mdui-list-item .mdui-icon{font-size:100px;display:block;margin-top:40px;color:#7ab5ef;}.thumb .mdui-list-item span{float:left;display:block;text-align:center;width:100%;position:absolute;top:180px;}.thumb .forcedownload{display:none;}.mdui-fab-fixed,.mdui-fab-wrapper{bottom:64px;}#toolbar{background-image:url() !important;}<?php echo config("cssstyle");//自定义css ?>
	</style>
</head>
<body class="mdui-theme-primary-blue-grey mdui-theme-accent-blue">
	<div class="mdui-container">
	    <div class="mdui-container-fluid">
	    <div class="mdui-toolbar nexmoe-item">
			<a href="/"><?php e(config('site_name'));?></a>
			<?php foreach((array)$navs as $n=>$l):?>
			<i class="mdui-icon material-icons mdui-icon-dark" style="margin:0;">chevron_right</i>
			<a href="<?php e($l);?>"><?php e($n);?></a>
			<?php endforeach;?>
		</div>
		</div>
    	<?php view::section('content');?>
  	</div>
	<script src="https://cdn.jsdelivr.net/gh/Mintimate/OneIndex-theme-nexmoes@latest/nexmoes/theme/personjs.js"></script>
  	<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  	<footer>
  	<p><a href="https://beian.miit.gov.cn" target="_blank"><center><?php echo config('icp_record') ?></center></a></p>
	</footer>
</body>
</html>