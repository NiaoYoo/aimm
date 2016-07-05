<?php
class PlugsAction extends Action {
     protected function _initialize(){
	     $ClassifyList = FH_getClassify();
	     $ConfigData = FH_getConfig();
		 $this->assign('ClassifyList',$ClassifyList);
		 $this->assign('ConfigData',$ConfigData);
	 }
    public function Feed() { 
 
			$Bloginfo = FH_getConfig();//查询bloginfo表的一行记录 
            $Bloglist = D('pic')->order('id desc')->limit(200)->select();//查询news表的记录 
          #import("@.ORG.Rss");//加载Rss.class.php类文件,我放在前台项目Lib/ORG目录中。 
			import('Lib.Action.Home.Rss');
              $RSS = new RSS($Bloginfo['site']['name'],$Bloginfo['site']['domain'],$Bloginfo['site']['des'],'');//初始化类，给RSS加上标题及描述信息，具体参数看构造器__construct 
              foreach($Bloglist as $list){ //遍历$Bloglist  
                $RSS->AddItem($list['title'],get_url_pic($list),$list['content']."<br><img src=\"".$list['titlepic']."\" alt=\"".$list['content']."\" width=\"318\" height=\"439\" \/>",date('Y-m-d H:i:s',$list['addtime'])); 
              } 
              $RSS->Display();//输出日记列表,不需要模板。
    }
	 public function gethits($id=0){
	      $db = M('Pic');
		  $id = (int)$id;
		  $arr = array(
		     'hits' => 0,
			 'good' => 0
		  );
		  $db->where('id='.$id)->setInc('hits'); //增加点击数
		  $data = $db->field('hits,good')->find($id);
		  if($data){
		     $arr['hits'] = $data['hits'];
			 $arr['good'] = $data['good'];
		  }
		  exit(json_encode($arr));
	 }
	 
	 public function digg($id=0){
	     $id = (int)$id;
		 if($id){
		     $db = M('Pic');
			 $db->where('id='.$id)->setInc('good'); 
			 $m = $db->where('id='.$id)->getField('good');
		 }else{
		     $m = 0;
		 }
		 $arr = array('good'=>(int)$m);
		 exit(json_encode($arr));
	 }
	 
	 public function piclike($id=0){
	     $id = (int)$id;
		 
		 $db = M('Pic');
		 $Data = $db->find($id);
		 if($Data){
		     $good = $Data["good"];
		 }else{
		     $good = 0;
		 }
		 $this->show('<span class="post-comments"><i class="like icon"></i>喜欢</span><small>'.$good.'</small>','utf-8');
		 exit();
		 
	 }
	 
	 public function postdigg($id){
	     $id = (int)$id;
		 $db = M('Pic');
		 $db->where('id='.$id)->setInc('good',1); 
		 $m = $db->where('id='.$id)->getField('good');
		 
		 $str = '<span class="post-comments"><i class="like icon"></i>喜欢</span><small>'.$m.'</small>';
		 $this->show($str,'utf-8');
		 exit();
	 }
	 
	 
	 
	 public function picdata($id=0){
		 $id = (int)$id;
		 $ConfigData = FH_getConfig();
		 $db = M('Pic');
		 $Data = $db->find($id);
		 if(!$Data){
		     $this->show('Request Error! ','utf-8');
			 exit();
		 }else{
		     
			 $imgArr = explode('$',$Data['picurls']);
			 $images = array();
			 foreach($imgArr as $k=>$r){
			     $images[$k] = array(
				     "title" => "",
				     "intro" => "",
				     "comment" => "",
				     "width" => "0",
				     "height" => "0",
				     "thumb_50" => $ConfigData['site']['piccdn'].$r,
				     "thumb_160" => $ConfigData['site']['piccdn'].$r,
				     "image_url" => $ConfigData['site']['piccdn'].$r,
				     "createtime" => "",
				     "source" => "",
				     "id" => $k+1
				 );
			 }
			 
			 $Url = get_url_pic($Data);
			 $prevData = $db->where('id<'.$Data["id"])->order('id desc')->find();
			 $nextData = $db->where('id>'.$Data["id"])->order('id')->find();
			 
			 $prevArr = array(
			     "interface" => "",
				 "title" => "",
				 "url" => "#no",
				 "thumb_50" => "/Public/Template/default/no-atlas.jpg"
			 );
			 
			 if($prevData){
			     $prevArr["title"] = $prevData["title"];
				 $prevArr["url"] = get_url_pic($prevData);
				 $prevArr["thumb_50"] = $ConfigData['site']['piccdn'].$prevData["titlepic"];
			 }
			 
			 $nextArr = array(
			     "interface" => "",
				 "title" => "",
				 "url" => "#no",
				 "thumb_50" => "/Public/Template/default/no-atlas.jpg"
			 );
			 
			 if($nextData){
			     $nextArr["title"] = $nextData["title"];
				 $nextArr["url"] = get_url_pic($nextData);
				 $nextArr["thumb_50"] = $ConfigData['site']['piccdn'].$nextData["titlepic"];
			 }
			 
			 
			 if(S('rarr_'.$Data["id"])){
				 $rarr=S('rarr_'.$Data["id"]);
			 }else{
			 $rarr = array(
			     "slide" => array(
				     "title" => $Data["title"],
					 "createtime" => date("Y-m-d h:i:s",$Data["addtime"]),
					 "click" => $Url,
                     "like" => $Data["good"],
                     "url" => $Url
				 ),
				 "next_album" => $nextArr,
				 "prev_album" => $prevArr,
				 "images" => $images
			 );
			 S('rarr_'.$Data["id"],$rarr,C('data_cache_pic'));
			 }
			 
			 
			 exit('var slide_data = '.json_encode($rarr));
			 
		 }
	 }
	 
	 
	 public function morehot($page=1){
		 if(S('morehot_'.$page)){
			 $DataList = S('morehot_'.$page);
			 #echo "123";
			 #exit;
		 }else{
	     $db = M('Pic');
		 $page = (int)$page;
		 $page = $page<1?1:$page;
		 $pageSize = 8;
		 $DataCount = $db->count();
		 
		 $maxPage = ceil($DataCount/$pageSize);
		 $page = $page>$maxPage?1:$page;
		 
	     $DataList = $db->order('hits desc')->page($page)->limit($pageSize)->select();
			S('morehot_'.$page,$DataList,C('data_cache_hot'));
		 }
		 $this->assign('DataList',$DataList);
		 $this->display('morelist');
	 }
	 
