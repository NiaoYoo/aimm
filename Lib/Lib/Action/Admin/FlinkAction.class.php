<?php
class FlinkAction extends SysAction {
     public function index(){
		 $db = M('Flink');
		 $DataList = $db->order('myorder')->select(); 
		 $this->assign('DataList',$DataList);
		 $this->display();
     }
	 
	 public function add(){
	     $this->edit(0);
	 }
	 
	 public function edit($id=0){
	     $id = (int)$id;
		 if($id<1){
		     $id = 0;
			 $Data = array(
			     'id'=>0,
				 'islock'=>0,
				 'gid'=>0,
				 'user'=>'',
				 'password'=>''
			 );
			 $submiturl = U("insert");
		 }else{
		     $db = M('Flink');
		     $Data = $db->find($id);
			 $submiturl = U("update");
		 }
		 $this->assign('submiturl',$submiturl);
		 $this->assign('Data',$Data);
		 $this->assign('id',$id);
		 $this->display('edit');
	 }
	 
	 
	  public function insert(){
	     
		 $db = D('Flink');
		 if($db->create()){
			 $data = $db->add();
			 if($data){
				 $this->success('添加成功',U('index'));
			 }else{
			     $this->error('添加失败');
			 }
		 }else{
		     $this->error($db->getError());
		 }
	 }
	 
	 public function update(){
	     $db = D('Flink');
		 if($db->create()){
			 $data = $db->save();
			 if($data!==false){
				 $this->success('修改成功',U('index'));
			 }else{
			     $this->error('修改失败');
			 }
		 }else{
		     $this->error($db->getError());
		 }
	 }
	 
	 public function del($id=0){
	     $id = (int)$id;
		 if($id<1){
		     $this->error('非法参数');
		 }else{
		     $db = M('Flink');
			 $data = $db->delete($id);
			 if($data!==false){
			     $this->success('删除成功');
			 }else{
			     $this->error('删除失败');
			 }
		 }
	 }
	 
	 public function setlock($id=0){
	     $id = (int)$id;
		 if($id<1){
		     $this->error('非法参数');
		 }else{
		     $db = M('Flink');
			 $data = $db->find($id);
			 if($data){
			     $islock = $data['islock']?0:1;
				 $db->where('id='.$id)->setField('islock',$islock);
				 $this->success('设置完成!');
			 }else{
			     $this->error('设置出错');
			 }
		 }
		 
	 
	 }
}