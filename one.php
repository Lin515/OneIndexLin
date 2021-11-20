<?php 
if( php_sapi_name() !== "cli" ){
   die( "NoAccess" );
}
require 'init.php';
ini_set('memory_limit', '128M');

class one{
	static function cache_clear(){
		cache::clear();
	}

	static function cache_refresh(){
		oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
	}

	static function token_refresh(){
		$refresh_token = config('refresh_token');
		$token = onedrive::get_token($refresh_token);
		if(!empty($token['refresh_token'])){
			config('@token', $token);
		}
	}
}

array_shift($argv);
$action = str_replace(':', '_',array_shift($argv));

if(is_callable(['one',$action])){
	@call_user_func_array(['one',$action], $argv);
	exit();
}
?>
oneindex commands :
 cache
  cache:clear    	clear cache
  cache:refresh  	refresh cache