	 public function morenew($page=1){
		 if(S('morenew_'.$page)){
			 $DataList = S('morenew_'.$page);
		 }else{
	     $db = M('Pic');
		 $page = (int)$page;
		 $page = $page<1?1:$page;
		 $pageSize = 8;
		 $DataCount = $db->count();
		 
		 $maxPage = ceil($DataCount/$pageSize);
		 $page = $page>$maxPage?1:$page;
		 
	     $DataList = $db->order('lasttime desc')->page($page)->limit($pageSize)->select();
			S('morenew_'.$page,$DataList,C('data_cache_new'));
		 }
		 
		 $this->assign('DataList',$DataList);
		 $this->display('morelist');
	 }
	 
	 public function morerec($page=1){
		 if(S('morerec_'.$page)){
			 $DataList = S('morerec_'.$page);
			 #echo "123";
		 }else{
	     $db = M('Pic');
		 $page = (int)$page;
		 $page = $page<1?1:$page;
		 $pageSize = 8;
		 
		 
		 $where = array(
		     'star' => array('gt',0)
		 );
		 $DataCount = $db->where($where)->count();
		 
		 $maxPage = ceil($DataCount/$pageSize);
		 $page = $page>$maxPage?1:$page;
		 
	     $DataList = $db->where($where)->order('star desc')->page($page)->limit($pageSize)->select();
			S('morerec_'.$page,$DataList,C('data_cache_rec'));
		 }
		 $this->assign('DataList',$DataList);
		 $this->display('morelist');
	 }
    public function sitemap() {
				$data = FH_getConfig();
                $page_size    =    $data['site']['maplist']; //每页条数
                $bp_db    =    M('pic');
                //1w个地址生成一个子地图，判断需要生成几个？
				$map['id']  = array('GT',0);
                $count        =    $bp_db->order('id desc')->count();
                $page_count    =    ceil($count/$page_size);  //分几个文件
                
                $this->create_index($page_count);    //生成主sitemap
                $this->create_child($page_count,$page_size);    //生成子sitemap
                
 
            $this->success('地图生成成功'); 
        
    }
    
    //生成主sitemap
    protected function create_index($page_count) {
				$data = FH_getConfig();//查询bloginfo表的一行记录 
                $content    =    "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
                for($i=1;$i<=$page_count;$i++) {
                    
                        $content    .="<sitemap>\r\n<loc> ".$data['site']['domain']."/sitemap/sitemap".$i.".xml</loc>\r\n<lastmod>".date("Y-m-d",strtotime("-".$i." day"))."</lastmod>\r\n</sitemap>";
                }
                $content .= "</sitemapindex>";
                
                $file = fopen("sitemap.xml","w"); 
            fwrite($file,$content); 
            fclose($file);
    
    
   }
   
   //生成子sitemap
   protected function create_child($page_count,$page_size) {
       
       for($i=0;$i<$page_count;$i++) {

                   $list = D('pic')->order('id desc')->limit($i*$page_size.','.$page_size)->select(); 
                
                $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n"; 
                    foreach($list as $k=>$v){ 
                        $sitemap .= "<url>\r\n"."<loc>".get_url_pic($v)."</loc>\r\n"."<lastmod>".date('Y-m-d',$v['addtime'])."</lastmod>\r\n<changefreq>daily</changefreq>\r\n<priority>1.0</priority>\r\n</url>\r\n"; 
             
                    } 
                 
                $sitemap .= '</urlset>'; 
                 
                $file = fopen("sitemap/sitemap".($i+1).".xml","w"); 
                fwrite($file,$sitemap); 
                fclose($file);
           }
       } 
}