var cnoa_plan_user_plan_attach_btn = "";
function makeplanAttacBtn(user_plan_atach_id, url, name, size){
	var getDownLink = function(name, rt){
		var url,tp='none';
		var ext = name.substring(name.lastIndexOf(".")+1).toLowerCase();
		switch (ext){
			case "jpg":
			case "bmp":
			case "png":	 url = "jpg.gif";tp='pic';break;
			case "rar":
			case "zip":
			case "7z":	 url = "rar.gif";break;
			case "txt":	 url = "txt.gif";tp='txt';break;
			case "xls":
			case "xlsx": url = "excel.gif";tp='xls';break;
			case "doc":
			case "docx": url = "word.gif";tp='doc';break;
			//case "ppt":
			//case "pptx": url = "word.gif";break;
			default:     url = "file.gif";break;
		}
		if(rt == true){
			return tp;
		}else{
			return '<img src="./resources/images/icons_file/'+url+'" width=16 height=16 align="absmiddle" >'+name+' ('+size+')';
		}
	};
	var a = Ext.fly(user_plan_atach_id);
	a.dom.innerHTML = getDownLink(name);
	var treeMenu = new Ext.menu.Menu({
		id: "menu_"+user_plan_atach_id,
		items: [
			{
				text: name,
				disabled: true
			},"-",
			{
				xtype: "swfdownloadmenuitem",
				text: lang('download'),
				iconCls: 'icon-file-down',
				dlurl: url,
				dlname: name
			},
			{
				text: lang('viewPreview'),
				hidden: (getDownLink(name, true)=="pic" || getDownLink(name, true)=="txt" || getDownLink(name, true)=="xls") ? false : true,
				handler: function(){
					window.open("./resources/preview.php?url="+encodeURIComponent(url));
				}
			},
			//{ text: "下载", pressed: true },
			{
				text: lang('edit'),
				pressed: false,
				hidden: true,
				//hidden: (!Ext.isAir && (getDownLink(name, true)=="doc" || getDownLink(name, true)=="xls")) ? false : true,
				handler: function(){
					window.open("./index.php?action=commonJob&act=viewDocForActivex&url="+encodeURIComponent(url));
				}
			}
		]
	});
	a.dom.onmouseover = function(position){
		try{
			Ext.getCmp(cnoa_plan_user_plan_attach_btn).hide();
			Ext.fly(cnoa_plan_user_plan_attach_btn.replace("menu_", "")).dom.style.backgroundColor = "#F2E6E6";
		}catch(e){}
		Ext.fly(user_plan_atach_id).dom.style.backgroundColor = "#CEC1FF";
		if(Ext.isIE){
			xy = [window.event.clientX, window.event.clientY];
		}else{
			var xy = [position.clientX, position.clientY];
		}
		
		treeMenu.showAt(xy);
		cnoa_plan_user_plan_attach_btn = "menu_"+user_plan_atach_id;
	};
}


function CNOA_USER_PLAN_TAB_CLOSE(type){
	var id = "";
	switch (type){
		case "add":
		case "edit":
			id = "CNOA_MENU_USER_PLAN_ADDEDIT";
			break;
		case "list_my":
			id = "CNOA_MENU_USER_PLAN_LIST_MY";
			break;
		case "list_dept":
			id = "CNOA_MENU_USER_PLAN_LIST_DEPT";
			break;
		case "workreport":
			id = "CNOA_MENU_USER_PLAN_WORKREPORT";
			break;
		case "sum":
			id = "CNOA_MENU_USER_PLAN_SUM";
			break;
		case "sharereport":
			id = "CNOA_MENU_USER_PLAN_SHAREREPORT";
			break;
		case "view":
			id = "CNOA_MENU_USER_PLAN_VIEW";
			break;
		case "task_add":
			id = "CNOA_MENU_USER_TASK_ADDEDIT";
			break;
	}
	mainPanel.closeTab(id);
}
function CNOA_USER_PLAN_TAB_OPEN(type, pid, theURL){
	var id 		= "";
	var url 	= "";
	var title 	= "";
	var icon 	= "";

	switch (type){
		case "add":
			id = "CNOA_MENU_USER_PLAN_ADDEDIT";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=addedit";
			title = lang('addPlan');
			icon = "icon-page-addedit";
			break;
		case "edit":
			id = "CNOA_MENU_USER_PLAN_ADDEDIT";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=addedit&job=edit&pid="+pid;
			title = lang('editPlan');
			icon = "icon-page-addedit";
			break;
		case "list_my":
			id = "CNOA_MENU_USER_PLAN_LIST_MY";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=myplan";
			title = lang('personPlan');
			icon = "icon-page-list";
			break;
		case "list_dept":
			id = "CNOA_MENU_USER_PLAN_LIST_DEPT";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=deptplan";
			title = lang('deptPlan');
			icon = "icon-page-list";
			break;
		case "workreport":
			id = "CNOA_MENU_USER_PLAN_WORKREPORT";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=workreport";
			title = lang('report');
			icon = "icon-page-list";
			break;
		case "sum":
			id = "CNOA_MENU_USER_PLAN_SUM";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=sum";
			title = lang('summary');
			icon = "icon-page-list";
			break;
		case "sharereport":
			id = "CNOA_MENU_USER_PLAN_SHAREREPORT";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=sharereport";
			title = lang('shareReportPlan');
			icon = "icon-page-list";
			break;
		case "view":
			id = "CNOA_MENU_USER_PLAN_VIEW";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=view&pid="+pid;
			title = lang('viewPlan');
			icon = "icon-page-view";
			break;
		case "task_add":
			id = "CNOA_MENU_USER_TASK_ADDEDIT";
			url = "index.php?app=user&func=plan&action=default&task=loadPage&from=task_add&pid="+pid;
			title = lang('addPlanTask');
			icon = "icon-page-addedit";
			break;
		default :
			return false;
			break;
	}
	
	url = theURL == undefined ? url : theURL;

	mainPanel.closeTab(id);
	
	mainPanel.loadClass(url, id, title, icon);
}

