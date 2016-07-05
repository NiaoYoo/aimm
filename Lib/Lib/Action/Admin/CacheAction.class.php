<?php
class CacheAction extends SysAction {
	/*拓展工具*/
    public function Show(){    
		 $this->display('show');
    }
    /*清空缓存*/
    public function del(){
		import('Lib.Action.Home.Dir');
		$dir = new Dir;
		@unlink('./Runtime/~runtime.php');
		if(!$dir->isEmpty('./Runtime/Data/_fields')){$dir->del('./Runtime/Data/_fields');}
		if(!$dir->isEmpty('./Runtime/Temp')){$dir->delDir('./Runtime/Temp');}
		if(!$dir->isEmpty('./Runtime/Cache')){$dir->delDir('./Runtime/Cache');}
		if(!$dir->isEmpty('./Runtime/Logs')){$dir->delDir('./Runtime/Logs');}
		echo('清除成功');
    }#
	// 删除静态缓存
	public function delhtml(){
		$id = $_GET['id'];
		#echo $id;die;
	    import('Lib.Action.Home.Dir');
		$dir = new Dir;
		if('index' == $id){
			@unlink(HTML_PATH.'index'.C('html_file_suffix'));
		}elseif('Index_classify'== $id){
			if(is_dir(HTML_PATH.'Index_classify')){$dir->delDir(HTML_PATH.'Index_classify');}		    
		}elseif('Index_content' == $id){
			if(is_dir(HTML_PATH.'Index_content')){$dir->delDir(HTML_PATH.'Index_content');}	    
		}elseif('Index_tags' == $id){
			if(is_dir(HTML_PATH.'Index_tags')){$dir->delDir(HTML_PATH.'Index_tags');}			
		}elseif('Index_pages' == $id){
			if(is_dir(HTML_PATH.'Plugs_morenew')){$dir->delDir(HTML_PATH.'Plugs_morenew');}	    
			if(is_dir(HTML_PATH.'Plugs_morehot')){$dir->delDir(HTML_PATH.'Plugs_morehot');}	    
			if(is_dir(HTML_PATH.'Plugs_morerec')){$dir->delDir(HTML_PATH.'Plugs_morerec');}    	    
		}elseif('day' == $id){
		    $this->delhtml_day();    
		}else{
		    @unlink(HTML_PATH.'index'.C('html_file_suffix'));
			if(is_dir(HTML_PATH.'Index_classify')){$dir->delDir(HTML_PATH.'Index_classify');}	    
			if(is_dir(HTML_PATH.'Index_content')){$dir->delDir(HTML_PATH.'Index_content');}	    
			if(is_dir(HTML_PATH.'Index_pages')){$dir->delDir(HTML_PATH.'Index_pages');}	    
			if(is_dir(HTML_PATH.'Index_tags')){$dir->delDir(HTML_PATH.'Index_tags');}    
			if(is_dir(HTML_PATH.'Plugs_morehot')){$dir->delDir(HTML_PATH.'Plugs_morehot');}
		    if(is_dir(HTML_PATH.'Plugs_morenew')){$dir->delDir(HTML_PATH.'Plugs_morenew');}	    
		    if(is_dir(HTML_PATH.'Plugs_morerec')){$dir->delDir(HTML_PATH.'Plugs_morerec');}	    
		}
		echo('清除成功');
	}
	//清理当天静态缓存文件
	public function delhtml_day(){
		$where = array();
		$where['lasttime']= array('gt',getxtime(1));
		$rs = D("Pic");
		$array = $rs->field('id')->where($where)->order('id desc')->select();
		if($array){
			foreach($array as $key=>$val){
			    $id = md5($array[$key]['id']).C('html_file_suffix');
			    @unlink(HTML_PATH.'Index_content/'.$id);
				#@unlink('./Html/Vod_play/'.$id);
			}
		    import('Lib.Action.Home.Dir');
			$dir = new Dir;
			if(!$dir->isEmpty('./Html/Vod_show')){$dir->delDir('./Html/Vod_show');}	
			if(!$dir->isEmpty('./Html/Ajax_show')){$dir->delDir('./Html/Ajax_show');}
			@unlink('./Html/index'.C('html_file_suffix'));						
		}
		echo('清除成功');
	}
	//当天图片数据缓存
	public function datadaypic(){
		$where = array();
		$where['lasttime']= array('gt',getxtime(1));
		$rs = M("Pic");
		$array = $rs->field('id')->where($where)->order('id desc')->select();
		foreach($array as $key=>$val){
			S('content_'.$val['id'],NULL);
		}						
		echo('清除成功');
	}	
    /*清空所有数据缓存内容*/
    public function dataclear(){
		if(C('data_cache_type') == 'memcache'){
			$cache = Cache::getInstance();	
			$cache->clear();
		}else{
			import('Lib.Action.Home.Dir');
			$dir = new Dir;
			if(!$dir->isEmpty(TEMP_PATH)){
				$dir->delDir(TEMP_PATH);
			}
		}
		echo('清除成功');
    }
	// 配置信息
    public function config(){
		$tpl = TMPL_PATH.'*';
		$list = glob($tpl);
		foreach ($list as $i=>$file){
			$dir[$i]['filename'] = basename($file);
		}
		$this->assign('dir',$dir);
		$config = require './Runtime/Conf/config.php';
		$this->assign($config);
		#$this->ppvod_list();//更新导航菜单
        $this->display('./Lib/Tpl/Admin/Cache_index.html');
    }
    //更新性能配置
	public function update(){
			$config = $_POST["config"];
			//静态缓存
			$config['html_cache_time'] = $config['html_cache_time']*3600;//其它页缓存
			if($config['html_cache_index']>0){
				#$config['HTML_CACHE_RULES']['Index:content'] = array('{:action}_{id}',$config['html_cache_index']*3600);
				$config['HTML_CACHE_RULES']['Index:index'] = array('{:action}',$config['html_cache_index']*3600);
			}else{
				$config['HTML_CACHE_RULES']['Index:index'] = NULL;
				#$config['HTML_CACHE_RULES']['Index:classify'] = NULL;
				#$config['HTML_CACHE_RULES']['Sys:Plugs:morenew'] = NULL;
			}
			if($config['html_cache_list']>0){
			    $config['HTML_CACHE_RULES']['Index:classify'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_list']*3600);
			}else{
			    $config['HTML_CACHE_RULES']['Index:classify'] = NULL;
			}
			if($config['html_cache_content']>0){
		    $config['HTML_CACHE_RULES']['Index:content'] = array('{:module}_{:action}/{id|md5}',$config['html_cache_content']*3600);
			}else{
		    $config['HTML_CACHE_RULES']['Index:content'] = NULL;
			}
			if($config['html_cache_ajax']>0){
			    $config['HTML_CACHE_RULES']['Index:pages'] = array('{:module}_{:action}/{name}',$config['html_cache_ajax']*3600);
			    $config['HTML_CACHE_RULES']['Index:search'] = array('{:module}_{:action}/{keyword|urlencode}_{page}',$config['html_cache_ajax']*3600);
			    $config['HTML_CACHE_RULES']['Index:tags'] = array('{:module}_{:action}/{name|urlencode}_{page}',$config['html_cache_ajax']*3600);
			    $config['HTML_CACHE_RULES']['Plugs:morehot'] = array('{:module}_{:action}/{page|md5}',$config['html_cache_ajax']*3600);
			    $config['HTML_CACHE_RULES']['Plugs:morenew'] = array('{:module}_{:action}/{page|md5}',$config['html_cache_ajax']*3600);
			    $config['HTML_CACHE_RULES']['Plugs:morerec'] = array('{:module}_{:action}/{page|md5}',$config['html_cache_ajax']*3600);
			}else{
			    $config['HTML_CACHE_RULES']['Index:pages'] = NULL;
			    $config['HTML_CACHE_RULES']['Index:search'] = NULL;
			    $config['HTML_CACHE_RULES']['Index:tags'] = NULL;
			    $config['HTML_CACHE_RULES']['Plugs:morehot'] = NULL;
			    $config['HTML_CACHE_RULES']['Plugs:morenew'] = NULL;
			    $config['HTML_CACHE_RULES']['Plugs:morerec'] = NULL;
			}
			$config['tmpl_cache_on'] = (bool) $config['tmpl_cache_on'];
			$config['html_cache_on'] = (bool) $config['html_cache_on'];
			$config['data_cache_subdir'] = (bool) $config['data_cache_subdir'];
			$config_old = require './Runtime/Conf/config.php';
			$config_new = array_merge($config_old,$config);
			arr2file('./Runtime/Conf/config.php',$config_new);
			@unlink('./Runtime/~runtime.php');
			$this->success('更新成功!');
	}
}