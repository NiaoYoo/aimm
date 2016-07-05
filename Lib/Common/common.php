<?php


function FH_getClassify($t=true){
     $Classify = $t?F('Classify/data'):false;
	 if(!$Classify){
	     $db = M('Classify');
		 $Classify = $db->order('myorder')->select();
		 
		 $tem = array();
		 foreach($Classify as $k=>$v){
		     array_push($tem,$v['id']);
		 }
		 $Classify = array_combine($tem,$Classify);
		 F('Classify/data',$Classify);
	 }
	 return $Classify;
}

function FH_getPages($t=true){
     $Pages = $t?F('Pages/data'):false;
	 if(!$Pages){
	     $db = M('Pages');
		 $Pages = $db->where('iscache=1')->order('myorder')->select();
		 
		 $tem = array();
		 foreach($Pages as $k=>$v){
		     array_push($tem,$v['id']);
		 }
		 $Pages = array_combine($tem,$Pages);
		 F('Pages/data',$Pages);
	 }
	 return $Pages;
}

function FH_getConfig($t=true){
     $ConfigArray = $t?F('Config/array'):false;
	 
	 if(!$ConfigArray){
	     $db = M('Config');
	     $mid = array('pic','site','url','html');
	 
	     $ConfigArray = array();
	 
	     foreach($mid as $r){
		     $tarr = array();
		     $data = $db->where("mid='$r'")->select();
		     foreach($data as $v){
		         $tarr[$v['sign']] = FH_getConfigValue($v['value'],$v['type']);
		     }
	         $ConfigArray[$r] = $tarr;
	     }
		 F('Config/array',$ConfigArray);
	 }
	 return $ConfigArray;
}

function FH_getConfigValue($v,$t){
	 switch($t){
	     case 'r' :
             $arr = explode("\r\n",$v);
			 $arr = array_filter($arr);
			 $value = array();
			 
			 foreach($arr as $k=>$r){
			     $tem = explode("==",$r);
				 if(count($tem)>1){
				     $value[$tem[0]] = $tem[1];
				 }else{
				     $value[$k] = $tem[1];
				 }
			 }
             break;		 
	     case 'n':
		     $value = (int)$v;
			 break;
		 default :
		     $value = $v;
			 break;
	 }
	 return $value;
}

function FH_getConfigData(){
     $ConfigData = $t?F('Config/data'):false;
	 if(!$ConfigData){
	     $db = M('Config');
	     $mid = array('pic','site','url','html');
		 $ConfigData = array();
		 foreach($mid as $r){
		     $tarr = array();
			 $data = $db->where("mid='$r'")->select();
			 foreach($data as $v){
			     $tarr[$v["sign"]] = $v["value"];
			 }
			 $ConfigData[$r] = $tarr;
		 }
		 F('Config/data',$ConfigData);
	 }
	 return $ConfigData;
}

function FH_getPageList($pagelink,$cNum=0,$page=1,$pn=10,$m=5){
         $pNum = ceil($cNum/$pn); //得到总页数
         
         if(fmod($m,2)){
            $x = ($m-1)/2;
            $y = $x;
         }else{
            $x = $m/2;
            $y = $x-1;
         }
         $x = $page-$x;
         $y = $page+$y;
         
         if($x<1){
            $x = 1;
            $y = $m;
         }
         
         if($y>$pNum){
            $y = $pNum;
            $x = $pNum-$m+1;
         }
         
         if($x<1){
            $x = 1;
         }

         $a = $page-1;
         if($a<1){
            $a = 1;
         }
         $A = '<a href="'.str_replace("[page]",$a,$pagelink).'">上一页</a>';
         if($page==1){
            $A = '<span>上一页</span>';
         }
         
         $b = $page+1;
         if($b>$pNum){
            $b = $pNum;   
         }
         $B = '<a href="'.str_replace("[page]",$b,$pagelink).'">下一页</a>';
         if($page==$pNum){
            $B = '<span>下一页</span>';
         }
         
         if($x==1){
            $M = '';
         }elseif($x==2){
            $M = '<a href="'.str_replace("[page]","1",$pagelink).'">1</a>';
         }else{
            $M = '<a href="'.str_replace("[page]","1",$pagelink).'">1</a><em>...</em>';
         }
         
         if($y==$pNum){
            $N = '';
         }elseif($y==$pNum-1){
            $N = '<a href="'.str_replace("[page]",$pNum,$pagelink).'">'.$pNum.'</a>';
         }else{
            $N = '<em>...</em><a href="'.str_replace("[page]",$pNum,$pagelink).'">'.$pNum.'</a>';
         }

         $S=$M;
         for($i=$x;$i<=$y;$i++){
             $style='';
             if($i==$page){
                $style=' class="this"';
             }
             $S .= '<a'.$style.' href="'.str_replace("[page]",$i,$pagelink).'">'.$i.'</a>';
         }
         $S .= $N.$A.$B;
         $S = '<b>共'.$cNum.'个数据/'.$pNum.'页</b>'.$S;		 
         return $S;        
}


