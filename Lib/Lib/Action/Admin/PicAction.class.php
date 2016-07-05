<?php
class PicAction extends SysAction {
     public function index(){
	     
		 $ClassifyList = FH_getClassify();
		 $ConfigArray = FH_getConfig();
		 
		 $urlattr = array();
		 $where = array(
		     'id' => array('gt',0)
		 );
		 
		 $page = I('get.page',1,'intval');
		 $page = $page>0?$page:1;
		 
		 $urlattr['page'] = $page;
		 
		 $desc = I('get.desc',1,'intval');
		 if($desc){
		     $desc = 'desc';
			 $urlattr['desc'] = 1;
		 }else{
		     $desc = 'asc';
			 $urlattr['desc'] = 0;
		 }
		 
		 $SearchOrderList = array('id','addtime','hits','good','bad','lasttime');
		 $order = I('get.order');
		 
		 if(!in_array($order,$SearchOrderList)){
		     $order = 'lasttime';
		 }
		 $urlattr['order'] = $order;
		 
		 $SearchFieldList = array('id'=>'视频ID','title'=>'标题','keys'=>'关键词','content'=>'介绍','tags'=>'Tags');
		 $field = I('get.field');
		 
		 if(!$field || $field == 'all'){
		     $field = 'all';
		 }else{
		     if(!$SearchFieldList[$field]){
		         $field = 'all';
		     }
		 }
		 
		 $urlattr['field'] = $field;

		 $keyword = I('get.keyword','','trim');
		 $urlattr['keyword'] = $keyword;

		 if($keyword!==''){
		     switch($field){
			     case 'title' :
				     $where['title'] = array('like','%'.$keyword.'%');
					 break;
				 case 'keys' :
				     $where['keys'] = array('like','%'.$keyword.'%');
					 break;
				 case 'content' :
				     $where['content'] = array('like','%'.$keyword.'%');
					 break;
				 case 'tags' :
				     $where['tags'] = array('like','%'.$keyword.'%');
					 break;
				 case 'id' :
                     if(strpos($keyword,'gt')===0){
					     $where['id'] = array('gt',(int)str_replace('gt','',$keyword));
					 }elseif(strpos($keyword,'lt')===0){
					     $where['id'] = array('lt',(int)str_replace('lt','',$keyword));
					 }else{
					     $search_ids = str_replace(array('|','/',' ','，','、'),',',$keyword);
						 $search_ids = explode(',',$search_ids);
						 $search_ids = array_filter(array_filter($search_ids,'intval'));
						 if($search_ids){
						     $where['id'] = array('in',implode(',',$search_ids));
						 }
					 }
					 break;				 
				 case 'all' :
				     $where['title|keys|tags|content'] = array('like','%'.$keyword.'%');
					 break;
			 }
		 }
		
		 
		 $cid = I('get.cid',0,'intval');
		 $cid = $cid<1?0:$cid;
		 $urlattr['cid'] = $cid;
		 
		 if($cid){
		     $where['cid'] = $cid;
		 }
		 
		 
		 $position = I('get.position');
		 $urlattr['position'] = $position;
		 if($position!==''){
		     $where['position'] = array('like','|%'.$position.'%|');
		 }
		 
		 $star = I('get.star',0,'intval');
		 $urlattr['star'] = $star;
		 if($star){
		     $where['star'] = $star;
		 }
		 
		 $pageNum = 30;
		 
		 $db = M('Pic');
		 $DataCount = $db->where($where)->count();
		 
		 $pageAttr = $urlattr;
		 $pageAttr['page']='[page]';
		 
		 
		 $pageLink = U('index',$pageAttr);
		 $pageLink = str_replace('%5Bpage%5D','[page]',$pageLink);
		 

		 $PageList = FH_getPageList($pageLink,$DataCount,$page,$pageNum,10);
		 
		 $DataList = $db->where($where)->order($order.' '.$desc)->page($page,$pageNum)->select();
		 
		 
		 
		 $this->assign('SearchFieldList',$SearchFieldList);
		 $this->assign('SearchOrderList',$SearchOrderList);
		 $this->assign('urlattr',$urlattr);
		 $this->assign('PageList',$PageList);
		 $this->assign('ConfigArray',$ConfigArray);
		 $this->assign('ClassifyList',$ClassifyList);
		 $this->assign('DataList',$DataList);
		 $this->display();
     }
	 
	 
	  public function search(){
	     $urlattr = array(
		     'page' => 1,
			 'desc' => 1,
			 'order' => 'lasttime',
			 'keyword' => I('post.keyword',''),
			 'field' => I('post.field',''),
			 'cid' => I('post.cid',0,'intval'),
			 'position' => I('post.position',''),
			 'star' => I('post.star','')
		 );
		 $this->redirect('index',$urlattr,0,'');
	 }
	 
	 public function add(){
	     $this->edit(0);
	 }
	 
