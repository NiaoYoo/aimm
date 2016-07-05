var FH = {
     getTabs : function($obj){
	     var $tab = $obj.find('.ftabs .tabs-title');
		 var $con = $obj.find('.fbox .tabs-content');
		 $tab.each(function(index,ele){
		     $(this).click(function(){
			     $tab.removeClass('hover');
				 $(this).addClass('hover');
				 $con.hide();
				 $con.eq(index).show();
			 });
		 });
	 },
	 setValue : function(id,data){
	     $('#'+id).val(data);
	 },
	 selectAll : function($obj){
		 $obj.on('ifClicked',function(){
	         var opts = this.checked===false?'check':'uncheck';
		     $('input[name="id[]"]').iCheck(opts);
	     });
	 
	     $('input[name="id[]"]').on('ifClicked',function(){
	         if(this.checked===true){
		         $obj.iCheck('uncheck');
		     }
	     })
	 },
	 
	 range : function(start,end,step){
         step || (step = 1)
         if (end == null) {
             end = start || 0
             start = 0
         }
         var index = -1,
         length = Math.max(0, Math.ceil((end - start) / step)),
         result = Array(length)
         while (++index < length) {
             result[index] = start
             start += step
         }
        return result;
    }
};


FH.makePicHtml = function(data,pagesize){

	 if($("#fdialog-makehtml").length>0){
		$("#fdialog-makehtml").fdialog('show');
		$('#makehtml_min_box').hide()
		return false;
	 }
	 
	 var C = {
	     count : 0,
		 pagecount : 0,
		 data : data,
		 page : 1,
		 pagesize : pagesize,
		 url : './index.php?g=Admin&m=Html&a=pic',
		 makeing : true
	 };
	 
	 function make(){
		 if(C.page>C.pagecount){
		     O.msg.html('恭喜,已全部生成完毕!');
			 return false;
		 }
		 
		 if(C.makeing!==true){
		     return false;
		 }
		 
		 var opts = C.data;
		 $.ajax({
		     url : C.url+'&pagesize='+C.pagesize+'&page='+C.page,
			 type : 'post',
			 dataType : 'json',
			 data : opts,
			 timeout : '120000',
			 success : function(r){
				 showmsg(r);
				 C.page++;
				 make();
			 },
			 error : function(){
			     showmsg(0);
				 C.page++;
				 make();
			 }
		 });
	 
	 }
	 
	 
	 
	 function getCount(){
	     var opts = C.data;
		 
		 $.ajax({
		     url : './index.php?g=Admin&m=Html&a=countpic'+'&pagesize='+C.pagesize,
			 type : 'post',
			 dataType : 'json',
			 data : opts,
			 timeout : '120000',
			 success : function(r){
			     C.pagecount = r.pagecount;
				 C.count = r.count;
				 O.count.html(C.pagecount);
				 make();
			 },
			 error : function(){
			     $.fdialog.alert('fa fa-times-circle','<h5>获取数据出错,请重试!</h5>');
			 }
		 });
		 
	 }
	 
	 function showmsg(r){
	     O.page.html(C.page);
		 O.msg.html('正在生成第'+C.page+'组');
		 var speed = (C.page/C.pagecount)*100;

		 O.speed.css('width',speed+'%');
		 O.minspeed.html(parseInt(speed));
				 
		 if(r.status===1){
			O.success.html(function(index,val){
				return parseInt(val)+1;
			});
		 }else{
			O.error.html(function(index,val){
			     return parseInt(val)+1;
			});
		 }
	 }
	 
	 function dialog(){
	     var fDialog = $.fdialog({
	         id : 'makehtml',
		     title : '数据生成提示',
	         content : '<div style="padding:7px 4px;"><div style="margin-bottom:10px;">共有<b style="font-size:14px; padding:0 5px; color:red;" class="makehtml_count">'+C.pagecount+'</b>组图片集需要生成，<span class="makehtml_msg">请稍等...</span></div><div style="width:400px; height:12px; background-color:#dddddd; margin-bottom:10px;"><div class="makehtml_speed" style="width:0%; height:12px; background-color:green;"></div><div style="line-height:30px;"><span style="float:right;">成功:<b class="makehtml_success">0</b><span style="padding-left:10px;">失败:</span><b class="makehtml_error">0</b></span><b class="makehtml_page">0</b> / <b class="makehtml_count">'+C.pagecount+'</b></div></div></div>',
		     width : 420,
		     height : 150,
		     button : [
			     {title:'暂停生成',value:'stop'},{title:'隐藏窗口',value:'hidden'}
		     ],
		     buttonCallback : function(obj,v,btn){
			     if(v=='stop'){
				     C.makeing = false;
				     btn.data('fdialog','run').html('开始生成');
			     }else if(v=='run'){
				     btn.data('fdialog','stop').html('暂停生成');
				     C.makeing = true;
				     make();
			     }else{
				     O.minbox.show();
				     obj.hide();
			     }
			     return false;
		     },
		     menuCallback : function(obj,v,btn){
			     C.makeing = false;
			     return true;
		     },
		     fiexd : true
	     });
		 
		 return fDialog;
	 }
	 
	 
	 var $fDialog = dialog();
	 if($fDialog){
		 if($("#makehtml_min_box").length<1){
			 $fDialog.after('<div id="makehtml_min_box" style="position:fixed; height:50px; width:60px; padding:10px; color:#ffffff; background:#37A4CD; left:10px; bottom:10px; display:none;">已生成<br/><b class="makehtml_min_speed" style="font-size:30px;">0</b><span>%</span></div>');
		 }
		 
		 var O = {
		     msg : $fDialog.find('.makehtml_msg'),
			 count : $fDialog.find('.makehtml_count'),
			 speed : $fDialog.find('.makehtml_speed'),
			 success : $fDialog.find('.makehtml_success'),
			 error : $fDialog.find('.makehtml_error'),
			 page : $fDialog.find('.makehtml_page'),
			 minbox : $("#makehtml_min_box"),
			 minspeed : $("#makehtml_min_box").find(".makehtml_min_speed")
		 }
		 
		 O.minbox.click(function(){
		     $(this).hide();
			 $fDialog.show();
		 });
		 
		 getCount();
	 }else{
	     $.fdialog.alert('fa fa-times-circle','<h5>系统初始化失败,请重试!</h5>');
	 }
	 
}



