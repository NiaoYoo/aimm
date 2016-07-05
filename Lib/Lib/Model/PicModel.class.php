<?php
class PicModel extends Model{
     protected $_validate = array(
         array('title','1,50','图片标题长度应在1~30个字符之间',3,'length')
     );
   
     protected $_auto = array( 
         array('title','trim',3,'function'),
		 array('keys','trim',3,'function'),
		 array('position','getAttr',3,'callback'),
		 array('good','intval',3,'function'),
		 array('bad','intval',3,'function'),
		 array('hits','intval',3,'function'),
		 array('star','intval',3,'function'),
		 array('addtime','getAddtime',3,'callback'),
		 array('lasttime','time',3,'function'),
		 array('picurls','getPicurls',3,'callback'),
		 array('pnum','getPnum',3,'callback'),
		 array('tags','getTags',3,'callback'),
		 array('titlepic','getTitlepic',3,'callback')
     );
	 
	 protected function getTitlepic($titlepic){
	     if($titlepic==''){
		     $pics = I('post.picurls');
			 $arr = explode('$',$pics);
			 $titlepic = $arr[0];
		 }
		 return $titlepic;
	 }
	 
	 
	 protected function getTags($tags){
	     $tags = str_replace(array("/","$"," ","\\","、","，"),",",$tags);
		 return $tags;
	 }
	 
	 protected function getAddtime($addtime){
	     if(!is_numeric($addtime)){
		     $addtime = strtotime($addtime);
		 }
		 $addtime = (int)$addtime;
		 if(!$addtime){
		     $addtime = time();
		 }
		 return $addtime;
	 }
	 
	 protected function getAttr($v){
	     if($v){
			 return '|'.implode('|',$v).'|';
		 }else{
		     return '';
		 }
	 }
	 
	 protected function getPnum(){
	     $urls = I('post.picurls');
		 $arr = explode('$',$urls);
		 $arr = array_filter($arr);
		 return count($arr);
	 }
	 
	  protected function getPicurls($v){
	     function getpath($s){
			 $s = str_replace('../','/',$s);
			 $s = str_replace('./','/',$s);
			 $s = str_replace('//','/',$s);
			 return $s;
		 }
		 if($v){
		     $arr = explode('$',$v);
		     $arr = array_filter(array_filter($arr),'getpath');
			 return implode('$',$arr);
		 }else{
		     return '';
		 }
		 
	  }
	 
}