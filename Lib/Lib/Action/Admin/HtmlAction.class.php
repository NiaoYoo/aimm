<?php
class HtmlAction extends SysAction {
     protected $_PicWhere = array();
	 
	 protected function _initialize(){
		 C('DEFAULT_THEME','default');
		 
		 $ClassifyList = FH_getClassify();
		 $PagesList = FH_getPages();
		 $ConfigData = FH_getConfig();
		 
		 
		 $this->assign('ClassifyList',$ClassifyList);
		 $this->assign('PagesList',$PagesList);
		 $this->assign('ConfigData',$ConfigData);

		 //$this->display('Home@/Index_indexs');
	 }
	 
	 public function countpic(){
		 $pageSize = I('get.pagesize',0,'intval');
		 $pageSize = $pageSize<1?1:$pageSize;
		 $id = I('post.id');
		 
		 $where = array();
		 
		 if($id){
		     $ids = explode(',',$id);
			 $ids = array_filter(array_filter($ids,'intval'));
			 if($ids){
			     $where['id'] = array('in',$ids);
			 }
		 }

         $cid = I('post.cid');
		 
		 if($cid!==''){
		     $where['cid'] = $cid;
		 }
		 
		 $db = M('Pic');
		 $Count = $db->where($where)->count();
		 $PageCount = ceil($Count/$pageSize);
			 
		 $arr = array(
			 'count' => $Count,
			 'pagecount' => $PageCount
		 );
		 $this->showJson($arr);
         		 
	 }
	 
	 public function pic(){
	     
		 $this->P_getWhere();
		 $where = $this->_PicWhere;
		 
		 $pageSize = I('get.pagesize',0,'intval');
		 $pageSize = $pageSize<1?1:$pageSize;
		 $pageSize = 100;
		 $page = I('get.page',0,'intval');
		 $db = M('Pic');
		 $DataArr = $db->where($where)->page($page)->limit($pageSize)->select();
		 
		 foreach($DataArr as $Data){
			 $this->M_pic($Data);
		 }

		 $arr = array('status'=>1);
		 $this->showJson($arr);
	 }
	 
	 public function classify(){
	     $db = M('Pic');
		 
		 $pageSize = I('get.pagesize',0,'intval');
		 $pageSize = $pageSize<1?1:$pageSize;
		 $page = I('get.page',0,'intval');
		 $page = $page<1?1:$page;
		 
		 $cid = I('post.id',0,'intval');
		 
		 $where = array('cid'=>array('eq',$cid));
		 $DataCount = $db->where($where)->count();
         
		 if(!$DataCount){
		     $arr = array('status'=>1,'pagecount'=>0,'aaa'=>I('post.id'));
		     $this->showJson($arr);
		 }else{
		     $ClassifyPageSize = 40;
			 $ClassifyPageCount = ceil($DataCount/$ClassifyPageSize); //共有多个页需要生成
			 
			 $PageCount = ceil($ClassifyPageCount/$pageSize);
			 
			 if($page>$PageCount){
			     $arr = array('status'=>1,'pagecount'=>0,'mmm'=>'sdada');
		         $this->showJson($arr);
			 }else{
			     //符合生成条件开始生成
				 $bNum = ($page-1)*$pageSize+1; //此次生成的起使页
				 $oNum = $bNum+$pageSize;
				 $oNum = $oNum>$ClassifyPageCount?$ClassifyPageCount:$oNum; //校正最大值,防止超出总页数
				 
				 for($i=$bNum;$i<$oNum;$i++){
				     $this->M_classify($cid,$i);
				 }
				 $arr = array('status'=>1,'pagecount'=>$PageCount);
		         $this->showJson($arr);
			 }
		 }
		 
	 }
	 