FH.makeClassifyHtml = function(data,pagesize,pagemax){

	 if($("#fdialog-makehtml").length>0){
		$("#fdialog-makehtml").fdialog('show');
		$('#makehtml_min_box').hide()
		return false;
	 }
	 
	 var C = {
		 pagecount : 0, //当前栏目共多少页,
		 pagemax : pagemax, //最大生成的栏目页数 0 不限
		 page : 1, //当前生成第几页
		 data : data, //栏目数据数组[1,2,3] 
		 mid : 0, //当前生成的栏目序号(最大不能越过data的长度)
		 pagesize : pagesize, //栏目一次生成多少页
		 url : './index.php?g=Admin&m=Html&a=classify',
		 makeing : true
	 };
	 
	 function make(){
		 if(C.mid>C.data.length-1){
		     O.msg.html('恭喜,已全部生成完毕!');
			 return false;
		 }
		 console.info(C.mid+' '+C.page);
		 if(C.makeing!==true){
		     return false;
		 }
		 
		 var opts = "&id="+C.data[C.mid];
		 
		 $.ajax({
		     url : C.url+'&pagesize='+C.pagesize+'&page='+C.page,
			 type : 'post',
			 dataType : 'json',
			 data : opts,
			 timeout : '120000',
			 success : function(r){
				 
				 C.pagecount = r.pagecount;
				 C.page++;
				 if(C.pagemax==0){
				     if(C.page>C.pagecount){
					     //表示当前栏目生成完了
						 C.mid++;
						 C.page = 1;
						 C.pagecount = 0;
						 showmsg(r);
				         make();
					 }else{
					     make();
					 }
				 }else{
				     if(C.page>C.pagecount || C.page>C.pagemax){
					     //表示当前栏目生成完或大于自定义最大页
						 C.mid++;
						 C.page = 1;
						 C.pagecount = 0;
						 showmsg(r);
				         make();
					 }else{
					     make();
					 }
				 }
			 },
			 error : function(){
				 C.mid++;
				 C.page = 1;
				 C.pagecount = 0;
				 showmsg(0);
				 make();
			 }
		 });
	 
	 }
	 
	 function showmsg(r){
	     O.page.html(C.mid);
		 O.msg.html('正在生成第'+(C.mid+1)+'个栏目');
		 var speed = ((C.mid)/C.data.length)*100;

		 O.speed.css('width',speed+'%');
		 O.minspeed.html(parseInt(speed));
				 
		 if(r.status===1){
			O.success.html(function(index,val){
				return parseInt(val)+1;
			});
		 }else{
			O.error.html(function(index,val){
			     return parseInt(val)+1;
			});
		 }
	 }
	 
	 function dialog(){
	     var fDialog = $.fdialog({
	         id : 'makehtml',
		     title : '数据生成提示',
	         content : '<div style="padding:7px 4px;"><div style="margin-bottom:10px;">共有<b style="font-size:14px; padding:0 5px; color:red;" class="makehtml_count">'+C.data.length+'</b>个栏目需要生成，<span class="makehtml_msg">请稍等...</span></div><div style="width:400px; height:12px; background-color:#dddddd; margin-bottom:10px;"><div class="makehtml_speed" style="width:0%; height:12px; background-color:green;"></div><div style="line-height:30px;"><span style="float:right;">成功:<b class="makehtml_success">0</b><span style="padding-left:10px;">失败:</span><b class="makehtml_error">0</b></span><b class="makehtml_page">0</b> / <b class="makehtml_count">'+(C.data.length)+'</b></div></div></div>',
		     width : 420,
		     height : 150,
		     button : [
			     {title:'暂停生成',value:'stop'},{title:'隐藏窗口',value:'hidden'}
		     ],
		     buttonCallback : function(obj,v,btn){
			     if(v=='stop'){
				     C.makeing = false;
				     btn.data('fdialog','run').html('开始生成');
			     }else if(v=='run'){
				     btn.data('fdialog','stop').html('暂停生成');
				     C.makeing = true;
				     make();
			     }else{
				     O.minbox.show();
				     obj.hide();
			     }
			     return false;
		     },
		     menuCallback : function(obj,v,btn){
			     C.makeing = false;
			     return true;
		     },
		     fiexd : true
	     });
		 
		 return fDialog;
	 }
	 
	 
	 var $fDialog = dialog();
	 if($fDialog){
		 if($("#makehtml_min_box").length<1){
			 $fDialog.after('<div id="makehtml_min_box" style="position:fixed; height:50px; width:60px; padding:10px; color:#ffffff; background:#37A4CD; left:10px; bottom:10px; display:none;">已生成<br/><b class="makehtml_min_speed" style="font-size:30px;">0</b><span>%</span></div>');
		 }
		 
		 var O = {
		     msg : $fDialog.find('.makehtml_msg'),
			 count : $fDialog.find('.makehtml_count'),
			 speed : $fDialog.find('.makehtml_speed'),
			 success : $fDialog.find('.makehtml_success'),
			 error : $fDialog.find('.makehtml_error'),
			 page : $fDialog.find('.makehtml_page'),
			 minbox : $("#makehtml_min_box"),
			 minspeed : $("#makehtml_min_box").find(".makehtml_min_speed")
		 }
		 
		 O.minbox.click(function(){
		     $(this).hide();
			 $fDialog.show();
		 });
		 
		 //getCount();
		 make();
	 }else{
	     $.fdialog.alert('fa fa-times-circle','<h5>系统初始化失败,请重试!</h5>');
	 }
	 
}