function getPlanDate(cusDate){
	var now = new Date();                    //当前日期
	if(cusDate != undefined){
		now = new Date(cusDate);
	}
		     
	var nowDayOfWeek = now.getDay();         //今天本周的第几天     
	var nowDay = now.getDate();              //当前日     
	var nowMonth = now.getMonth();           //当前月     
	var nowYear = now.getFullYear();             //当前年     
	nowYear += (nowYear < 2000) ? 1900 : 0;
	
	
	
	var lastMonthDate = new Date();  //下月日期
	if(cusDate != undefined){
		lastMonthDate = new Date(cusDate);
	}  
	
	lastMonthDate.setDate(1);
	lastMonthDate.setMonth(lastMonthDate.getMonth()+1);
	var lastMonth = lastMonthDate.getMonth();
	
	//alert(cusDate);
	
	
	this.formatDate = function(date) {
	    var myyear = date.getFullYear();
	    var mymonth = date.getMonth()+1;
	    var myweekday = date.getDate();
	         
	    if(mymonth < 10){
	        mymonth = "0" + mymonth;
	    }
	    if(myweekday < 10){
	        myweekday = "0" + myweekday;
	    }
	    return (myyear+"-"+mymonth + "-" + myweekday);
	}
	
	
	//获得某月的天数     
	function getMonthDays(myMonth){
	    var monthStartDate = new Date(nowYear, myMonth, 1); 
	    var monthEndDate = new Date(nowYear, myMonth + 1, 1);
	    var   days   =   (monthEndDate   -   monthStartDate)/(1000   *   60   *   60   *   24); 
	    return   days;      
	}
	
	//获得本天的开始日期
	this.getDayStartDate = function(){
	    var dayStartDate = new Date(nowYear, nowMonth, nowDay);
	    return this.formatDate(dayStartDate);
	}      
    
	//获得本天的结束日期
	this.getDayEndDate = function(){
	    var dayEndDate = new Date(nowYear, nowMonth, nowDay);
	    return this.formatDate(dayEndDate);
	}
	
	//获得明天的开始日期
	this.getLastDayStartDate = function(){
	    var dayStartDate = new Date(nowYear, nowMonth, nowDay + 1);
	    return this.formatDate(dayStartDate);
	}      
    
	//获得明天的结束日期
	this.getLastDayEndDate = function(){
	    var dayEndDate = new Date(nowYear, nowMonth, nowDay + 1);
	    return this.formatDate(dayEndDate);
	}    
	
	//获得本周的开始日期
	this.getWeekStartDate = function(){

	    var weekStartDate = new Date(nowYear, nowMonth, nowDay - nowDayOfWeek);
	    return this.formatDate(weekStartDate);
	}      
    
	//获得本周的结束日期
	this.getWeekEndDate = function(){
	    var weekEndDate = new Date(nowYear, nowMonth, nowDay + (6 - nowDayOfWeek));
	    return this.formatDate(weekEndDate);
	}
	
	//获得下周的开始日期
	this.getLadtWeekStartDate = function(){
	    var weekStartDate = new Date(nowYear, nowMonth, nowDay + (7- nowDayOfWeek));
	    return this.formatDate(weekStartDate);
	}      
    
	//获得下周的结束日期
	this.getLastWeekEndDate = function(){
	    var weekEndDate = new Date(nowYear, nowMonth, nowDay + (13 - nowDayOfWeek));
	    return this.formatDate(weekEndDate);
	}
	
	//获得本月的开始日期
	this.getMonthStartDate = function(){
	    var monthStartDate = new Date(nowYear, nowMonth, 1);
	    return this.formatDate(monthStartDate);
	}
	    
	//获得本月的结束日期
	this.getMonthEndDate = function(){
	    var monthEndDate = new Date(nowYear, nowMonth, getMonthDays(nowMonth));
	    return this.formatDate(monthEndDate);
	}
	
	//获得下月开始时间 
	this.getLastMonthStartDate = function(){
	    var lastMonthStartDate = new Date(nowYear, lastMonth, 1);
	    return this.formatDate(lastMonthStartDate);
	}
  
	//获得下月结束时间  
	this.getLastMonthEndDate = function(){
	    var lastMonthEndDate = new Date(nowYear, lastMonth, getMonthDays(lastMonth));
	    return this.formatDate(lastMonthEndDate);
	}
	
	//获得本年的开始日期
	this.getYearStartDate = function(){
	    var yearStartDate = new Date(nowYear, 0, 1);
	    return this.formatDate(yearStartDate);
	}
	    
	//获得本年的结束日期
	this.getYearEndDate = function(){
	    var yearEndDate = new Date(nowYear, 11, 31);
	    return this.formatDate(yearEndDate);
	}
	
	//获得下年开始时间 
	this.getLastYearStartDate = function(){
	    var lastMonthStartDate = new Date(nowYear+1, 0, 1);
	    return this.formatDate(lastMonthStartDate);
	}
  
	//获得下年结束时间  
	this.getLastYearEndDate = function(){
	    var lastYearEndDate = new Date(nowYear+1, 11, 31);
	    return this.formatDate(lastYearEndDate);
	}
}

