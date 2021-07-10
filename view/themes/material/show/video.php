<?php view::layout('layout')?>
<?php
$item['thumb'] = onedrive::thumbnail($item['path']);
?>
<?php view::begin('content');?>
<link class="dplayer-css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dplayer/dist/DPlayer.min.css">
<div class="mdui-container-fluid">
	<br>
	<div id="dplayer"></div>
	<br>
	<!-- 固定标签 -->
	<div class="mdui-textfield">
	  <label class="mdui-textfield-label">下载地址</label>
	  <input class="mdui-textfield-input" type="text" value="<?php e($url);?>"/>
	</div>
</div>
<?php
	$ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
	$play_url = $item['downloadUrl'];
	$type = "auto";
	if ($ext == "flv") {
		$type = "flv";
		e('<script src="https://cdn.jsdelivr.net/npm/flv.js@1.5.0/dist/flv.min.js"></script>');
	// 以下格式仅支持教育版和企业版
	} else if (in_array($ext,["ts","avi","mpg","mpeg","rm","rmvb","mov","wmv","asf"])) {
		$type = "dash";
		$play_url =  str_replace("thumbnail","videomanifest",$item['thumb'])."&part=index&format=dash&useScf=True&pretranscode=0&transcodeahead=0";
		e('<script src="https://cdn.jsdelivr.net/npm/dashjs@4.0.0-npm/dist/dash.all.min.js"></script>');
	}
?>
<script src="https://cdn.jsdelivr.net/npm/dplayer@1.26.0/dist/DPlayer.min.js"></script>
<script>
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
