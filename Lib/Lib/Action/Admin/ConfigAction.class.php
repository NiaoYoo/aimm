<?php
class ConfigAction extends SysAction {
     public function index(){
	     $ConfigData = FH_getConfigData();
		 $this->assign('ConfigData',$ConfigData);
		 $this->display();
     }
	 
	 
	 
	 public function pic(){
	     $this->display();
	 }
	 
	 
	 public function update(){
		 $config = $_POST["config"];
	     $db = M('Config');
	     $db->query('truncate __TABLE__');
		 foreach($_POST as $k=>$v){
			 $arr = explode('_',$k);
			 $data = array(
			     'mid' => $arr[0],
				 'sign' => $arr[1],
				 'type' => $arr[2],
				 'value' => $v
			 );
             $db->add($data);
		 }
		 FH_getConfig(false);
		 FH_getConfigData(false);
		 $this->success('更新成功!');
	 }
	 
	 
	 
	 
}