CNOA_USER_PLAN_COMMON = {
	viewTpl : new Ext.XTemplate(
		'<div style="width:770px;height:40px;background-color:#FEFBDC;font-size:16px;font-weight:bold;line-height:40px; text-align: center;">[{plantypeName}]{title}',
		'</div>',
		'<div style="margin-top:5px;margin-bottom:5px;width:770px;">',
		'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('baseInfo2')+'</span></div>',
		'	<div style="background-color:#EFF5FF;padding-bottom:5px;">',
		'		<ul class="user_task_view_ul">',
		'			<li><span class="first">'+lang('planType')+': </span><span class="second" style="color:#800000;">{plantypeName}</span></li>',
		'			<li><span class="first">'+lang('planer')+': </span><span class="second">{planer}</span></li>',
		'			<li><span class="first">'+lang('planStatus')+': </span><span class="second">{statusText}</span></li>',
		'			<li><span class="first">'+lang('startTime')+': </span><span class="second">{stime}</span></li>',
		'			<li><span class="first">'+lang('endTime')+': </span><span class="second">{etime}</span></li>',
		'			<li>&nbsp;</li>',
		'		</ul>',
		'		<div class="clear"></div>',
		'	</div>',
		'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('content')+'</span></div>',
		'	<div style="padding:20px;background-color:#EFF5FF;">',
		'	<tpl for="contentList">',
		'	  <div style="border-bottom:1px dashed #969AA7;margin:10px 0;line-heigth:20px;">{c}</div>',
		'	</tpl>',
		'	</div>',
		'	<div id="user_plan_view_summary"><div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('planSummary')+'</span></div>',
		'	<div style="padding:20px;background-color:#EFF5FF;">{summary}</div></div>',
		'	<tpl if="attachCount &gt; 0">',
		'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('attach')+'</span></div>',
		'	<div style="padding:20px;background-color:#EFF5FF;">',
		'		{attach}',
		'	</div>',
		'	</tpl>',
		'</div>'
	),

	commentTpl : new Ext.XTemplate(
		'<div style="border-width:0px;background-color:#F5F5F5">',
		'	<div style="border:2px solid #E6E6E6;border-bottom-width:0px;height:auto;">',
		'		<div style="height:25px;background-color:#E6E6E6;line-height:25px;">',
		'			<span style="font-size:12px;font-weight:bold;">{title}</span>({totalCount}'+lang('tiao')+')',
		'		</div>',
		'	   <tpl if="totalCount &gt; 0">',
		'		<ul style="margin-top:10px;">',
		'			<tpl for="list">',
		'			<li style="border-bottom:1px solid #DDDDDD;margin:2px 0;background-color:{[xindex % 2 === 0 ? "#FFF" : "##F6F6F6"]}">',
		'				<div style="height:160px;width:120px;float:left;border:1px solid #D8D8D8;margin:3px 5px 5px 5px;"><img src="{face}" onerror="this.src=\'./resources/images/empty_photo.png\'" style="background-color:#FFF;"></div>',
		'				<div style="float:left;width:590px;">',
		'					<div style="height:22px;line-height:22px;border-bottom:1px solid #DDDDDD;">',
		'						#{[xindex]} '+lang('postter')+':<a  style="font-weight:bold;">{user}</a>&nbsp;&nbsp;&nbsp;'+lang('inDepartment')+':<a style="font-weight:bold;">{dept}</a>&nbsp;&nbsp;&nbsp;'+lang('posttime2')+':{posttime}',
		'						<tpl if="uid =='+CNOA_USER_UID +'">',
		'							&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="CNOA_user_plan_view.editComment({id})">修改</a>',
		'							&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="CNOA_user_plan_view.delComment({id})">删除</a>',
		'						</tpl>',		
		'					</div>',
		'				<div style="padding:10px;">{content}</div></div>',
		'				<div style="clear:both;"></div>',
		'			</li>',
		'			</tpl>',
		'		</ul>',
		'	</div>',
		'	</tpl>',
		'</div>'
	)
}