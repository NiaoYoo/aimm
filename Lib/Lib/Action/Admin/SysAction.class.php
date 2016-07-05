<?php
class SysAction extends Action {
     protected function _initialize(){
	     $this->islogin();
	 }
	 
	 
	 private function islogin(){
	     $admin_user = session('admin_user');
		 $admin_password = session('admin_password');
		 if($admin_user && $admin_password){
		     $db = M('Admin');
			 $where = array('user'=>$admin_user,'password'=>$admin_password);
			 $admin = $db->where($where)->find();
			 if($admin){
                 if($admin['islock']){
				     $this->error('帐号已被管理员锁定!',U('Admin/Login/index'));
				 }else{
				     $this->assign('SysAdmin',$admin);
				 }
			 }else{
			     $this->error('帐号状态已被修改,请重新登陆!',U('Admin/Login/index'));
			 }
		 }else{
		     $this->error('帐号未登陆或登陆超时,请重新登陆!',U('Admin/Login/index'));
		 }
	 }


}