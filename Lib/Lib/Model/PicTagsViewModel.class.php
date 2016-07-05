<?php
class PicTagsViewModel extends ViewModel{
	 public $viewFields = array(
         'Tagsdata'=>array('tid','pid','_type'=>'left'),
         'Pic'=>array('title','titlepic','cid','hits','good','addtime','pnum','star','tags','id','_on'=>'Tagsdata.pid=Pic.id')
     );
}