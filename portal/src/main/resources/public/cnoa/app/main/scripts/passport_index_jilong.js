/**
 * 公用变量
 */
var isLogin = false;
var CNOAPASSPORT;
var ID_btn_btnLogin = Ext.id();
var ID_txt_username = Ext.id();
var ID_txt_password = Ext.id();
var ID_txt_authcode = Ext.id();



/**
 * 取回密码
 */
var CNOA_getPassword = CNOA.Class.create();
CNOA_getPassword.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = 'index.php?app=main&func=passport&action=getPasswd';

		this.formPanel = new Ext.form.FormPanel({
			hideBorders: true,
			border: false,
			waitMsgTarget: true,
			labelWidth: 70,
			items: [
				{
					xtype: "panel",
					layout: "form",
					bodyStyle: "padding: 10px;",
					border: false,
					defaults: {
						width: 240
					},
					items: [
						{
							xtype: "textfield",
							fieldLabel: lang('loginUserName'),
							allowBlank: false,
							name: "username"
						},
						{
							xtype: "textfield",
							fieldLabel: lang('myMailbox'),
							allowBlank: false,
							vtype: 'email',
							name: "email"
						},
						{
							xtype: "displayfield",
							hideLabel: true,
							width: 314,
							style: "margin-top:10px",
							value: "<span class='cnoa_color_gray'>" + lang('ifYouForgetPassword') + "</span>"
						}
					]
				}
			]
		});
		
		this.mainPanel = new Ext.Window({
			width: 350,
			height: 180,
			layout: "fit",
			modal: true,
			maximizable: false,
			draggable: true,
			closable: true,
			resizable: false,
			title: lang('getMyPassword'),
			items: [this.formPanel],
			buttons: [{
				text: lang('ok'),
				handler: function() {
					_this.submit();
				}
			},
			{
				text: lang('cancel'),
				handler: function() {
					_this.close();
				}
			}]
		});
	},
	
	show : function(){
		this.mainPanel.show();
	},
	
	close : function(){
		this.mainPanel.close();
	},
	
	submit : function(){
		var _this = this;
		
		var f = this.formPanel.getForm();
		if(f.isValid()){
			f.submit({
				url: _this.baseUrl,
				waitTitle: lang('waiting'),
				method: 'POST',
				waitMsg: lang('loading'),
				params: {},
				success: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						_this.close();
					}.createDelegate(this));
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){});
				}.createDelegate(this)
			});
		}
	}
}

function makeAuthUrl(){
	return 'index.php?app=main&func=common&action=commonJob&act=authcode&r=_'+Math.random();
}
function refreshAuth(obj){
	if(obj == undefined){
		obj = document.getElementById("authCodeImg");
	}
	obj.src = makeAuthUrl();
}

/**
 * 主界面-登录
 */
function CNOA_PASSPORT(){
	this.mainPanel = new Ext.Panel({
		border: false,
		hideBorders: true,
		width: 248,
		height: 150,
		bodyStyle: "background:none",
		style: "margin-left:344px;margin-top:85px;background:none",
		layout: "form",
		items: [
			{
				xtype: "panel",
				bodyStyle: "background:none;padding-left:54px;",
				items: [
					{
						xtype: "textfield",
						width: 136,
						style: 'margin-top:27px;margin-bottom:4px;',
						name: 'userName',
						id: ID_txt_username,
						hideLabel: true,
						allowBlank: false,
						blankText: CNOA.lang.tipUsernameMustNotEmpty,
						value: CNOA_LOGIN_USERNAME == "" ? "" : CNOA_LOGIN_USERNAME
					},
					{
						xtype: "textfield",
						width: 136,
						name: 'passWord',
						id: ID_txt_password,
						inputType: 'password',
						hideLabel: true,
						selectOnFocus: true,
						allowBlank: false,
						blankText: CNOA.lang.tipPasswordMustNotEmpty
					}
				]
			},
			{
				xtype: "panel",
				bodyStyle: "background:none",
				id: "windowTip",
				html: "&nbsp;",
				height: 22
			},
			{
				xtype: "panel",
				layout: 'table',
				bodyStyle: "background:none",
            	layoutConfig: {
					columns: 3
				},
				defaults:{
				    border: false
				},
				items: [
					{
						xtype: "box",
						autoEl: {
							style: "margin-left:124px",
							tag: "a",
							href: "javascript:void(0);",
							onclick: "doLogin();",
							html: "<img src='./resources/images/jilong/login_btn.png' />"
						}
					}/*
					{
						xtype: "button",
						width: 60,
						id: ID_btn_btnLogin,
						text: CNOA.lang.btnLogin,
						style: "margin-left:10px",
						handler: doLogin
					}*/
				]
			}
		]
	});
	
	this.loginForm = new Ext.FormPanel({
		border: false,
		waitMsgTarget: true,
		defaultType: 'textfield',
		labelWidth: 50,
		height: 245,
		bodyStyle: "background:none",
		items: this.mainPanel
	});

	this.ctPanel = new Ext.Panel({
		border: false,
		bodyStyle: 'width:602px;heigth:304px;background:url(./resources/images/bg_main_jilong.png);',
		items: [this.loginForm],
		listeners: {
			render: function(){
				try{
					pngfix();
				}catch(e){}
			}
		}
	});

	this.loginPanel = new Ext.Window({
		frame: false,
		autoCreate: true,
		resizable: false,
		constrain: true,
		minimizable: false,
		maximizable: false,
		stateful: false,
		modal: false,
		shim: true,
		shadow: false,
		buttonAlign: "center",
		plain: true,
		footer: false,
		closable: false,
		layout: "fit",
		width: 604,
		height:306,
		bodyStyle: "border-width:0",
		items: [this.ctPanel],
		keys: [{
            key: Ext.EventObject.ENTER,
            fn: doLogin,
            scope:this
        }]
	});
}

