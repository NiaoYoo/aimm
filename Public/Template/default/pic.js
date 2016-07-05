var selectedPic = epidiascope.filmstrips[epidiascope.selectedIndex];

function echoFocus(){
	var _url = "/index.php?m=Plugs&a=picdata&id="+pid;
	getData.curUrl = _url;
	aimmPic._getJsData(_url,getData.fillData);
	epidiascope.PVUrl_m = dataInfo.others.PVCountUrl_m;
	epidiascope.PVUrl_a = dataInfo.others.PVCountUrl_a;
	if(dataInfo.others.downloadPic===true){
		document.getElementById("downloadPicObj").style.display="inline";
	};
	window.scrollTo(0,130);
};

//下载图片
function downloadPic(){
	alert("暂不提供下载，请切换浏览全部图片模式用鼠标右键保存至本地！");
	return;
};
function next_jstoflash(){	
	if(getData.nextUrl.length <= 0){
		return;
	}else{
		epidiascope.ImgObj1.src = "/Public/Template/default/news_mj_005.gif";
		aimmPic._getJsData(getData.nextUrl,function(){getData.fillData(0);});
	}
}
function pre_jstoflash(){
	if(getData.preUrl.length <= 0){
		return;
	}else{
		epidiascope.ImgObj1.src = "/Public/Template/default/news_mj_005.gif";
		aimmPic._getJsData(getData.preUrl,function(){getData.fillData(1)});
	}
}

var sendT = {
	getHeader : function(){
		if(dataInfo.title!=epidiascope.filmstrips[epidiascope.selectedIndex].title) {
			return dataInfo.title + '-' + epidiascope.filmstrips[epidiascope.selectedIndex].title;
		} else {
			return epidiascope.filmstrips[epidiascope.selectedIndex].title;
		}
	},
	getFirstImgSrc : function(){
		return epidiascope.ImgObj1.src;
	}
}



function postDigg(ftype,aid){
	document.getElementById('likeData').className='already';
	var taget_obj = document.getElementById('likeData');
	var saveid = GetCookie('diggid');
	if(saveid != null)
	{
		var saveids = saveid.split(',');
		var hasid = false;
		saveid = '';
		j = 1;
		for(i=saveids.length-1;i>=0;i--)
		{
			if(saveids[i]==aid && hasid) continue;
			else {
				if(saveids[i]==aid && !hasid) hasid = true;
				saveid += (saveid=='' ? saveids[i] : ','+saveids[i]);
				j++;
				if(j==20 && hasid) break;
				if(j==19 && !hasid) break;
			}
		}
		if(hasid) {
			alert("您已经喜欢过了哦！");
			return;
		}
		else saveid += ','+aid;
		SetCookie('diggid',saveid,1);
	}
	else
	{
		SetCookie('diggid',aid,1);
	}
	
	$.getJSON('/index.php?m=Plugs&a=digg&id='+aid,function(r,s,x){
	     if(s==='success'){
			 $("#likeData small").html(r.good);
		 }
	});
	
}
function getLike(aid){
	$.getJSON('/index.php?m=Plugs&a=gethits&id='+aid,function(r,s,x){
	     if(s==='success'){
		     $("#viewData").html(r.hits);
			 $("#likeData small").html(r.good);
		 }
	});
}