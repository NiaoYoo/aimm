<?php
class LoginAction extends Action {
     public function index(){
		if (!$_SESSION['AdminLogin']) {
			header("Content-Type:text/html; charset=utf-8");
			echo('请从后台管理入口登录。');
			exit();
		}
		if ($_SESSION[C('USER_AUTH_KEY')]) {
			redirect("?g=Admin&m=Index&a=show");
		}
		$this->display();
	 }
	 
	 public function out(){
	     session(C('USER_AUTH_KEY'),null);
	     session('admin_user',null);
		 session('admin_password',null);
		 session_destroy();
		 $this->redirect('index');
	 }
	 
	 public function into(){
	     $user = I("post.user","","trim");
		 if($user==""){
		     $this->error("帐号不能为空");
		 }
		 $password = I("post.password","","trim");
		 if($password==""){
		     $this->error("密码不能为空");
		 }

		 $db = M("Admin");
		 $admin = $db->where(array("user"=>strtolower($user),"password"=>FH_Crypt($password)))->find();
		 if($admin){
		     if($admin["islock"]==0){
			     session(C('USER_AUTH_KEY'),$admin["id"]);
			     session("admin_user",$admin["user"]);
			     session("admin_password",$admin["password"]);
				 
				 $data = array(
				     "loginnum" => $admin["loginnum"]+1,
					 "lasttime" => time(),
					 "lastip" => get_client_ip()
				 );
				 $db->where(array("id"=>$admin["id"]))->setField($data);
			     $this->success("登陆成功",U("Admin/Index/show"));
			 }else{
			    $this->error("当前帐号已被锁定,请联系网站管理员!",U("show"));
			 }
		 }else{
		     $this->error("密码与帐号不匹配",U("show"));
		 }
	 }

}
