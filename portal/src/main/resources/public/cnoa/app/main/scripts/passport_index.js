var isLogin = false;

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
var closeWindow = function(){   
	window.opener=null;   
	//window.opener=top;   
	window.open("","_self");   
	window.close(); 
}

var LogoComponent = Ext.extend(Ext.BoxComponent, {
	onRender : function(ct, position){
		this.el = ct.createChild({tag: 'div', cls: "go-app-logo"});
	}
});

var langCombo = new Ext.form.ComboBox({
	fieldLabel: lang('language'),
	name: 'language',
	store:  new Ext.data.SimpleStore({
			fields: ['value', 'language'],
			data: CNOA.config.languageList
		}),
	width: 246,
	//disabled: true,
	hiddenName: 'language',
	valueField: 'value',
	displayField:'language',
	mode: 'local',
	triggerAction:'all',
	forceSelection: true,
	editable: false,
	value : CNOA.config.defaultLanguage
});

langCombo.on('select', function(){
	document.location = 'index.php?app=main&func=config&action=changeLanguage&setLanguage='+langCombo.getValue();
}, this);

var setWindowTip = function(msg){
	Ext.fly("loginWindowId").update(msg);
}

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
			title: "取回密码",
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
				waitTitle: lang('notice'),
				method: 'POST',
				waitMsg: lang('waiting'),
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

Ext.onReady(function() {
	var fullScreenCheckBox = new Ext.form.Checkbox({
		name: "customPermit",
		hideLabel: true,
		style: "margin-top: 8px",
		boxLabel: lang('fullWindow'),
		listeners:{
			check : function(th, checked){
				if(checked == true){
					setWindowTip(lang('turnOffPopUp'));
				}else{
					setWindowTip("");
				}
			}
		}
	})

	var loginForm = new Ext.FormPanel({
		el: 'formPanel',
		id: 'loginForm',
		name: 'loginForm',
		deferredRender: false,
		border: false,
		waitMsgTarget: true,
		hideBorders: true,
		width:385,
		region: "center",
		items: {
			el: 'loginInfo',
			bodyStyle: "padding:15px;",
			layout: 'form',
			defaultType: 'textfield',
			items: [new LogoComponent(), langCombo, {
				fieldLabel: lang('loginUserName'),
				width: 246,
				name: 'userName',
				allowBlank: false,
				blankText: lang('pleaseAccount')
			},
			{
				fieldLabel: lang('password'),
				width: 246,
				name: 'passWord',
				inputType: 'password',
				selectOnFocus: true,
				allowBlank: false,
				value: "",
				blankText: lang('pleaseEnterPassword')
			},
			fullScreenCheckBox
			]
		}
	});
	Ext.Msg.minWidth = 250;
	
	var doLogin = function(){
		if(isLogin){
			if(fullScreenCheckBox.getValue()){
				openWindow({url: 'index.php'});
				closeWindow();
			}else{
				window.location.href = 'index.php';
			}
			return;
		}

		if(!win.getComponent('loginForm').getForm().findField('userName').isValid()){
			win.getComponent('loginForm').getForm().findField('userName').focus(true);
			return;
		}

		if(!win.getComponent('loginForm').getForm().findField('passWord').isValid()){
			win.getComponent('loginForm').getForm().findField('passWord').focus(true);
			return;
		}

		if (win.getComponent('loginForm').getForm().isValid()) {
			win.getComponent('loginForm').getForm().submit({
				url: 'index.php?app=main&func=passport&action=login',
				waitTitle: lang('notice'),
				method: 'POST',
				waitMsg: lang('passportLoginWaitMsg'),
				params: {'loginSubmit' : 1},
				success: function(form, action) {
					isLogin = true;
					var loginResult = action.result.success;
					var errmsg = action.result.msg;
					if(fullScreenCheckBox.getValue()){
						openWindow({url: 'index.php'});
						closeWindow();
					}else{
						window.location.href = 'index.php';
					}
				},
				failure: function(form, action) {
					var errmsg = action.result.msg;
					CNOA.msg.alert(errmsg, function(){
						win.getComponent('loginForm').getForm().findField('passWord').focus(true);
						setWindowTip(errmsg);
					});
				}
			});
		}
	}

	var win = new Ext.Window({
		el: 'loginWindow',
		id: 'loginWindow',
		width: 400,
		height: 273,
		layout: "border",
		closeAction: 'hide',
		plain: true,
		title: lang('loginSystem'),
		maximizable: false,
		draggable: true,
		closable: false,
		resizable: false,
		items: loginForm,
		buttonAlign: 'right',
		buttons: [{
			text: lang('fogetPassword'),
			handler: function() {
				var CNOA_getPasswords = new CNOA_getPassword();
				CNOA_getPasswords.show();
			}
		},
		{
			text: lang('templates.login'),
			handler: doLogin
		}],
		keys: [{
            key: Ext.EventObject.ENTER,
            fn: doLogin,
            scope:this
        }]
	});

	win.show(document.body, function(){
		win.getComponent('loginForm').getForm().findField('userName').focus(true, 500);

		//加入提示元素:
		var newDiv = document.createElement("div");
		newDiv.id = "loginWindowId";
		newDiv.style.cssText="display: block; width: 200px; font-size: 12px; float: left; height: 22px; color: red; line-height: 26px;";
		var parentDiv = Ext.getCmp("loginWindow").toolbars[0].getEl().dom;
		parentDiv.parentNode.insertBefore(newDiv, parentDiv);

		if(CNOA_LOGIN_EXPIRE == 1){
			setWindowTip(lang('sessionTimeOut'));
		}
		if(CNOA_LOGIN_DLOGIN == 1){
			setWindowTip(lang('otherClient'));
		}
	});
	
	win.on("move", function(th, x, y){
		win.getComponent('loginForm').getForm().findField('userName').focus(true, 500);
	});
});

