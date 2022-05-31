<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
	<title><?php e($title . config('site_name'));?></title>
	<link rel="stylesheet" href="<?php e(statics_cdn()); ?>bootstrap@3.4.1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php e(statics_cdn()); ?>mdui@0.4.3/dist/css/mdui.min.css">
	<link rel="stylesheet" href="<?php e(statics_cdn()); ?>nexmoe/nexmoe.css">
	<script src="<?php e(statics_cdn()); ?>mdui@0.4.3/dist/js/mdui.min.js"></script>
	<style>
	body {
		background-image:url("<?php echo config('site_background') ?>") !important;
	}
	</style>
</head>
<body class="mdui-theme-primary-blue-grey mdui-theme-accent-blue">
	<div class="mdui-container">
	    <div class="mdui-container-fluid">
	    <div class="mdui-toolbar nexmoe-item">
			<a href="/"><?php e(config('site_name_small'));?></a>
			<?php foreach((array)$navs as $n=>$l):?>
			<i class="mdui-icon material-icons mdui-icon-dark" style="margin:0;">chevron_right</i>
			<a href="<?php e($l);?>"><?php e($n);?></a>
			<?php endforeach;?>
		</div>
		</div>
    	<?php view::section('content');?>
  	</div>
  	<footer>
  	<p><a href="https://beian.miit.gov.cn" target="_blank"><center><?php echo config('icp_record') ?></center></a></p>
	</footer>
</body>
</html>