	 protected function M_classify($id,$page){
	     $db = M("Pic");
		 
		 $ClassifyList = $this->ClassifyList;
		 $ClassifyData = $ClassifyList[$id];

		 $pageSize = 40;
		 $where = array('cid'=>array('eq',$id));
		 
		 
		 $DataCount = $db->where($where)->count();
		 $maxPage = ceil($DataCount/$pageSize);
		 $page = $page>$maxPage?1:$page;
			 
		 $pageLink = get_url_classify($id,'[page]');
		 $PageList = FH_getPageList_Li($pageLink,$DataCount,$page,$pageSize,12);
		 $DataList = $db->where($where)->page($page)->limit($pageSize)->select();
			 
		 $this->assign('ClassifyData',$ClassifyData);
		 $this->assign('DataList',$DataList);
		 $this->assign('DataCount',$DataCount);
		 $this->assign('pageSize',$pageSize);
		 $this->assign('page',$page);
		 $this->assign('id',$id);
		 $this->assign('PageList',$PageList);
	     
		 $classifypath = get_url_classify($id,$page,false);
		 $m = get_explode_url($classifypath);

		 $this->buildHtml($m['name'],'./'.$m['path'].'/','Home@/Index_classify');
	 
	 }
	 
	 
	  public function countpages(){
	     $pageSize = I('get.pagesize',0,'intval');
		 $pageSize = $pageSize<1?1:$pageSize;
		 $id = I('post.id');
		 
		 $where = array();
		 
		 if($id){
		     $ids = explode(',',$id);
			 $ids = array_filter(array_filter($ids,'intval'));
			 if($ids){
			     $where['id'] = array('in',$ids);
			 }
		 }
		 
		 $db = M('Pages');
		 $Count = $db->where($where)->count();
		 $PageCount = ceil($Count/$pageSize);
			 
		 $arr = array(
			 'count' => $Count,
			 'pagecount' => $PageCount
		 );
		 $this->showJson($arr);
	 }
	 
	 public function pages(){
		 $id = I('post.id');
		 $where = array();
		 if($id){
		     $ids = explode(',',$id);
			 $ids = array_filter(array_filter($ids,'intval'));
			 if($ids){
			     $where['id'] = array('in',$ids);
			 }
		 }

		 $pageSize = I('get.pagesize',0,'intval');
		 $pageSize = $pageSize<1?1:$pageSize;
		 $pageSize = 100;
		 $page = I('get.page',0,'intval');
		 $db = M('Pages');
		 $DataArr = $db->where($where)->order('lasttime desc')->page($page)->limit($pageSize)->select();
		 
		 foreach($DataArr as $Data){
			 $this->M_pages($Data);
		 }

		 $arr = array('status'=>1);
		 $this->showJson($arr);
	 }
	 
	 
	 public function index(){
	     $this->M_index();
		 $arr = array('status'=>1);
		 $this->showJson($arr);
	 }
	 
	 protected function M_pic($Data){
	     $this->assign('Data',$Data);
		 $path = get_url_pic($Data,false);
		 $m = get_explode_url($path);
		 $this->buildHtml($m['name'],'./'.$m['path'].'/','Home@/Index_content');
	 }
	 
	 protected function M_pages($Data){
	     
		 $this->assign('Data',$Data);
		 
		 $skinname = $Data['skinname']!=''?$Data['skinname']:'pages';
		 $path = get_url_pages($Data,false);
		 $m = get_explode_url($path);
		 
		 $this->buildHtml($m['name'],'./'.$m['path'].'/','Home@/Index_'.$skinname);
	 }
	 
	  protected function M_index(){
		 $this->buildHtml('index','./','Home@/Index_index');
	  }
	 
	 protected function P_getWhere(){
		 $id = I('post.id');
		 $where = array();
		 
		 if($id){
		     $ids = explode(',',$id);
			 $ids = array_filter(array_filter($ids,'intval'));
			 if($ids){
			     $where['id'] = array('in',$ids);
			 }
		 }

         $cid = I('post.cid');
		 
		 if($cid!==''){
		     $where['cid'] = $cid;
		 }
		 
		 $this->_PicWhere = $where;
		 
	 }
	 
	 protected function showJson($arr){
	     exit(json_encode($arr));
	 }
	 
	 
	 
}