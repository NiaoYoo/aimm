<?php
class IndexAction extends Action {
     protected function _initialize(){
	     $ClassifyList = FH_getClassify();
		 $PagesList = FH_getPages();
		 $ConfigData = FH_getConfig();
		 

		 $this->assign('ClassifyList',$ClassifyList);
		 $this->assign('PagesList',$PagesList);
		 $this->assign('ConfigData',$ConfigData);
	 }

	 public function index(){
		 $this->display('index');
     }
	 public function content($id=0){
		 $id = (int)$id;
		 $db = M('Pic');
		 
		 if(S('content_'.$id)){
		 	$Data =S('content_'.$id);
		 }else{
		 	$Data = $db->find($id);
		 S('content_'.$id,$Data,C('data_cache_pic'));
		 }
		 if(!$Data){
		     $this->error('参数错误!');
		 }
		 $this->assign('Data',$Data);
		 $this->display('content');
	 }
	 
	 public function classify($id=0){
		 $ClassifyList = $this->ClassifyList;
		 if(!is_numeric($id)){
		     $id = str_replace('/','',$id);
			 foreach($ClassifyList as $k=>$v){
			     if($id==$v['ename']){
				     $id = $k;
					 break;
				 }
			 }
		 }
		 
		 $id = (int)$id;
		 
		 
		 
		 if(array_key_exists($id,$ClassifyList)){
		     
			 $ClassifyData = $ClassifyList[$id];
			 
			 
			 $where = array(
			     'cid' => array('eq',$id)
			 );
			 $page = I('get.page',1,'intval');
			 $page = $page<1?1:$page;
			 
			 $db = M("Pic");
			 
			 $pageSize = 40;
			 $DataCount = $db->where($where)->count();
			 $maxPage = ceil($DataCount/$pageSize);
			 $page = $page>$maxPage?1:$page;
			 
			 $pageLink = get_url_classify($id,'[page]');
			 
			 $PageList = FH_getPageList_Li($pageLink,$DataCount,$page,$pageSize,12);
			 if(S('classify_'.$id.'_'.$page)){
				 $DataList=S('classify_'.$id.'_'.$page);
				 #echo $id.'_'.$page;
				 #exit;
			 }else{
			 $DataList = $db->where($where)->order('lasttime desc')->page($page)->limit($pageSize)->select();
			 S('classify_'.$id.'_'.$page,$DataList,C('data_cache_name'));
			 }
			 $this->assign('ClassifyData',$ClassifyData);
			 $this->assign('DataList',$DataList);
			 $this->assign('DataCount',$DataCount);
			 $this->assign('pageSize',$pageSize);
			 $this->assign('page',$page);
			 $this->assign('id',$id);
			 $this->assign('PageList',$PageList);
			 
			 $this->display('classify');
		 }else{
		     $this->error('参数错误!',U('index'));
		 }
	 }
	 
	 
	 public function search($keyword=''){
		 $keyword = str_replace('/','',$keyword);
		 if(!$keyword){
		     $this->error('请输入你要搜索的关键词!');
		 }
		 
		 $where = array(
		     'title' => array('like','%'.$keyword.'%')
		 );
		 $page = I('get.page',1,'intval');
		 $page = $page<1?1:$page;
		 
		 $db = M('Pic');
		 
		 $pageSize = 40;
		 $DataCount = $db->where($where)->count();
		 
		 if($DataCount){
		     $maxPage = ceil($DataCount/$pageSize);
		     $page = $page>$maxPage?1:$page;
		     $pageLink = get_url_search($keyword,'[page]');
		     $PageList = FH_getPageList_Li($pageLink,$DataCount,$page,$pageSize,12);
		     $DataList = $db->where($where)->page($page)->limit($pageSize)->select();
		 }else{
		     $DataList = array();
			 $PageList = "";
			 $page = 1;
		 }

		 $this->assign('Keyword',$keyword);
		 $this->assign('DataList',$DataList);
		 $this->assign('DataCount',$DataCount);
		 $this->assign('pageSize',$pageSize);
		 $this->assign('page',$page);
		 $this->assign('id',$id);
		 $this->assign('PageList',$PageList);
			 
		 $this->display('search');
	 }
	 
	 public function tags($name=''){
		 
		 if($name!=''){
		     $db_tags = M('Tags');
		     $db = D('PicTagsView');
			 
			 $name = str_replace('/','',$name);
			 
			 $where = array(
			     'name' => array('eq',trim($name))
			 );
			 
			 $tags = $db_tags->where($where)->find();
			 
			 $page = I('get.page',1,'intval');
			 $page = $page<1?1:$page;
			 $pageSize = 40;
			 
			 
			 if($tags){
			     $where = array('tid'=>array('eq',$tags['id']));
				 $DataCount = $db->where($where)->count();
				 $maxPage = ceil($DataCount/$pageSize);
			     $page = $page>$maxPage?1:$page;

				 $pageLink = get_url_tags($name,'[page]');
				 
			     $PageList = FH_getPageList_Li($pageLink,$DataCount,$page,$pageSize,12);
				 
				 $DataList = $db->where($where)->page($page)->limit($pageSize)->order('id desc')->select();
			 }else{
			     $DataCount = 0;
				 $DataList = array();
				 $page = 1;
				 $pageList = '';
			 }
			 
			 $this->assign('DataList',$DataList);
			 $this->assign('DataCount',$DataCount);
			 $this->assign('pageSize',$pageSize);
			 $this->assign('page',$page);
			 $this->assign('TagsData',$tags);
			 $this->assign('PageList',$PageList);
			 
			 $this->display('tags');
		 }else{
		     $this->error('参数错误','./');
		 }
		 
		 
	 
	 }
	 
	 
	 public function pages($name=''){
	     if(!is_numeric($name)){
		     $name = trim($name);
			 $where = array(
			     'ename' => array('eq',$name)
			 );
		 }else{
		     $name = (int)$name;
			 $where = array(
			     'id' => array('eq',$name)
			 );
		 }
		 $db = M('Pages');
		 
		 $Data = $db->where($where)->find();
		 
		 if($Data){
		     $skinname = $Data['skinname'];
			 if($skinname==''){
			     $skinname = 'pages';
			 }
			 $this->assign('Data',$Data);
			 $this->display($skinname);
		 }else{
		     $this->error('信息出错了');
		 }
	 }
	 
	 
	 public function _empty($name){
         //$this->E($name);
     }
	 
	 protected function E($name){
	     //
	 }


	 
}