function FH_getPageList_Li($pagelink,$cNum=0,$page=1,$pn=10,$m=5){
         $pNum = ceil($cNum/$pn); //得到总页数
         
         if(fmod($m,2)){
            $x = ($m-1)/2;
            $y = $x;
         }else{
            $x = $m/2;
            $y = $x-1;
         }
         $x = $page-$x;
         $y = $page+$y;
         
         if($x<1){
            $x = 1;
            $y = $m;
         }
         
         if($y>$pNum){
            $y = $pNum;
            $x = $pNum-$m+1;
         }
         
         if($x<1){
            $x = 1;
         }

         $a = $page-1;
         if($a<1){
            $a = 1;
         }
         $A = '<li class="prev"><a href="'.str_replace("[page]",$a,$pagelink).'"></a><li>';
         if($page==1){
            $A = '<li class="prev"><a href="#"></a><li>';
         }
         
         $b = $page+1;
         if($b>$pNum){
            $b = $pNum;   
         }
         $B = '<li class="next"><a href="'.str_replace("[page]",$b,$pagelink).'"></a><li>';
         if($page==$pNum){
            $B = '<li class="next"><a href="#"></a><li>';
         }
         
         if($x==1){
            $M = '';
         }elseif($x==2){
            $M = '<li><a href="'.str_replace("[page]","1",$pagelink).'">1</a><li>';
         }else{
            $M = '<li><a href="'.str_replace("[page]","1",$pagelink).'">1</a><li><li><em>...</em></li>';
         }
         
         if($y==$pNum){
            $N = '';
         }elseif($y==$pNum-1){
            $N = '<li><a href="'.str_replace("[page]",$pNum,$pagelink).'">'.$pNum.'</a></li>';
         }else{
            $N = '<li><em>...</em></li><li><a href="'.str_replace("[page]",$pNum,$pagelink).'">'.$pNum.'</a></li>';
         }

         $S=$M;
         for($i=$x;$i<=$y;$i++){
             $style='';
             if($i==$page){
                $style=' class="pagecur"';
             }
             $S .= '<li'.$style.'><a href="'.str_replace("[page]",$i,$pagelink).'">'.$i.'</a></li>';
         }
         $S = $A.$S.$N.$B;
		 
		 $S = str_replace('/1.html','/',$S);
		 
         return $S; 
}

function FH_getTags($tags,$n=0){
     $arr = explode(',',$tags);
	 if($n){
	     return array_slice($arr,0,$n);
	 }else{
	     return $arr;
	 }
}




