<?php
class AdminModel extends Model{
     protected $_validate = array(
		 array('user','/^[a-zA-Z0-9]{1,19}$/','用户名只能由数字及字母组成'),
		 array('user','','用户名已被占用',0,'unique'),
		 array('password','6,16','密码长度应在6~16个字符之间',0,'length',1),
		 array('password','6,16','密码长度应在6~16个字符之间',2,'length',2),
		 
     );
   
     protected $_auto = array( 
         array('password','getPassword',3,'callback'),
		 array('lasttime','time',1,'function')
     );
	 
	 protected function getPassword($pw){
	     $id = I('post.id');
		 if(!$id){
		     $pw = FH_Crypt($pw);
		 }else{
		     if($pw!==''){
			     $pw = FH_Crypt($pw);
			 }else{
			     $db = M('Admin');
				 $data = $db->find($id);
				 $pw = $data['password'];
			 }
		 }
		 return $pw;
	 }
	 
}