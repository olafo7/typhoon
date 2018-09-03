var CNOA_videosession_meetingClass, CNOA_videosession_meeting;

// 修改用户档案资料
CNOA_videosession_meetingClass = CNOA.Class.create();
CNOA_videosession_meetingClass.prototype = {

	init : function(){
		var _this = this;

		//保存按钮
		this.ID_btn_save = Ext.id();
		//应用按钮
		this.ID_btn_apply = Ext.id();
		//关闭按钮
		this.ID_btn_close = Ext.id();

		this.roomID			= 10000;
		this.userType		= 1;
		this.roomPwd		= '';
		//this.serverUrl		= 'TCP:122.225.104.210:1089;TCP:122.225.104.210:1089;';
		this.serverUrl		= 'TCP:videomeeting.cnoa.cn:3389;';

		this.formPanel = new Ext.form.FormPanel({
			hideBorders: true,
			border: false,
			waitMsgTarget: true,
			labelWidth: 60,
			labelAlign: "right",
			items: [
				{
					xtype: "panel",
					border: false,
					layout: "form",
					bodyStyle: "padding: 10px",
					defaults: {
						width: 230
					},
					items: [
						{
							xtype: "textfield",
							name: "username",
							fieldLabel: "用户名",
							allowBlank: false
						},
						{
							xtype: "textfield",
							name: "password",
							fieldLabel: "密码",
							allowBlank: false,
							inputType: "password"
						}
					]
				}
			]
		});

		this.mainPanel = new Ext.Window({
			width: 337,
			height: 175,
			//modal: true,
			autoDestroy: true,
			closeAction: "close",
			resizable: false,
			title: "打开视频会议",
			layout: "fit",
			items: [this.formPanel],
			listeners:{
				close: function(){
					Ext.destroy(CNOA_videosession_meeting);
					CNOA_videosession_meeting = null;
					try{
						_this.closeTab();
					}catch(e){}
				}
			},
			buttons: [
				{
					text: "确定",
					id: this.ID_btn_save,
					iconCls: 'icon-btn-save',
					handler: function(){
						this.submitForm({close: true});
					}.createDelegate(this)
				},
				{
					text: "取消",
					id: this.ID_btn_close,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						_this.close();
					}
				}
			]
		});
	},

	LoginConf : function (NodeManAddr,roomID,roomPwd,UserName,UserPassword,userType ){
		var GroupName="Fastonz";
		var ProductName="FMDesktop";
		var ClientName="FMDesktop";
		var room_para;

		room_para = "-link"+" " + NodeManAddr +  " "+"-rid"+" "+roomID + " " + "-uname" + " "+UserName+" "+"-utype"+" "+userType;

		if( userType != '0' && UserPassword != "" ){
			room_para += " "+"-upwd"+" "+UserPassword;
		}
		
		if( userType == '0' && roomPwd != "" ){
			room_para += " "+"-rpwd"+" "+roomPwd;
		}

		try {
			ClientLoader.Run(GroupName,ProductName,room_para);
		} catch (e){
			alert("您还未安装客户端，请先下载安装“好视通”");
			location.href="http://www.fsmeeting.com/download/FMDesktop.exe";
			return ;
		}	
	},

	show : function(){
		var _this = this;

		this.mainPanel.show();

		if(!Ext.isIE){
			CNOA.msg.alert("该功能需要在IE浏览器下才可以使用，请使用IE浏览器打开本功能。", function(){
				_this.close();
			});
		}
	},
	
	close : function(){
		this.mainPanel.close();
	},
	
	closeTab : function(){
		mainPanel.closeTab(CNOA.videosession.session.parentID.replace("docs-", ""));
	},
	
	submitForm : function(){
		var _this = this;
		
		if (this.formPanel.getForm().isValid()) {
			var username = this.formPanel.getForm().findField("username").getValue();
			var password = this.formPanel.getForm().findField("password").getValue();
			
			this.LoginConf(this.serverUrl, this.roomID, this.roomPwd, username, password, this.userType);
		}
	}
}

if(CNOA_videosession_meeting == null){
	CNOA_videosession_meeting = new CNOA_videosession_meetingClass();
	CNOA_videosession_meeting.show();
}
Ext.getCmp(CNOA.videosession.session.parentID).on("close", function(){
	try{
		CNOA_videosession_meeting.close();
	}catch(e){}
});