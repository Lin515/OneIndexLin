<?php
	class onedrive{
		static $client_id;
		static $client_secret;
		static $redirect_uri;
		//国际版
		static $api_url = 'https://graph.microsoft.com/v1.0';
		static $oauth_url = 'https://login.microsoftonline.com/common/oauth2/v2.0';
		//世纪互联版
		// static $api_url = "https://microsoftgraph.chinacloudapi.cn/v1.0";
		// static $oauth_url = "https://login.partner.microsoftonline.cn/common/oauth2/v2.0";

		//验证URL，浏览器访问、授权
		static function authorize_url(){
			$client_id = self::$client_id;
			$scope = urlencode("offline_access files.readwrite.all");
			$redirect_uri = self::$redirect_uri;
			$url = self::$oauth_url."/authorize?client_id={$client_id}&scope={$scope}&response_type=code&redirect_uri={$redirect_uri}";
			
			if($_SERVER['HTTP_HOST'] != 'localhost'){
				$url .= '&state='.urlencode('http://'.$_SERVER['HTTP_HOST'].get_absolute_path(dirname($_SERVER['PHP_SELF'])));
			}
			
			return $url;
		}

		//使用 $code, 获取 $refresh_token
		static function authorize($code = ""){
			$client_id = self::$client_id;
			$client_secret = self::$client_secret;
			$redirect_uri = self::$redirect_uri;

			$url = self::$oauth_url."/token";
			$post_data = "client_id={$client_id}&redirect_uri={$redirect_uri}&client_secret={$client_secret}&code={$code}&grant_type=authorization_code";
			fetch::$headers = "Content-Type: application/x-www-form-urlencoded";
			$resp = fetch::post($url, $post_data);
			$data = json_decode($resp->content, true);
			return $data;
		}

		//使用 $refresh_token，获取 $access_token
		static function get_token($refresh_token){
			$client_id = self::$client_id;
			$client_secret = self::$client_secret;
			$redirect_uri = self::$redirect_uri;

			$request['url'] = self::$oauth_url."/token";
			$request['post_data']  = "client_id={$client_id}&redirect_uri={$redirect_uri}&client_secret={$client_secret}&refresh_token={$refresh_token}&grant_type=refresh_token";
			$request['headers']= "Content-Type: application/x-www-form-urlencoded";
			$resp = fetch::post($request);
			$data = json_decode($resp->content, true);
			return $data;
		}

		//获取 $access_token, 带缓存
		static function access_token(){
			$token = config('@token');
			if($token['expires_on'] > time()+600){
				return $token['access_token'];
			}else{
            			if (empty($token) || empty($token['refresh_token'])) {
                			$refresh_token = config('refresh_token');
            			} else {
                			$refresh_token = $token['refresh_token'];
            			}
				$token = self::get_token($refresh_token);
				if(!empty($token['refresh_token'])){
					$token['expires_on'] = time()+ $token['expires_in'];
					config('@token', $token);
					return $token['access_token'];
				}
			}
			return "";
		}


		// 生成一个request，带token
		static function request($path="/", $query=""){
			$path = self::urlencode($path);
			$path = empty($path)?'/':":/{$path}:/";
			$token = self::access_token();
			$request['headers'] = "Authorization: bearer {$token}".PHP_EOL."Content-Type: application/json".PHP_EOL;
			$request['url'] = self::$api_url."/me/drive/root".$path.$query;
			return $request;
		}

		
		//返回目录信息
		static function dir($path="/"){
			$request = self::request($path, 'children?select=name,size,folder,lastModifiedDateTime,id,@microsoft.graph.downloadUrl');
			$items = array();
			self::dir_next_page($request, $items);
			//不在列表显示的文件夹
			$hide_list = explode(PHP_EOL,config('onedrive_hide'));
			if(is_array($hide_list) && count($hide_list)>0){
				foreach($hide_list as $hide_dir){
					foreach($items as $key=>$_array){
						$buf = trim($hide_dir);
						if($buf && stristr($key, $buf))unset($items[$key]);
					}
				}
			}
			return $items;
		}

		//通过分页获取页面所有item
		static function dir_next_page($request, &$items, $retry=0){
			$resp = fetch::get($request);
			
			$data = json_decode($resp->content, true);
			if(empty($data) && $retry < 3){
				$retry += 1;
				return self::dir_next_page($request, $items, $retry);
			}
			
			foreach((array)$data['value'] as $item){
				//var_dump($item);
				$items[$item['name']] = array(
					'name'=>$item['name'],
					'id' => $item['id'],
					'size'=>$item['size'],
					'lastModifiedDateTime'=>strtotime($item['lastModifiedDateTime']),
					'downloadUrl'=>$item['@microsoft.graph.downloadUrl'],
					'folder'=>empty($item['folder'])?false:true
				);
			}

			if(!empty($data['@odata.nextLink'])){
				$request = self::request();
				$request['url'] = $data['@odata.nextLink'];
				return self::dir_next_page($request, $items);
			}
		}
	
		//关键字搜索
		static function search($keyword){
			$token = self::access_token();
			$keyword=self::urlencode($keyword);
			$request['headers'] = "Authorization: bearer {$token}".PHP_EOL."Content-Type: application/json".PHP_EOL;
			$request['url'] = self::$api_url."/me/drive/root/search(q='".$keyword."')";
			$resp=fetch::get($request);
			$data = json_decode($resp->content, true);

			foreach((array)$data['value'] as $item){
				//var_dump($item);
				$path = self::id2path($item['id']);
				$items[$item['name']] = array(
					'name'=>$item['name'],
					'id' => $item['id'],
					'size'=>$item['size'],
					'lastModifiedDateTime'=>strtotime($item['lastModifiedDateTime']),
					'folder'=>empty($item['folder'])?false:true,
					'path'=>$path
				);
			}
			return $items;
		}

		//文件缩略图链接
		static function thumbnail($path,$size='large'){
			$request = self::request($path,"thumbnails/0?select={$size}");
			$resp = fetch::get($request);
			$data = json_decode($resp->content, true);
			$request = self::request($path,"thumbnails/0?select={$size}");
			return @$data[$size]['url'];
		}

		static function share($path){
			$request = self::request($path,"createLink");
			$post_data['type'] = 'view';
			$post_data['scope'] = 'anonymous';
			$resp = fetch::post($request, json_encode($post_data));
			$data = json_decode($resp->content, true);
			return $data;
		}
		
		static function create_upload_session($path){
			$request = self::request($path, 'createUploadSession');
			$request['post_data'] = '{"item": {"@microsoft.graph.conflictBehavior": "fail"}}';
			$token = self::access_token();
			$resp = fetch::post($request);
			$data = json_decode($resp->content, true);
			if($resp->http_code == 409){
				return false;
			}
			return $data;
		}

		static function upload_session($url, $file, $offset, $length=10240){
			$token = self::access_token();
			$file_size = self::_filesize($file);
			$content_length = (($offset+$length)>$file_size)?($file_size-$offset):$length;
			$end = $offset+$content_length-1;
			$post_data = self::file_content($file, $offset, $length);

			$request['url'] = $url;
			$request['curl_opt']=[CURLOPT_TIMEOUT=>360];
			$request['headers'] = "Authorization: bearer {$token}".PHP_EOL;
			$request['headers'] .= "Content-Length: {$content_length}".PHP_EOL;
			$request['headers'] .= "Content-Range: bytes {$offset}-{$end}/{$file_size}";
			$request['post_data'] = $post_data;
			$resp = fetch::put($request);
			$data = json_decode($resp->content, true);
			return $data;
		}

		static function upload_session_status($url){
			$token = self::access_token();
			fetch::$headers = "Authorization: bearer {$token}".PHP_EOL."Content-Type: application/json".PHP_EOL;
			$resp = fetch::get($url);
			$data = json_decode($resp->content, true);
			return $data;
		}

		static function delete_upload_session($url){
			$token = self::access_token();
			fetch::$headers = "Authorization: bearer {$token}".PHP_EOL."Content-Type: application/json".PHP_EOL;
			$resp = fetch::delete($url);
			$data = json_decode($resp->content, true);
			return $data;
		}

		static function file_content($file, $offset, $length){
			$handler = fopen($file, "rb") OR die('获取文件内容失败');
			fseek($handler, $offset);
			
			return fread($handler, $length);
		}

		static function human_filesize($size, $precision = 1) {
			for($i = 0; ($size / 1024) > 1; $i++, $size /= 1024) {}
			return round($size, $precision).(['B','KB','MB','GB','TB','PB','EB','ZB','YB'][$i]);
		}

		static function urlencode($path){
			foreach(explode('/', $path) as $k=>$v){
				if(empty(!$v)){
					$paths[] = rawurlencode($v);
				}
			}
			return @join('/',$paths);
		}
			
		static function _filesize($path){
		    if (!file_exists($path))
		        return false;
		    $size = filesize($path);
		    
		    if (!($file = fopen($path, 'rb')))
		        return false;
		    
		    if ($size >= 0){//Check if it really is a small file (< 2 GB)
		        if (fseek($file, 0, SEEK_END) === 0){//It really is a small file
		            fclose($file);
		            return $size;
		        }
		    }
		    
		    //Quickly jump the first 2 GB with fseek. After that fseek is not working on 32 bit php (it uses int internally)
		    $size = PHP_INT_MAX - 1;
		    if (fseek($file, PHP_INT_MAX - 1) !== 0){
		        fclose($file);
		        return false;
		    }
		    
		    $length = 1024 * 1024;
		    while (!feof($file)){//Read the file until end
		        $read = fread($file, $length);
		        $size = bcadd($size, $length);
		    }
		    $size = bcsub($size, $length);
		    $size = bcadd($size, strlen($read));
		    
		    fclose($file);
		    return $size;
		}

		 //文件路径转itemid
		 public static function path2id($path)
		 {
			 $request = self::request(urldecode($path));
			 $access_token = self::access_token();
			 $request['headers'] = "Authorization: bearer {$access_token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
			 $resp = fetch::get($request);
			 $data = json_decode($resp->content, true);
			 return $data['id'];
		 }

		 //itemid转文件路径，原理：移动项目到其本身所在文件夹，可以返回其路径信息
		 //itemid：待转item的id
		 //parentid：待转item父项id。
		 public static function id2path($itemid)
		 {
			$resp_json = self::detail($itemid);
			$totalpath=$resp_json['parentReference']['path'];
			$count=strpos($totalpath,"/drive/root:");
			$pathwithroot=substr_replace($totalpath,"",$count,strlen('/drive/root:'));
			$count2 = strpos($pathwithroot,config('onedrive_path'));
			$path = config('root_path').'/'.substr_replace($pathwithroot,"",$count2,strlen(config('onedrive_root'))).'/';
			$path = str_replace("//",'/',$path).$resp_json['name'];
			return $path;
		 }

		 //itemid获取详细信息
		 public static function detail($itemid){
			$token = self::access_token();
			$request['headers'] = "Authorization: bearer {$token}".PHP_EOL."Content-Type: application/json".PHP_EOL;
			$request['url'] = self::$api_url."/me/drive/items/".$itemid;
			$resp = fetch::get($request);
			$data = json_decode($resp->content, true);
			return $data;
		 }

		
	}
