var CNOA_attendanceNoticeWindow_closed, 
	CNOA_attendanceNoticeWindow_jsData, 
	CNOA_attendanceNoticeWindow_jsData_cid,
	attendanceNoticeWindow; 

// 弹出窗口类
CNOA.attendanceNoticeWindow = CNOA.Class.create();
CNOA.attendanceNoticeWindow.prototype = {
	init : function(title, content, href, cid, num){
		var _this = this;		
		this.baseUrl	= "index.php?app=user&func=attendance&action=checktime&task=alert";

		this.title		= title;
		this.content	= content;
		this.href		= href;
		this.cid		= cid;
		this.num		= num;
		
		//位置
		this.position = "br";		
		this.mainContent = this.makeContentPanel();

		this.footBtn = new Ext.Panel({
			border: false,
			height: 50,
			layoutConfig: {
				columns: 3
			},
			bodyStyle: "padding-top:4px",
			layout: "table",
			items: [
				{
					xtype: "panel",
					border: false,
					width: 160
				},{
					xtype: "panel",
					style: "margin-right:5px",
					border: false,
					width: 70,
					items: [
						{
							xtype: "button",
							text: lang('attendanceReg'),
							cls: 'btn-blue4',
							handler: function(){																
								_this.detailBtnHandler();
								_this.close();
							}
						}
					]
				},{
					xtype: "button",
					text: lang('close'),
					handler: function(){
						_this.close();
					}
				}
			]
		});

		this.mainPanel = new Ext.Window({
			width:300,
			height:200,
			//id: "CNOA_SYSTEM_NOTICE_WINDOW_ID_"+this.id,
			shadow:false,
			animCollapse : true,
			title:lang('noticeTitle2'),
			plain: true,
			modal: false,
			closeAction: "close",
			maximizable: false,
			draggable: true,
			//autoDestroy: true,
			resizable: false,
			items: [this.mainContent, this.footBtn],
			listeners: {
				show : function(th){
					th.toFront();					
					if (_this.position == "br") {
						th.getEl().alignTo(Ext.getBody(), 'br-br');
					}
				},
				
				close : function(){
					CNOA_attendanceNoticeWindow_closed = true;
				}
			}
		});
		
		return this.mainPanel;
	},
	
	show : function(position){
		if(position != undefined){
			this.position = position;
		}
		this.mainPanel.show();
		this.mainPanel.getEl().fadeIn({
			easing: 'easeIn',
			duration: 1,
			remove: false,
			useDisplay: false
		});
	},

	close : function(){
		var _this = this;
		
		if(_this.position == "br"){
			_this.mainPanel.getEl().slideOut("b", {
				easing: 'easeOut',
				duration: 1,
				remove: false,
				useDisplay: true,
				callback: function(){
					_this.mainPanel.close();
					Ext.destroy(_this.mainPanel);
				}
			});
		}else{
			_this.mainPanel.close();
			Ext.destroy(_this.mainPanel);
		}
	},
	
	detailBtnHandler : function(){
		var _this = this;
		var cid = _this.cid;
		var num = _this.num;
		
		Ext.Ajax.request({
			url: _this.baseUrl + "&type=checkin&cid=" + cid + '&cnum=' + num,
			method: 'GET',
			success: function(r){
				var sText = r.responseText;
				jsData = Ext.decode(sText);
				CNOA.msg.alert(jsData.msg);
				
				try{
					CNOA_MAIN_DESKTOP_USRE_HR_CLOCK_OBJ.getInfo();
				}catch(e){}
			}
		});
			
		//	mainPanel.closeTab(CNOA_APP_NOTICE_FROMTYPE[this.type].id);
		//	mainPanel.loadClass(this.href, CNOA_APP_NOTICE_FROMTYPE[this.type].id, CNOA_APP_NOTICE_FROMTYPE[this.type].tname, CNOA_APP_NOTICE_FROMTYPE[this.type].icon);
		
	},
	
	makeContentPanel : function(){
		return new Ext.Panel({
			border: false,
			height: 120,
			autoScroll: true,
			bodyStyle: "padding: 5px",
			html: "<div style='color:#800000;font-weight:bold;font-size:12px;line-height:25px;display:"+(((this.title=="")||(this.title==undefined))?"none":"block")+";'>"+this.title+"</div>"+"<div style='text-indent:25px;line-height:18px;'>"+this.content+"</div>"
		});
	},
	
	//提供给待办事务列表调用
	goView : function(){
		var _this = this;
		//mainPanel.closeTab(CNOA_APP_NOTICE_FROMTYPE[this.type].id);
		//mainPanel.loadClass(this.href, CNOA_APP_NOTICE_FROMTYPE[this.type].id, CNOA_APP_NOTICE_FROMTYPE[this.type].tname, CNOA_APP_NOTICE_FROMTYPE[this.type].icon);
	},
	
	close : function(){
		this.mainPanel.close();
	}
}


CNOA.attendanceNotice = CNOA.Class.create();
CNOA.attendanceNotice.prototype = {
	init : function(){
		var _this = this;		
		this.baseUrl	= "index.php?app=user&func=attendance&action=checktime&task=alert";
		//CNOA_attendanceNoticeWindow_closed = false;
		
		function checktime(){		
			var jsData = CNOA_attendanceNoticeWindow_jsData;			
			var now		= new Date();
			now.setTime(differentMillisec + now.getTime());

			var sContent	= lang('nowTimeIs', now.toLocaleTimeString()) + ', '+ lang('pleaseDJKQ', jsData.time);
			var sTitle		= lang('nowKQType', jsData.type);
			var sUrl		= '#';
			var cid			= jsData.id;
			var cnum		= jsData.num;

			attendanceNoticeWindow = new CNOA.attendanceNoticeWindow(sTitle, sContent, sUrl, cid, cnum);
			attendanceNoticeWindow.show(); 
			CNOA_attendanceNoticeWindow_closed = false;	
		}
		
		function funcAlert(){
			
			if(CNOA_attendanceNoticeWindow_closed == false){
				return;
			}
			Ext.Ajax.request({
				url: _this.baseUrl + "&type=loadData",
				method: 'GET',
				success: function(r){
					var sText = r.responseText;
					if(sText != ''){
						var jsData = Ext.decode(sText);
						try{
							if(jsData.alert == 1){
								
								try{
									CNOA_MAIN_DESKTOP_USRE_HR_CLOCK_OBJ.getInfo();
								}catch(e){}
								
								//启用了“上下班登记提醒”							
								CNOA_attendanceNoticeWindow_jsData = jsData;
								checktime();
							}
						}catch(e){
							//alert('error');
						}
					}				
				}
			});	

					
			
		};

		
		funcAlert();
		var intv = setInterval(function(){
			funcAlert();
		}, 1000 * 60);		
	}
}

new CNOA.attendanceNotice();
