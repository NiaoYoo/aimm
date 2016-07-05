var Site_TreeData = [
     {
	     "id":"system",
		 "name":"系统设置",
		 "data":[
			 {
			     "name" : "后台管理员设置",
				 "menu" : [
				    {"name":"管理员操作","url":"./index.php?m=Admin&c=Admin&a=index"}
				 ]
			 },			 
			 {
			     "name" : "播放器设置",
				 "menu" : [
				     {"name" : "播放器类型管理","url":"add.html"},
					 {"name" : "广告管理","url":"add2.html"},
					 {"name" : "播放器设置"}
				 ]
			 },
			 {
			     "name" : "数据库维护",
				 "menu" : [
				     {"name" : "数据库备份与恢复","url":"add.html"},
					 {"name" : "数据库维护"},
					 {"name" : "批量替换字段值"}
				 ]
			 },
			 {
			     "name" : "友情链接管理",
				 "menu" : [
				     {"name" : "友情分类管理"},
					 {"name" : "添加友链"},
					 {"name" : "管理友链"}
				 ]
			 }
			 
		 ]
	 },
	 {
	     "id":"info",
		 "name":"信息管理",
		 "data":[
		     {
			     "name":"影片管理",
				 "menu":[
				     {"name":"影片管理","url":"./index.php?m=Admin&c=Vod&a=index"},
					 {"name":"添加影片","url":"./index.php?m=Admin&c=Vod&a=edit"}
				 ]
			 },
			 {
			     "name":"栏目管理",
				 "menu":[
				     {"name":"栏目管理","url":"./index.php?m=Admin&c=Classify&a=index"},
					 {"name":"栏目添加","url":"./index.php?m=Admin&c=Classify&a=edit"}
				 ]
			 },
			 {
			     "name":"影片类型管理",
				 "menu":[
				     {"name":"类型管理","url":"./index.php?m=Admin&c=Datatype&a=index"},
					 {"name":"类型添加","url":"./index.php?m=Admin&c=Datatype&a=edit"}
				 ]
			 },
			 {
			     "name":"专题管理",
				 "menu":[]
			 },
			 {
			     "name":"影视模型设置",
				 "menu":[
				     {"name":"影视模型设置","url":"./index.php?m=Admin&c=Vod&a=config"}
				 ]
			 }
		 ]
	 }
]