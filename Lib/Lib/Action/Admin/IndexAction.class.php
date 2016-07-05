<?php
class IndexAction extends SysAction {
     public function show(){
		 $this->display();
     }
	 
	 public function welcome(){
	      $this->display();
	 }
	 public function ads(){
	      $this->display();
	 }
	 public function makehtml(){
	      $ConfigData = FH_getConfig();
		  $ClassifyList = FH_getClassify();
		  
		  $this->assign('ConfigData',$ConfigData);
		  $this->assign('ClassifyList',$ClassifyList);
		  $this->display();
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
                
 
            $this->success('地图生成成功',U('makehtml')); 
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