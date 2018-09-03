var attNoticeWindow, 
	CNOA_attNotice_data,
	CNOA_attNoticeWindow_closed;

CNOA.attNoticeWindow = CNOA.Class.create();
CNOA.attNoticeWindow.prototype = {
	init : function(content, title, closeTime, callBack){
		var _this 		= this;
		this.baseUrl 	= "index.php?app=att&func=person&action=classes";
		this.title 		= title;
		this.content 	= content;
		this.dingshi;

		//位置
		this.position 	= "br";
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
								_this.checkIn();
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
			width: 300,
			height: 200,
			shadow: false,
			animCollapse: true,
			title: lang('noticeTitle2'),
			plain: true,
			modal: false,
			closable: false,
			maximizable: false,
			draggable: true,
			resizable: false,
			items:[this.mainContent, this.footBtn],
			listeners: {
				show: function(th){
					th.toFront();
					if(_this.position == "br"){
						th.getEl().alignTo(Ext.getBody(), 'br-br');
					}
				},
				close: function(){
					if(callBack){
						callBack();
					}
				}
			}
		});

		_this.autoClose(closeTime);
		
		return this.mainPanel;

	},

	makeContentPanel : function(){
		return new Ext.Panel({
			border: false,
			height: 120,
			autoScroll: true,
			bodyStyle: "padding:5px",
			html: "<div style='color:#800000;font-weight:bold;font-size:12px;line-height:25px;display:"+(((this.title=="")||(this.title==undefined))?"none":"block")+";'>"+this.title+"</div> <div style='text-indent:25px;line-height:18px;'>"+this.content+"</div>"
		})
	},

	show: function(position){
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

	close: function(){
		var _this = this;

		if(_this.position == "br"){
			_this.mainPanel.getEl().slideOut("b",{
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

		clearTimeout(_this.dingshi);
		CNOA_attNoticeWindow_closed = true;
	},

	autoClose : function(closeTime){
		var _this = this;
		
		var now = Date.parse(new Date());
		var close = (closeTime+"000")-now;

		_this.dingshi = setTimeout(function(){
			_this.close();
		},close);
	},

	checkIn: function(){
		var _this = this;
		var jsData = CNOA_attNotice_data;
		Ext.Ajax.request({
			url: "index.php?app=att&func=person&action=register&task=addChecktime",
			method: "POST",
			params: {'classes': jsData.cname, 'num': jsData.num, 'stime': jsData.stime, 'etime': jsData.etime, 'time': jsData.time, 'workType': jsData.workType},
			success: function(r){
				
				var r = Ext.decode(r.responseText);
				if(r.failure === true){
					CNOA.msg.alert(r.msg);
				}else{
					CNOA.msg.notice2(r.msg);
					if(CNOA_MAIN_DESKTOP_ATT_REGISTER_OBJ){
						CNOA_MAIN_DESKTOP_ATT_REGISTER_OBJ.getInfo();
					}
					
				}
				_this.close();
			}

		})
	}
}

CNOA.attNotice = CNOA.Class.create();
CNOA.attNotice.prototype = {
	init : function(){
		var _this = this;
		var close = true;
		this.baseUrl = "index.php?app=att&func=person&action=classes&task=checkAlert";

		function checkTime(){
			var jsData 	= CNOA_attNotice_data;
			var now 	= new Date();
			now.setTime(differentMillisec + now.getTime());
			
			var sContent	= lang('nowTimeIs', now.toLocaleTimeString()) + ', '+ lang('pleaseDJKQ', jsData.time);
			var sTitle 		= lang('nowKQType', jsData.type);
			if(close){
				attNoticeWindow = new CNOA.attNoticeWindow(sContent, sTitle, jsData.closeTime, callBack);
				attNoticeWindow.show();
				close = false;
			}
			//CNOA_attNoticeWindow_closed = false;
		}

		function checkAlert(){
			// console.log("check");
			//if(CNOA_attNoticeWindow_closed == false){
			//	return;
			//}

			Ext.Ajax.request({
				url: _this.baseUrl,
				method: "GET",
				success: function(r){
					
					if(r.responseText){
						var jsData = Ext.decode(r.responseText);

						try{
							if(jsData.alert == 1){
								if(jsData.strideDate == 1){
									jsData.closeTime += 60 * 60 * 24;
								}
								CNOA_attNotice_data = jsData;
								checkTime();
							}else{
								try{attNoticeWindow.close();}catch(e){}
							}

						}catch(e){
							try{attNoticeWindow.close();}catch(e){}
						}

					}
				}
			})
		};

		checkAlert();
		var intv = setInterval(function(){
			checkAlert();
		}, 1000 * 20);
		function callBack(){
			//close变量标记上一个窗口是否已经关闭
			setTimeout(function(){
				close = true;
			},1000*20)
		}
	}
}

new CNOA.attNotice();