FH.makePagesHtml = function(data,pagesize){

	 if($("#fdialog-makehtml").length>0){
		$("#fdialog-makehtml").fdialog('show');
		$('#makehtml_min_box').hide()
		return false;
	 }
	 
	 var C = {
	     count : 0,
		 pagecount : 0,
		 data : data,
		 page : 1,
		 pagesize : pagesize,
		 url : './index.php?g=Admin&m=Html&a=pages',
		 makeing : true
	 };
	 
	 function make(){
		 if(C.page>C.pagecount){
		     O.msg.html('恭喜,已全部生成完毕!');
			 return false;
		 }
		 
		 if(C.makeing!==true){
		     return false;
		 }
		 
		 var opts = C.data;
		 $.ajax({
		     url : C.url+'&pagesize='+C.pagesize+'&page='+C.page,
			 type : 'post',
			 dataType : 'json',
			 data : opts,
			 timeout : '120000',
			 success : function(r){
				 showmsg(r);
				 C.page++;
				 make();
			 },
			 error : function(){
			     showmsg(0);
				 C.page++;
				 make();
			 }
		 });
	 
	 }
	 
	 
	 
	 function getCount(){
	     var opts = C.data;
		 
		 $.ajax({
		     url : './index.php?g=Admin&m=Html&a=countpages'+'&pagesize='+C.pagesize,
			 type : 'post',
			 dataType : 'json',
			 data : opts,
			 timeout : '120000',
			 success : function(r){
			     C.pagecount = r.pagecount;
				 C.count = r.count;
				 O.count.html(C.pagecount);
				 make();
			 },
			 error : function(){
			     $.fdialog.alert('fa fa-times-circle','<h5>获取数据出错,请重试!</h5>');
			 }
		 });
		 
	 }
	 
	 function showmsg(r){
	     O.page.html(C.page);
		 O.msg.html('正在生成第'+C.page+'组');
		 var speed = (C.page/C.pagecount)*100;

		 O.speed.css('width',speed+'%');
		 O.minspeed.html(parseInt(speed));
				 
		 if(r.status===1){
			O.success.html(function(index,val){
				return parseInt(val)+1;
			});
		 }else{
			O.error.html(function(index,val){
			     return parseInt(val)+1;
			});
		 }
	 }
	 
	 function dialog(){
	     var fDialog = $.fdialog({
	         id : 'makehtml',
		     title : '数据生成提示',
	         content : '<div style="padding:7px 4px;"><div style="margin-bottom:10px;">共有<b style="font-size:14px; padding:0 5px; color:red;" class="makehtml_count">'+C.pagecount+'</b>组单页面需要生成，<span class="makehtml_msg">请稍等...</span></div><div style="width:400px; height:12px; background-color:#dddddd; margin-bottom:10px;"><div class="makehtml_speed" style="width:0%; height:12px; background-color:green;"></div><div style="line-height:30px;"><span style="float:right;">成功:<b class="makehtml_success">0</b><span style="padding-left:10px;">失败:</span><b class="makehtml_error">0</b></span><b class="makehtml_page">0</b> / <b class="makehtml_count">'+C.pagecount+'</b></div></div></div>',
		     width : 420,
		     height : 150,
		     button : [
			     {title:'暂停生成',value:'stop'},{title:'隐藏窗口',value:'hidden'}
		     ],
		     buttonCallback : function(obj,v,btn){
			     if(v=='stop'){
				     C.makeing = false;
				     btn.data('fdialog','run').html('开始生成');
			     }else if(v=='run'){
				     btn.data('fdialog','stop').html('暂停生成');
				     C.makeing = true;
				     make();
			     }else{
				     O.minbox.show();
				     obj.hide();
			     }
			     return false;
		     },
		     menuCallback : function(obj,v,btn){
			     C.makeing = false;
			     return true;
		     },
		     fiexd : true
	     });
		 
		 return fDialog;
	 }
	 
	 
	 var $fDialog = dialog();
	 if($fDialog){
		 if($("#makehtml_min_box").length<1){
			 $fDialog.after('<div id="makehtml_min_box" style="position:fixed; height:50px; width:60px; padding:10px; color:#ffffff; background:#37A4CD; left:10px; bottom:10px; display:none;">已生成<br/><b class="makehtml_min_speed" style="font-size:30px;">0</b><span>%</span></div>');
		 }
		 
		 var O = {
		     msg : $fDialog.find('.makehtml_msg'),
			 count : $fDialog.find('.makehtml_count'),
			 speed : $fDialog.find('.makehtml_speed'),
			 success : $fDialog.find('.makehtml_success'),
			 error : $fDialog.find('.makehtml_error'),
			 page : $fDialog.find('.makehtml_page'),
			 minbox : $("#makehtml_min_box"),
			 minspeed : $("#makehtml_min_box").find(".makehtml_min_speed")
		 }
		 
		 O.minbox.click(function(){
		     $(this).hide();
			 $fDialog.show();
		 });
		 
		 getCount();
	 }else{
	     $.fdialog.alert('fa fa-times-circle','<h5>系统初始化失败,请重试!</h5>');
	 }
	 
}

$(function(){
     //FH.makeIdsVod([1,2,3]);
});