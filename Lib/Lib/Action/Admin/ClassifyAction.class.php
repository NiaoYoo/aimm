<?php
class ClassifyAction extends SysAction {
     public function index(){
	     $ClassifyList = FH_getClassify();
		 $this->assign('ClassifyList',$ClassifyList);
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
			 $db = M('Classify');
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
	     $db = D('Classify');
		 if($db->create()){
			 $classify = $db->add();
			 if($classify){
				 FH_getClassify(false);
				 $this->success('栏目添加成功',U('index'));
			 }else{
			     $this->error('栏目添加失败');
			 }
		 }else{
		     $this->error($db->getError());
		 }
	 }
	 
	 public function update(){
	     $db = D('Classify');
		 if($db->create()){
			 $classify = $db->save();
			 if($classify!==false){
			     FH_getClassify(false);
				 $this->success('栏目修改成功',U('index'));
			 }else{
			     $this->error('栏目修改失败');
			 }
		 }else{
		     $this->error($db->getError());
		 }
	 }
	 
	 public function del($id=0){
		 $db = M('Classify');
		 $re = $db->delete($id);
		 if($re!==false){
		     FH_getClassify(false);
			 $this->success('成功删除'.$re.'个栏目',U('index'));
		 }else{
		     $this->error('删除栏目失败');
		 }
	 }
	 
	 public function dels(){
	     $id = I('post.id');
		 $id = array_filter(array_filter($id,'intval'));
         if($id){
		     $db = M('Classify');
			 $where = array(
		         'id' => array('in',$id)
		     );
			 $m = $db->where($where)->delete();
			 FH_getClassify(false);
			 $this->success('成功删除'.$m.'个栏目',U('index'));
		 }else{
		     $this->error('请选择要删除的栏目!');
		 }
	 }
	 
	 public function edits(){
	     $id = I('post.id');
		 $myorder = I('post.myorder');
		 $db = M('Classify');
		 $m = 0;
		 foreach($id as $r){
		     $r = (int)$r;
			 $order = (int)$myorder[$r];
			 $t = $db->where('id='.$r)->save(array('myorder'=>$order));
			 if($t!==false){
			     $m++;
			 }
		 }
		 FH_getClassify(false);
		 $this->success('成功修改'.$m.'个栏目!',U('index'));
	 }
	 
	 public function shift(){
	     $ClassifyList = FH_getClassify();
		 $this->assign('ClassifyList',$ClassifyList);
		 $this->display();
	 }
	 
	 public function setshift(){
	     $id = I('post.id');
		 $cid = I('post.cid',0,'intval');
		 
		 if(!$id){
		     $this->error('请选择要转移数据的栏目');
		 }
		 
		 if(!$cid){
		     $this->error('请选择目标栏目');
		 }
		 $db = M('Pic');
		 $db_tagsdata = M('Tagsdata');
		 foreach($id as $r){
		     $db->where('cid='.$r)->setField('cid',$cid);
		     $db_tagsdata->where('cid='.$r)->setField('cid',$cid);
		 }
	     $this->success('数据转移成功!',U('shift'));
	 }
	 
	 
}