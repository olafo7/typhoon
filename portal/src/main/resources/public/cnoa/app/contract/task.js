


// 弹出窗口类
CNOA.ntContractBusinessNoticeWindow = CNOA.Class.create();
CNOA.ntContractBusinessNoticeWindow.prototype = {
	init : function(title, content, href){
		var _this = this;		
		this.baseUrl	= "index.php?app=contract&func=business&action=notice";

		this.title		= title;
		this.content	= content;
		this.href		= href;
		
		//位置
		this.position = "br";		
		this.mainContent = this.makeContentPanel();

		this.footBtn = new Ext.Panel({
			border: false,
			height: 30,
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
							text: "我知道了",
							cls: 'btn-blue4',							
							handler: function(){																
								//_this.detailBtnHandler();
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
			height:215,
			//id: "CNOA_SYSTEM_NOTICE_WINDOW_ID_"+this.id,
			shadow:false,
			animCollapse : true,
			title:"合同提醒",
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
		
		Ext.Ajax.request({
			url: _this.baseUrl + "&task=checkin",
			method: 'GET',
			success: function(r){
				var sText = r.responseText;
				jsData = Ext.decode(sText);
				CNOA.msg.alert(jsData.msg);
			}
		});
			
		//	mainPanel.closeTab(CNOA_APP_NOTICE_FROMTYPE[this.type].id);
		//	mainPanel.loadClass(this.href, CNOA_APP_NOTICE_FROMTYPE[this.type].id, CNOA_APP_NOTICE_FROMTYPE[this.type].tname, CNOA_APP_NOTICE_FROMTYPE[this.type].icon);
		
	},
	
	makeContentPanel : function(){
		return new Ext.Panel({
			border: false,
			height: 136,
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
	}
}


CNOA.ntContractBusinessNotice = CNOA.Class.create();
CNOA.ntContractBusinessNotice.prototype = {
	init : function(){
		var _this		= this;			
		this.baseUrl	= "index.php?app=contract&func=business&action=notice";
		
	
		if(CNOA_IN_AIR == 1){
			//CNOA.parentSandBridge.attendanceNotice();
			return;
		}


		
		function funcAlert(){
			

			Ext.Ajax.request({
				url: _this.baseUrl + "&task=noticeCheck",
				method: 'GET',
				success: function(r){
					var sText = r.responseText;
					if(sText != ''){
						var jsData = Ext.decode(sText);
						try{	
							var items = jsData.data;
							if(items.length > 0){
								for(var i=0; i<=items.length - 1; i++){
									//跳出窗口
									//查看后，则
									var item		= items[i];
									var contract	= item.contract;
									var sContent	= item.content;
									var sTitle		= '<div style="font-weight:bold; color:#FF6699;">《' + contract + '》合同提醒</div> ' + item.title + '';
									var sUrl		= '#';
									var win = new CNOA.ntContractBusinessNoticeWindow(sTitle, sContent, sUrl);
									win.show(); 
								}
							}
						
						}catch(e){
						}
					}				
				}
			});	

					
			
		};
		
		
		// 50秒跑一次
		// 到数据库查询，有哪个需要提醒的
		// 如果有两个都是同一时间，咋办？
		
		funcAlert();
		var intv = setInterval(function(){
			funcAlert();
		}, 1000 * 50);//60 * 1);

			
		
	}
}

new CNOA.ntContractBusinessNotice();