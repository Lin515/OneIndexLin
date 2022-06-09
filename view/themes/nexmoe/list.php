<?php view::layout('layout')?>
<?php
function file_ico($item) {
	$ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
	if ($ext == "") {
		return "insert_drive_file";
	}
	if (in_array($ext,config("show")['image'])) {
		return "image";
	}
	if (in_array($ext,config("show")['video'])) {
		return "ondemand_video";
	}
	if (in_array($ext,config("show")['video5'])) {
		return "ondemand_video";
	}
	if (in_array($ext,config("show")['audio'])) {
		return "audiotrack";
	}
	return "insert_drive_file";
}
?>

<?php view::begin('content');?>

<div class="mdui-container-fluid">
<?php if($head):?>
<div class="mdui-typo mdui-shadow-3" style="padding: 20px;margin: 20px; 0">
	<?php e($head);?>
</div>
<?php endif;?>
<style>
.thumb .th {
	display: none;
}

.thumb .mdui-text-right {
	display: none;
}

.thumb .mdui-list-item a,.thumb .mdui-list-item {
	width: 217px;
	height: 230px;
	float: left;
	margin: 10px 10px !important;
}

.thumb .mdui-col-xs-12,.thumb .mdui-col-sm-7 {
	width: 100% !important;
	height: 230px;
}

.thumb .mdui-list-item .mdui-icon {
	font-size: 100px;
	display: block;
	margin-top: 40px;
	color: #7ab5ef;
}

.thumb .mdui-list-item span {
	float: left;
	display: block;
	text-align: center;
	width: 100%;
	position: absolute;
	top: 180px;
}

.simple-spinner {
	height: 100%;
	border: 8px solid rgba(150,150,150,0.2);
	border-radius: 50%;
	border-top-color: rgb(150,150,150);
	animation: rotate 1s 0s infinite ease-in-out alternate;
}

@keyframes rotate {
	0% {
		transform: rotate(0);
	}

	100% {
		transform: rotate(360deg);
	}
}
</style>
<div class="nexmoe-item">
	<div class="mdui-row">
		<ul class="mdui-list">
			<li class="mdui-list-item th">
				<input class="mdui-textfield-input" type="text" id="filteredit" placeholder="搜索当前目录" autofocus />
				<button class="mdui-btn mdui-ripple" id="sharebtn">获取链接</button>
			</li>
			<li class="mdui-list-item th" id="indexsort">
				<label class="mdui-checkbox">
					<input type="checkbox" value="" id="checkall" onclick="checkall()">
					<i class="mdui-checkbox-icon"></i>
				</label>
				<div class="mdui-col-xs-12 mdui-col-sm-7">文件 <i class="mdui-icon material-icons icon-sort" data-sort="name" data-order="more">expand_more</i></div>
				<div class="mdui-col-sm-3 mdui-text-right">修改时间 <i class="mdui-icon material-icons icon-sort" data-sort="date" data-order="more">expand_more</i></div>
				<div class="mdui-col-sm-2 mdui-text-right">大小 <i class="mdui-icon material-icons icon-sort" data-sort="size" data-order="more">expand_more</i></div>
			</li>

			<?php if($path != '/'):?>
			<li class="mdui-list-item mdui-ripple" id="backtolast">
				<a href="<?php echo get_absolute_path($root.$path.'../');?>">
				<div class="mdui-col-xs-12 mdui-col-sm-7">
					<i class="mdui-icon material-icons">arrow_upward</i>
					..
				</div>
				<div class="mdui-col-sm-3 mdui-text-right"></div>
				<div class="mdui-col-sm-2 mdui-text-right"></div>
				</a>
			</li>
			<?php endif;?>

			<?php foreach((array)$items as $item):?>
				<?php if(!empty($item['folder'])):?>

			<li class="mdui-list-item mdui-ripple filter" data-sort
				data-sort-name="<?php echo $item['name'];?>"
				data-sort-date="<?php echo $item['lastModifiedDateTime'];?>"
				data-sort-size="<?php echo $item['size'];?>"
				id="<?php echo $item["id"] ?>" >
				<label class="mdui-checkbox">
					<input type="checkbox" id="check" value="<?php echo $item["id"] ?>" name="itemid" onclick="onClickHander()">
					<i class="mdui-checkbox-icon"></i>
				</label>
				<a href="<?php echo get_absolute_path($root.$path.rawurlencode($item['name']));?>">
				<div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
					<i class="mdui-icon material-icons">folder_open</i>
					<span><?php echo $item['name'];?></span>
				</div>
				<div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
				<div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
				</a>
			</li>
				<?php else:?>
			<li class="mdui-list-item file mdui-ripple filter" data-sort
				data-sort-name="<?php echo $item['name'];?>"
				data-sort-date="<?php echo $item['lastModifiedDateTime'];?>"
				data-sort-size="<?php echo $item['size'];?>"
				id="<?php echo $item["id"] ?>" >
				<label class="mdui-checkbox">
					<input type="checkbox" value="<?php echo $item["id"] ?>" name="itemid" onclick="onClickHander()">
					<i class="mdui-checkbox-icon"></i>
				</label>
				<a href="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']);?>" target="_blank">
				<div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
					<i class="mdui-icon material-icons"><?php echo file_ico($item);?></i>
					<span><?php e($item['name']);?></span>
				</div>
				<div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
				<div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
				</a>
			</li>
			<?php endif;?>
			<?php endforeach;?>
		</ul>
	</div>
</div>

<?php if($readme):?>
<div class="mdui-typo mdui-shadow-3" style="padding: 20px;margin: 20px; 0">
	<?php e($readme);?>
</div>
<?php endif;?>
</div>

<div class="mdui-fab-wrapper" id="myFab">
    <button class="mdui-fab mdui-ripple mdui-color-theme-accent">
      <i class="mdui-icon material-icons">add</i>
      <i class="mdui-icon mdui-fab-opened material-icons">mode_edit</i>
    </button>
    <div class="mdui-fab-dial">
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-orange" onclick="window.open('/?/admin')"><i class="mdui-icon material-icons">account_circle</i>
      </button>
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-blue" onclick="thumb()"><i class="mdui-icon material-icons" id="format_list">format_list_bulleted</i>
      </button>
    </div>
</div>

<div class="mdui-container">
 <div class="mdui-dialog" id="share">
    <div class="mdui-dialog-content">
			<div class="mdui-textfield mdui-textfield-floating-label">
				<label class="mdui-textfield-label">请手动复制以下链接</label>
				<textarea class="mdui-textfield-input" style="margin: 20px 0;" rows="5" readonly id="sharelinks"></textarea>
			</div>
	</div>
  </div>
</div>

<script src="<?php e(statics_cdn()); ?>jquery@3.5.1/dist/jquery.min.js"></script>
<script	src="<?php e(statics_cdn()); ?>mdui@0.4.3/dist/js/mdui.min.js"></script>
<script src="<?php e(statics_cdn()); ?>clipboard@2.0.6/dist/clipboard.min.js"></script>
<script src="<?php e(statics_cdn()); ?>nexmoe/nexmoe.js"></script>

<?php view::end('content');?>
