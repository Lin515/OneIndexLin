<?php
	view::layout('layout');
	$item['thumb'] = onedrive::thumbnail($item['path']);
	view::begin('content');
?>
<link class="dplayer-css" rel="stylesheet" href="<?php e(statics_cdn()); ?>dplayer@1.26.0/dist/DPlayer.min.css">
<?php
	$ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
	$play_url = $item['downloadUrl'];
	$type = "auto";
	if ($ext == "flv") {
		$type = "flv";
		e('<script src="' . statics_cdn() . 'flv.js@1.5.0/dist/flv.min.js"></script>');
	// 以下格式仅支持教育版和企业版
	} else if (in_array($ext,["ts","avi","mpg","mpeg","rm","rmvb","mov","wmv","asf"])) {
		$type = "dash";
		$play_url =  str_replace("thumbnail","videomanifest",$item['thumb'])."&part=index&format=dash&useScf=True&pretranscode=0&transcodeahead=0";
		e('<script src="' . statics_cdn() . 'dashjs@4.0.0-npm/dist/dash.all.min.js"></script>');
	}
?>
<script src="<?php e(statics_cdn()); ?>dplayer@1.26.0/dist/DPlayer.min.js"></script>
<div class="mdui-container-fluid">
	<div class="nexmoe-item">
	<div class="mdui-center" id="dplayer"></div>
	<div class="mdui-p-t-5 ">
		<ul class="mdui-menu" id="menu">
			<li class="mdui-menu-item">
			<a href="intent:<?php e($url);?>;end" class="mdui-ripple">MXPlayer(FREE)</a>
			</li>
			<li class="mdui-menu-item">
			<a href="vlc://<?php e($url);?>" class="mdui-ripple">VLC</a>
			</li>
			<li class="mdui-menu-item">
			<a href="potplayer://<?php e($url);?>" class="mdui-ripple">PotPlayer</a>
			</li>
			<li class="mdui-menu-item">
			<a href="nplayer-<?php e($url);?>" class="mdui-ripple">nPlayer</a>
			</li>
		</ul>
		<button id="appplayers" class="mdui-btn mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">&#xe039;</i>外部播放器播放<i class="mdui-icon material-icons">&#xe313;</i></button>
	</div>
	<!-- 固定标签 -->
	<div class="mdui-textfield">
	  <label class="mdui-textfield-label">下载地址</label>
	  <input class="mdui-textfield-input" type="text" value="<?php e($url);?>"/>
	</div>
	</div>
</div>
<script>
var inst = new mdui.Menu('#appplayers', '#menu');
document.getElementById('appplayers').addEventListener('click', function () {
  inst.open();
});

const dp = new DPlayer({
	container: document.getElementById('dplayer'),
	lang:'zh-cn',
	video: {
	    url: '<?php e($play_url);?>',
	    pic: '<?php @e($item['thumb']);?>',
	    type: '<?php e($type); ?>'
	}
});
</script>
<a href="<?php e($url);?>" class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">file_download</i></a>
<?php view::end('content');?>
