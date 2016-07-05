<?php
class FlinkModel extends Model{
     protected $_validate = array(
		 array('name','require','网站名称必须填写'), 
		 array('name','1,20','网站名称不能超过20个字符',0,'length',3),
		 array('link','require','网站地址必须填写'), 
     );
   
     protected $_auto = array( 
         array('myorder','intval',3,'function')
     );
	 
}