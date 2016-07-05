<?php
class AdminAction extends SysAction {
     public function index(){
		 $db = M('Admin');
		 $DataList = $db->select(); 
		 
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
		     $db = M('Admin');
		     $Data = $db->find($id);
			 $submiturl = U("update");
		 }
		 $this->assign('submiturl',$submiturl);
		 $this->assign('Data',$Data);
		 $this->assign('id',$id);
		 $this->display('edit');
	 }
	 
	 
	  public function insert(){
	     
		 $db = D('Admin');
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
	     $db = D('Admin');
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
		     $db = M('Admin');
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
		     $db = M('Admin');
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
	 
	 
	 public function editpassword(){
	     $admin = $this->SysAdmin;
		 $pw = I("post.pw","","trim");
		 
		 if(FH_Crypt($pw)!==$admin['password']){
		     $this->error('原密码错误');
		 }
		 
		 $pw1 = I("post.pw1","","trim");
		 $pw2 = I("post.pw2","","trim");
		
		 
		 if(strlen($pw1)<6 || strlen($pw1)>20){
		     $this->error('密码长度应在6~20之间');
		 }
		 if($pw1!==$pw2){
		     $this->error('两次输入的密码不一致');
		 }
		 
		 $newpassword = FH_Crypt($pw1);
		 
		 $db = M('Admin');
		 $m = $db->where('id='.$admin['id'])->save(array('password'=>$newpassword));
		 if($m!==false){
			  session('admin_password',$newpassword);
			  $this->success('密码修改成功');
		 }else{
		     $this->error('系统出错,稍后重试');
		 }
		
	 }
}