	 public function edit($id=0){
	     $ClassifyList = FH_getClassify();
		 $ConfigArray = FH_getConfig();
		 
		 if(!$id){
		     $submiturl = U("insert");
			 $Data = array(
			     'id' => 0,
				 'addtime' => time(),
				 'cid' => 0
			 );
		 }else{
		     $submiturl = U("update");
			 $db = M('Pic');
			 $Data = $db->find($id);
		 }
		 
		 $this->assign('submiturl',$submiturl);
		 $this->assign('Data',$Data);
		 $this->assign('ConfigArray',$ConfigArray);
		 $this->assign('ClassifyList',$ClassifyList);
		 $this->display('edit');
	 }
	 
	 public function insert(){
	     $db = D('Pic');
		 if($db->create()){
			 $data = $db->add();
			 if($data){
				 $tags = I('post.tags','','');
				 $this->_Tags($data,$tags);
				 $this->success('添加成功',U('index'));
			 }else{
			     $this->error('添加失败');
			 }
		 }else{
		     $this->error($db->getError());
		 }
	 }
	 
	 
	 public function update(){
	     $db = D('Pic');
		 if($db->create()){
			 $data = $db->save();
			 if($data!==false){
                 $id = I('post.id',0,'intval');
				 $tags = I('post.tags','','');
				 $this->_Tags($id,$tags);
				 $this->success('修改成功');
			 }else{
			     $this->error('修改失败');
			 }
		 }else{
		     $this->error($db->getError());
		 }
	 }
	 
	 public function del($id=0){
	     $id = (int)$id;
		 if($id){
		     $db = M('Pic');
			 $m = $db->delete($id);
			 if($m){
			     $db_tags = M('Tags');
				 $db_tagsdata = M('Tagsdata');
				 $tids = $db_tagsdata->field('tid')->where('pid='.$id)->select();
				 if($tids){
				     $db_tagsdata->where('pid='.$id)->delete();
					 foreach($tids as $r){
					     $tid = $r["tid"];
					     $tCount =  $db_tagsdata->where('tid='.$tid)->count();
						 $db_tags->where('id='.$tid)->setField('num',$tCount);
					 }
				 }
				 $this->error('删除成功!');
			 }else{
			     $this->error('删除失败!');
			 }
		 }else{
		     $this->error('非法参数');
		 }
	 }
	 
	 
	 public function dels(){
	     $id = I('post.id');
		 $id = array_filter($id,'intval');
		 
		 if($id){
		     $db = M('Pic');
			 $m = $db->where(array('id'=>array('in',$id)))->delete();
			 
			 if($m!==false){
			     $db_tags = M('Tags');
				 $db_tagsdata = M('Tagsdata');
				 $where = array('pid'=>array('in',$id));
				 $tids = $db_tagsdata->field('tid')->where($where)->select();
				 if($tids){
				     $db_tagsdata->where($where)->delete();
					 foreach($tids as $r){
					     $tid = $r["tid"];
					     $tCount =  $db_tagsdata->where('tid='.$tid)->count();
						 $db_tags->where('id='.$tid)->setField('num',$tCount);
					 }
				 }
				 $this->success('成功删除'.$m.'个图片集!');
			 }else{
			     $this->error('删除失败!');
			 }
		 }else{
		     $this->error('请选择要删除的图片集!');
		 }
	 }
	 
	 public function edits(){
	     $id = I('post.id');
		 $id = array_filter($id,'intval');
		 
		 if($id){
		     $db = M('Pic');
		    	 $where = array(
		    	     "id" => array("in",$id)
		    	 );
				 //position
				 $data = array(
				     'star'=> I('post.setstar',0,'intval'),
				 );
				 $db->where($where)->setField($data);
				 $this->success('设置成功');
		 }else{
		     $this->error('请选择要设置的图片集!');
		 }
	 
	 }
	 
	 protected function _Tags($id=0,$tags=''){
	     
		 if(!$id || $tags==''){
		     return;
		 }else{
			$tags = str_replace(array("/","$"," ","\\","、","，"),",",$tags);
			
			$tags_arr = explode(',',$tags);
			$cid = I('post.cid',0,'intval');
			
			$db = M('Tags');
			$db_data = M('Tagsdata');
			
			$db_data->where('pid='.$id)->delete(); //清理当前视频原Tags
			
			foreach($tags_arr as $k){
			     $k = trim($k);
			     if($k!==''){
				     $TagsData = $db->where('name="'.$k.'"')->find();
				     if($TagsData){
					     $tid = $TagsData['id'];
						 $db_data->add(array('pid'=>$id,'cid'=>$cid,'tid'=>$tid));
						 $tCount = $db_data->where('tid='.$tid)->count();
						 $db->where('id='.$tid)->setField('num',$tCount);
					 }else{
					     $tid = $db->add(array('name'=>$k,'num'=>1));
						 if($tid){
						     $db_data->add(array('pid'=>$id,'cid'=>$cid,'tid'=>$tid));
							 $tCount = $db_data->where('tid='.$tid)->count();
						     $db->where('id='.$tid)->setField('num',$tCount);
						 }
					 }
				 }
			};
			
			
			
		 }
	     
	 
	 }
	 
	 
}