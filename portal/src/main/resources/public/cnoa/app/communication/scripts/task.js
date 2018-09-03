/**
 * 计划任务For Application: communication
 */

//任务：启动收件箱检查器 / 系统消息检查器(类QQ/MSN)
var CNOA_communication_message_inbox_refreshTimer_titleTimer = null;
var CNOA_communication_message_inbox_refreshTimer_title = document.title;

function CNOA_TASK_MESSAGE_INBOX_REFRESHER(){
	//var btn = Ext.getCmp("CNOA_main_mainPanel_communication_message_inbox_btn");
	var step = 0;
	//检查收件箱
	Ext.Ajax.request({
		url: 'index.php?app=communication&func=message&action=index&task=checkNewMessage',
		method: 'GET',
		success: function(r) {
			var result = Ext.decode(r.responseText);
			if (parseInt(result.msg) > 0){
				//btn.setIconClass("icon-cnoa-Email-active");
				//btn.setText("消息("+result.msg+")");
				//btn.ownerCt.doLayout();
				
				//主动刷新桌面上的列表
				try{
					var desktopList = Ext.getCmp("CNOA_MAIN_DESKTOP_COMM_MESSAGE_INBOX_GRID");
					desktopList.getStore().reload();
				}catch(e){}
			}else{
				//btn.setIconClass("icon-cnoa-Email");
				//btn.setText("消息");

				//resetDocumentTitle();
			}
		}.createDelegate(this)
	});
}
function CNOA_TASK_NOTICE_REFRESHER(){
	//检查消息提醒框
	Ext.Ajax.request({  
		url: 'index.php?task=checkSystemNotice',
		method: 'GET',
		success: function(r) {
			var result = Ext.decode(r.responseText);
			var data = result.msg;
			var totalMenu = result.totalMenu;
			Ext.getCmp("CNOA_DESKTOP_NOTICE_BTN").setText(lang('remind') + "<span class='b1'><span>"+result.total+"</span><i></i></span>");
			Ext.getCmp("CNOA_DESKTOP_NOTICE_ALLWAIT_BTN").setText(lang('workToDo')+"<span class='b2'><span>"+result.totalwait+"</span><i></i></span>");

			/*
			Ext.each(totalMenu, function(item, index, other){
				var sBtn = Ext.getCmp("ID_DESKTOP_NOTICE_GROUP"+item.ename);
				try{
					if(sBtn.sType == "sBtn"){
						sBtn.setText(item.name);
					}else{
						sBtn.setText(item.sname);
					}
				}catch (e){
					
				}
			});
			*/
			Ext.each(data, function(item, index, other){
				var noticeWindow = Ext.getCmp("CNOA_SYSTEM_NOTICE_WINDOW_ID_"+item.id);
				if (noticeWindow == undefined){
					CNOA.msg.notice2(item.content, item.title);
					
					Ext.Ajax.request({
						url: 'index.php?task=deleteSystemNotice',
						params: {id: item.id},
						method: 'POST',
						success: function(r) {
							
						}.createDelegate(this)
					});
				}
			});
			
		}.createDelegate(this)
	});
}
function resetDocumentTitle(){
	clearInterval(CNOA_communication_message_inbox_refreshTimer_titleTimer);
	document.title = CNOA_communication_message_inbox_refreshTimer_title;
}

/**
 * 计划任务For Application: communication
 */

//任务：启动Web IM最新消息检查器
/*
function CNOA_TASK_IM_REFRESHER(){
	Ext.Ajax.request({  
		url: 'index.php?app=communication&func=im&action=index',
		method: 'POST',
		timeout: 10000,
		params: { task: 'scanNewMsg'},
		success: function(r) {
			if (r.responseText == ""){
				return;
			}
			try{
				CNOA_COMMUNICATION_IM_MANAGER.push(r.responseText);
			}catch(e){}
			
		},
		failure : function(){
			try{
				CNOA_communication_im_index.insertNewChattingRecord(list[i].uid, list[i].name);
			}catch (e){}
		}
	});
}
*/

if(CNOA_IN_AIR != 1){
	//检查收件箱 / 提醒消息
	CNOA_TASK_MESSAGE_INBOX_REFRESHER();
	var CNOA_communication_message_inbox_refreshTimer = setInterval(function(){
		CNOA_TASK_MESSAGE_INBOX_REFRESHER();
	}, 60000);
	
	//检查即时会话
	//CNOA_TASK_IM_REFRESHER();
	//var CNOA_communication_im_refreshTimer = setInterval(function(){
	//	CNOA_TASK_IM_REFRESHER();
	//}, 30000);
}

//检查待办
CNOA_TASK_NOTICE_REFRESHER();
var CNOA_communication_message_inbox_refreshTimer = setInterval(function(){
	CNOA_TASK_NOTICE_REFRESHER();
}, 60000);

