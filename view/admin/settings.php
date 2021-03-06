<?php view::layout('layout')?>

<?php view::begin('content');?>
<div class="mdui-container-fluid">
	<div class="mdui-typo">
	  <h1> 基本设置 <small>设置OneIndex基本参数</small></h1>
	</div>
	<form action="" method="post">
		<div class="mdui-textfield">
		  <h4>网站名称</h4>
		  <input class="mdui-textfield-input" type="text" name="site_name" value="<?php echo $config['site_name'];?>"/>
		</div>

		<div class="mdui-textfield">
		  <h4>站点短名称</h4>
		  <input class="mdui-textfield-input" type="text" name="site_name_small" value="<?php echo $config['site_name_small'];?>"/>
		</div>

		<div class="mdui-textfield">
		  <h4>网站主题<small></small></h4>
		  <select name="style" class="mdui-select">
			  <?php
				foreach(scandir(ROOT.'/view/themes') as $k=>$s){
				    $styles[$k] = trim($s, '/');
				}
				$styles = array_diff($styles, [".", "..", "admin"]);
				$style = config("style")?config("style"):'nexmoe';
				$cache_type  = config("cache_type")?config("cache_type"):'secache';
			 	foreach($styles as $style_name):
			  ?>
			  <option value ="<?php echo $style_name;?>" <?php echo ($style==$style_name)?'selected':'';?>><?php echo $style_name;?></option>
			  <?php endforeach;?>
		  </select>
		</div>

		<div class="mdui-textfield">
		  <h4>网站背景图<small> 开启伪静态后此处需使用完整地址</small></h4>
		  <input class="mdui-textfield-input" type="text" name="site_background" value="<?php echo $config['site_background'];?>"/>
		</div>

		<div class="mdui-textfield">
		  <h4>静态资源CDN地址<small> 请指定statics文件夹的所在地址，例如 https://cdn.jsdelivr.net/gh/Lin515/OneIndexLin/statics/ ，需以/结尾，若留空则使用本地资源</small></h4>
		  <input class="mdui-textfield-input" type="text" name="statics_cdn" value="<?php echo $config['statics_cdn'];?>"/>
		</div>

		<div class="mdui-textfield">
		  <h4>ICP备案号</h4>
		  <input class="mdui-textfield-input" type="text" name="icp_record" value="<?php echo $config['icp_record'];?>"/>
		</div>

		<div class="mdui-textfield">
		  <h4>OneDrive起始目录(空为根目录)<small>例：仅共享share目录 /share</small></h4>
		  <input class="mdui-textfield-input" type="text" name="onedrive_root" value="<?php echo $config['onedrive_root'];?>"/>
		</div>

		<div class="mdui-textfield">
		  <h4>不渲染目录<small> 该目录下index.html、readme.md、head.md文件不会被渲染</small></h4>
		  <input class="mdui-textfield-input" type="text" name="except_path" value="<?php echo $config['except_path'];?>"/>
		  <small>设置所有目录都不渲染输入all，设置名为all的目录不渲染，输入/all</small>
		</div>

		<div class="mdui-textfield">
		  <h4>需要隐藏的目录<small> 不需要列出的目录(一行一个) 清空缓存后生效</small></h4>
		  <textarea class="mdui-textfield-input" placeholder="输入后回车换行" name="onedrive_hide"><?=@$config['onedrive_hide'];?></textarea>
		  <small>这里是通配识别，就是存在以上字符文件夹一律会隐藏</small>
		</div>


		<div class="mdui-textfield">
		  <h4>防盗链(白名单)<small> 不填写则不启用, 多个用英文 <code>;</code> 分割</small></h4>
		  <input class="mdui-textfield-input" name="onedrive_hotlink" value="<?=@$config['onedrive_hotlink'];?>"/>
		  <small>支持通配符 例: <code>*.domain.com</code></small>
		</div>

		<div class="mdui-textfield">
		  <h4>缓存类型<small></small></h4>
		  <select name="cache_type" class="mdui-select">
			  <?php
			 	foreach(['secache', 'filecache', 'memcache', 'redis'] as $type):
			  ?>
			  <option value ="<?php echo $type;?>" <?php echo ($type==$cache_type)?'selected':'';?>><?php echo $type;?></option>
			  <?php endforeach;?>
		  </select>
		</div>

		<div class="mdui-textfield">
		  <h4>缓存过期时间(秒)</h4>
		  <input class="mdui-textfield-input" type="text" name="cache_expire_time" value="<?php echo $config['cache_expire_time'];?>"/>
		</div>

		<div class="mdui-textfield">
		  <h4>去掉地址栏中的<code style="color: #c7254e;background-color: #f7f7f9;font-size:16px;">/?/</code> (需配合伪静态使用!!)</h4>
		  <label class="mdui-textfield-label"></label>
		  <label class="mdui-switch">
			  <input type="checkbox" name="root_path" value="?" <?php echo empty($config['root_path'])?'checked':'';?>/>
			  <i class="mdui-switch-icon"></i>
		  </label>
		</div>




	   <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right">
	   	<i class="mdui-icon material-icons">&#xe161;</i> 保存
	   </button>
	</form>
</div>
<?php view::end('content');?>
