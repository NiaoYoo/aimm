<?php
class UploadAction extends SysAction {
     public function index(){
	     $this->display();
     }
	 
	 public function pics(){
	     $bid = I('get.bid');
		 $pid = I('get.pid',0,'intval');
		 $db = M('File');
		 $FileList = $db->where('pid='.$pid)->select();

		 $this->assign('FileList',$FileList);
		 $this->assign('bid',$bid);
		 $this->assign('pid',$pid);
		 $this->display();
	 }
	 
	 public function titlepic(){
		 $this->pics();
	 }
	 
	 public function upload(){
	     import('ORG.Net.UploadFile');
		 $upload = new UploadFile();
         $upload->maxSize  = 3145728 ;
         $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
         $upload->savePath =  './Public/Uploads/'.date('Ymd',time()).'/';
		 
		 $arr = array(
		     'msg' => '上传成功',
			 'status' => 1
		 );
         if(!$upload->upload()){
			 $arr['msg'] = $upload->getErrorMsg();
			 $arr['status'] = 0;
         }else{
             $info =  $upload->getUploadFileInfo();
			 $info['savepath'] = str_replace('./Public/','/Public/',$info['savepath']);
			 $attr = array(
			     'pid' => I('post.pid',0,'intval'),
				 'picname' => I('post.picname')
			 );
			 //数据入库
			 $this->FileInsert($info,$attr);
			 
			 $arr['data'] = $info;
         }
		 exit(json_encode($arr));
	 }
	 
	 protected function FileInsert($file,$attr){
	     $db = M('File');
		 foreach($file as $r){
		     $data = array(
			     'name' => $attr['picname']?$attr['picname']:$r['name'],
				 'pid' => $attr['pid'],
				 'path' => str_replace('./','/',$r['savepath']).$r['savename'],
				 'size' => $r['size'],
				 'addtime' => time()
			 );
		     $db->add($data);
		 }
	 }
	 
	 
}