//获取链接函数
function get_url_pic($data,$istrue=true){
     $ConfigData = FH_getConfig();
	 $UrlModel = $ConfigData['url']['site'];
	 
	 if($UrlModel==1){
	     return './index.php?a=content&id='.$data["id"];
	 }else{
	     if($UrlModel){
		     $url = $ConfigData['url']['contenturl']; //静态
		 }else{
		     $url = $ConfigData['url']['contenturl2']; //伪静态
		 }
		 
		 if(!$url){
		     $url = 'content/{id}/index.html';
		 }
		 
		 $ClassifyData = FH_getClassify();
	     $Classify = $ClassifyData[$vod['vod_cid']];
		 $arr = array(
		     'cid' => $data["cid"],
			 'cpath' => $Classify["ename"],
			 'y' => date('Y',$data["addtime"]),
		     'm' => date('m',$data["addtime"]),
		     'd' => date('d',$data["addtime"]),
		     'time' => $data["addtime"],
			 'id' => $data["id"],
			 'fmod' => fmod($data["id"],255)
		 );
		 foreach($arr as $k=>$v){
		     $url = str_replace('{'.$k.'}',$v,$url);
		 }
		 
		 if($istrue){
		     $url = $ConfigData['site']['domain'].'/'.$url;
		     //$url = str_replace('//','/',$url);
		 }
		 return $url;
	 }
}

function get_url_classify($cid,$page=1,$istrue=true){
     $ConfigData = FH_getConfig();
	 $UrlModel = $ConfigData['url']['site'];
	 $cid = (int)$cid;
	 
	 if($UrlModel==1){
		 //动态
		 if($page==1){
		     return './index.php?a=classify&id='.$cid;
		 }else{
		     return './index.php?a=classify&id='.$cid.'&page='.$page;
		 }
	 }else{
		 if($UrlModel){
		     $url = $ConfigData['url']['classifyurl']; //静态
		 }else{
		     $url = $ConfigData['url']['classifyurl2']; //伪静态
		 }

		 if(!$url){
		     $url = 'classify/{cid}/{page}.html';
		 }

		 $ClassifyData = FH_getClassify();
	     $Classify = $ClassifyData[$cid];
		 $arr = array(
		     'cid' => $cid,
			 'cpath' => $Classify["ename"]
		 );
		 
		 if($page==1){
		     $url = str_replace('{page}','',$url);
			 $url = str_replace('/.html','/',$url);
		 }else{
		     $url = str_replace('{page}',$page,$url);
		 }
		 
		 foreach($arr as $k=>$v){
		     $url = str_replace('{'.$k.'}',$v,$url);
		 }
		 
		 if($istrue){
		     $url = $ConfigData['site']['domain'].$url;
		     //$url = str_replace('//','/',$url);
		 }
		 return $url;
	 }
}

function get_url_tags($tags,$page=1){
     $ConfigData = FH_getConfig();
	 $UrlModel = (int)$ConfigData['url']['tags'];
	 if($UrlModel){
	     if($page==1){
		     return '/index.php?a=tags&name='.urlencode($tags);
		 }else{
		     return '/index.php?a=tags&name='.urlencode($tags).'&page='.$page;
		 }
	 }else{
	     if($page==1){
		     return '/tags/'.urlencode($tags).'/';
		 }else{
		     return '/tags/'.urlencode($tags).'/'.$page.'/';
		 }
	 }
	 
	 $url = $ConfigData['site']['domain'].'/'.$url;
	 //$url = str_replace('//','/',$url);
	 
	 return $url;
	 
}

function get_url_search($keyword,$page=1){
     $ConfigData = FH_getConfig();
	 $UrlModel = (int)$ConfigData['url']['search'];
	 if($UrlModel){
	     if($page==1){
		     $url = '/index.php?a=search&name='.urlencode($keyword);
		 }else{
		     $url = '/index.php?a=keyword&name='.urlencode($keyword).'&page='.$page;
		 }
	 }else{
	     if($page==1){
		     $url = '/search/'.urlencode($keyword).'/';
		 }else{
		     $url = '/search/'.urlencode($keyword).'/'.$page.'/';
		 }
	 }
	 $url = $ConfigData['site']['domain'].$url;
	 //$url = str_replace('//','/',$url);
	 return $url;
}

