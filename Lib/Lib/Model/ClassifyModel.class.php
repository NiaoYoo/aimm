<?php
class ClassifyModel extends Model{
     protected $_validate = array(
         array('name','1,20','栏目名称长度应在1~20个字符之间',3,'length'),
		 array('ename','/^[a-zA-Z0-9_-]{1,19}$/','栏目英文名称不合法'),
		 array('ename','','栏目英文名称重复',3,'unique'),
     );
   
     protected $_auto = array( 
         array('name','trim',3,'function'),
		 array('myorder','intval',3,'function'),
     );
}