/**
 * 显示取回密码窗口
 */
function showGetPassWindow(){
	//var CNOAGETPASSPORT = new CNOA_GETPASSPORT();
	var CNOA_getPasswords = new CNOA_getPassword();
	CNOA_getPasswords.show();
}


var openWindow = function (config){
	if(!config.width){
		config.width = screen.availWidth;
		config.height = screen.availHeight;
	}
	if(!config.target){
		config.target='_blank';
	}

	var centered;
	x = (screen.availWidth - config.width) / 2;
	y = (screen.availHeight - config.height) / 2;
	centered =',width=' + config.width + ',height=' + config.height + ',left=' + x + ',top=' + y + ',scrollbars=yes,resizable=yes,status=no';

	var popup = window.open(config.url, config.target, centered);
	
	
	if(!popup){
		CNOA.msg.alert(lang('wuFaQuanPing'));
		return false;
	}
	
  if (!popup.opener) popup.opener = self;
  
	popup.focus();
	
	return popup;
}

function closeWindow(){   
	window.opener=null;   
	//window.opener=top;   
	window.open("","_self");   
	window.close(); 
}

function setWindowTip(msg){
	Ext.fly("windowTip").update("<div style='height:22px' class='cnoa_color_red'>"+msg+"&nbsp;</div>");
}

function doLogin(){
	if(isLogin){
		if(CNOAPASSPORT.fullScreenCheckBox.getValue()){
			openWindow({url: 'index.php'});
			closeWindow();
		}else{
			window.location.href = 'index.php';
		}
		return;
	}

	if(!CNOAPASSPORT.loginForm.getForm().findField('userName').isValid()){
		CNOAPASSPORT.loginForm.getForm().findField('userName').focus(true, 100);
		CNOA.miniMsg.alertShowAt(ID_txt_username, lang('fillLoginAccount'), 5, 22);
		return;
	}

	if(!CNOAPASSPORT.loginForm.getForm().findField('passWord').isValid()){
		CNOAPASSPORT.loginForm.getForm().findField('passWord').focus(true, 100);
		CNOA.miniMsg.alertShowAt(ID_txt_password, lang('fillLoginPassword'), 5, 22);
		return;
	}

	if (CNOAPASSPORT.loginForm.getForm().isValid()) {
		CNOAPASSPORT.loginForm.getForm().submit({
			url: 'index.php?app=main&func=passport&action=login',
			waitTitle: lang('waiting'),
			method: 'POST',
			waitMsg: CNOA.lang.msgLoginWaitMsg,
			params: {'loginSubmit' : 1},
			success: function(form, action) {
				isLogin = true;
				var loginResult = action.result.success;
				var errmsg = action.result.msg;
				window.location.href = 'index.php';
			},
			failure: function(form, action) {
				var errmsg = action.result.msg;
				var msg = errmsg;
				CNOA.msg.alert(msg, function(){
					CNOAPASSPORT.loginForm.getForm().findField('passWord').focus(true);
					setWindowTip(msg);
				});
			}
		});
	}else{
		if(CNOA.enableLoginAuth == 1){
			CNOA.miniMsg.alertShowAt(ID_btn_btnLogin, lang('zQTXuserName'));
		}else{
			CNOA.miniMsg.alertShowAt(ID_btn_btnLogin, lang('userNameAndPassword'));
		}
	}
}

Ext.onReady(function() {
	setTimeout(function() {
		Ext.get('cnoa-x-mask-loading-msg').remove();
	},
	50);
	
	CNOAPASSPORT = new CNOA_PASSPORT();
	CNOAPASSPORT.loginPanel.show(document.body, function(){
		CNOAPASSPORT.loginForm.getForm().findField('userName').focus(true, 500);
	
		if(CNOA_LOGIN_EXPIRE == 1){
			setWindowTip(lang('sessionTimeOut'));
		}
		if(CNOA_LOGIN_DLOGIN == 1){
			setWindowTip(lang('otherClient'));
		}
	});
});