function get_url_pages($data,$istrue=true){
     $ConfigData = FH_getConfig();
	 $UrlModel = (int)$ConfigData['url']['site'];
	 
	 $arr = array(
		'id' => $id,
		'ename' => $data["ename"]
	 );
	 
	 
	 if($UrlModel==1){
	     $url =  './index.php?a=pages&name='.$data["ename"];
	 }else{
	     if($UrlModel){
		     $url = $ConfigData['url']['pagesurl']; //静态
		 }else{
		     $url = $ConfigData['url']['pagesurl2']; //伪静态
		 }

		 if(!$url){
		     $url = 'pages/{ename}.html';
		 }
		 
		 foreach($arr as $k=>$v){
		     $url = str_replace('{'.$k.'}',$v,$url);
		 }
		 
		 if($istrue){
		     $url = $ConfigData['site']['domain'].'/'.$url;
		     //$url = str_replace('//','/',$url);
		 }
	 }
	 return $url;

}


function get_explode_url($url){
     $arr = explode('/',$url);
	 $name = end($arr);
	 $name = str_replace(array('.htmls','.html','.htm'),'',$name);
	 if($name==''){
	     $name = 'index';
	 }
	 array_splice($arr,count($arr)-1);
	 $path = implode('/',$arr);
	 return array('name'=>$name,'path'=>$path);
}







//加密处理

function FH_Crypt($str,$key='FHPIC'){
     import('ORG.Crypt.Crypt');
	 $Crypt = new Crypt();
	 $m = $Crypt->encrypt($str,$key);
     return md5($m);
}






/*-------------------------------------------------文件夹与文件操作开始------------------------------------------------------------------*/
//读取文件
function read_file($l1){
	return @file_get_contents($l1);
}
//写入文件
function write_file($l1, $l2=''){
	$dir = dirname($l1);
	if(!is_dir($dir)){
		mkdirss($dir);
	}
	return @file_put_contents($l1, $l2);
}
//递归创建文件
function mkdirss($dirs,$mode=0777) {
	if(!is_dir($dirs)){
		mkdirss(dirname($dirs), $mode);
		return @mkdir($dirs, $mode);
	}
	return true;
}
// 数组保存到文件
function arr2file($filename, $arr=''){
	if(is_array($arr)){
		$con = var_export($arr,true);
	} else{
		$con = $arr;
	}
	$con = "<?php\nreturn $con;\n?>";//\n!defined('IN_MP') && die();\nreturn $con;\n
	write_file($filename, $con);
}
/*-------------------------------------------------系统路径相关函数开始------------------------------------------------------------------*/
// 转换成JS
function t2js($l1, $l2=1){
    $I1 = str_replace(array("\r", "\n"), array('', '\n'), addslashes($l1));
    return $l2 ? "document.writeln(\"$I1\");" : $I1;
}
// 获取广告调用地址
function getadsurl($str,$charset="utf-8"){
	$ConfigData = FH_getConfig();
	return '<script type="text/javascript" src="'.$ConfigData['site']['picpath'].'/'.C('admin_ads_file').'/'.$str.'.js" charset="'.$charset.'"></script>';
}
/*-------------------------------------------------字符串处理开始------------------------------------------------------------------*/


// 判断比较类函数

function FH_isToday($t1,$t2){
     return date('Y-m-d',$t1)==date('Y-m-d',$t2);
}



function g_url_contents($url){
        $ip = rand(1,255).'.'.rand(1,255).'.'.rand(1,255).'.'.rand(1,255);
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip,'CLIENT-IP:'.$ip));
		curl_setopt($ch,CURLOPT_REFERER,'http://www.95mm.com/'); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        curl_setopt($ch,CURLOPT_USERAGENT,$user_agent);
        @ $c = curl_exec($ch);
        curl_close($ch);
		
		
        return $c;
}

/*-------------------------------------------------模板常用函数-----------------------------------------------------------------*/
//获得某天前的时间戳
function getxtime($day){
	$day = intval($day);
	return mktime(23,59,59,date("m"),date("d")-$day,date("y"));
}

/* function getpath($s){
			 $s = str_replace('../','/',$s);
			 $s = str_replace('./','/',$s);
			 $s = str_replace('//','/',$s);
			 return $s;
} */

