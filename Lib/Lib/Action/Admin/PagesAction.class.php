<?php
class PagesAction extends SysAction {
     public function index(){
	     $db = M('Pages');
		 $page = I('get.page',1,'intval');
		 $page = $page>0?$page:1;
		 
		 $pageSize = 30;
		 $DataCount = $db->count();
		 
		 $pageAttr = array('page'=>'[page]');
		 $pageLink = U('index',$pageAttr);
		 $pageLink = str_replace('%5Bpage%5D','[page]',$pageLink);
		 $PageList = FH_getPageList($pageLink,$DataCount,$page,$pageSize,10);
		 
		 $DataList = $db->order('myorder')->page($page,$pageNum)->select();
		 
		 $this->assign('PageList',$PageList);
		 $this->assign('DataList',$DataList);
		 $this->display();
     }
	 
	 public function add(){
	     $this->edit(0);
	 }
	 
	 public function edit($id=0){
		 if(!$id){
		     $submiturl = U("insert");
			 $Data = array(
			     'id' => 0
			 );
		 }else{
		     $submiturl = U("update");
			 $db = M('Pages');
			 $Data = $db->find($id);
			 if(!$Data){
			     $Data = array(
			         'id' => 0
			     );
			 }
		 }
		 
		 $this->assign('submiturl',$submiturl);
		 $this->assign('Data',$Data);
		 $this->display('edit');
	 }
	 
	 
	 public function insert(){
	     $db = D('Pages');
		 if($db->create()){
			 $Pages = $db->add();
			 if($Pages){
				  FH_getPages(false);
				 $this->success('添加成功',U('index'));
			 }else{
			     $this->error('添加失败');
			 }
		 }else{
		     $this->error($db->getError());
		 }
	 }
	 
	 public function update(){
	     $db = D('Pages');
		 if($db->create()){
			 $Pages = $db->save();
			 if($Pages!==false){
			     FH_getPages(false);
				 $this->success('修改成功',U('index'));
			 }else{
			     $this->error('修改失败');
			 }
		 }else{
		     $this->error($db->getError());
		 }
	 }
	 
	 public function del($id=0){
		 $db = M('Pages');
		 $re = $db->delete($id);
		 if($re!==false){
		     FH_getPages(false);
			 $this->success('成功删除'.$re.'个单页',U('index'));
		 }else{
		     $this->error('删除失败');
		 }
	 }
	 
	 public function dels(){
	     $id = I('post.id');
		 $id = array_filter(array_filter($id,'intval'));
         if($id){
		     $db = M('Pages');
			 $where = array(
		         'id' => array('in',$id)
		     );
			 $m = $db->where($where)->delete();
			 FH_getPages(false);
			 $this->success('成功删除'.$m.'个单页',U('index'));
		 }else{
		     $this->error('请选择要删除的单页!');
		 }
	 }
	 
	 public function edits(){
	     $id = I('post.id');
		 $myorder = I('post.myorder');
		 $db = M('Pages');
		 $m = 0;
		 foreach($id as $r){
		     $r = (int)$r;
			 $order = (int)$myorder[$r];
			 $t = $db->where('id='.$r)->save(array('myorder'=>$order));
			 if($t!==false){
			     $m++;
			 }
		 }
		 FH_getPages(false);
		 $this->success('成功修改'.$m.'个单页!',U('index'));
	 }
}