/**
 * 全局变量
 */
var CNOA_odoc_send_check_edit, CNOA_odoc_send_check_editClass;
var CNOA_odoc_send_check_selectFlow, CNOA_odoc_send_check_selectFlowClass;
var CNOA_odoc_send_check_edit_dealTime = "";

/*
=============================================
显示服务器时间
*/
//用来存放差值
var differentMillisec = 0;

function initServerTime() {
    //取时间差值
	getServerDate();
}

//取得显示时间
function showtime(){
	now = new Date();
	now.setTime(differentMillisec + now.getTime());
	str = now.format('Y年m月d日 H:i:s');
	
	CNOA_odoc_send_check_edit_dealTime = now.format('Y.m.d H:i');
	var today = new Array(lang('sunday'), lang('monday'), lang('tuesday'), lang('wednesday'), lang('thursday'), lang('friday'), lang('saturday'));
	str += " [" + today[now.getDay()] + "]";
	try{Ext.getCmp("CNOA_SERVERTIME_BAR").getEl().update(str);}catch(e){}
	ctroltime=setTimeout("showtime()", 1000);
}

function getServerTime(tarea_id, tarea_va){
	tarea_va = tarea_va.replace("{TIME}", CNOA_odoc_send_check_edit_dealTime);
	jQuery("textarea[name^='"+tarea_id+"']").attr('value', tarea_va);
}

//从服务器取时间,用的buffalo取
function getServerDate() {
	begin = new Date();
	millisecbeg = begin.getTime();

	Ext.Ajax.request({
		url: 'index.php?action=commonJob&act=getServerTime13',
		method: "POST",
		success: function(r, opts) {
			var serverMillisec = r.responseText;
			end = new Date();
			millisecend = end.getTime();
			differentMillisec = serverMillisec - new Date() + (millisecend - millisecbeg)/2;
			//取得显示时间
			showtime();
		},
		failure: function(response, opts) {
			CNOA.msg.alert(result.msg);
		}
	});
}
getServerDate();
/*
显示服务器时间
=============================================
*/

maxsizeWindow();

CNOA_odoc_send_check_selectFlowClass = CNOA.Class.create();
CNOA_odoc_send_check_selectFlowClass.prototype = {
	init: function(id){
		var _this = this;
		
		var ID_SET_UID		= Ext.id();
		var ID_CHOICE_VALUE	= Ext.id();
		
		this.from = "fixed";
		
		this.baseUrl = "index.php?app=odoc&func=send&action=check";
		
		this.ID_say = Ext.id();
	
		this.fixed = new Ext.form.FormPanel({
			border: false,
			labelWidth: 80,
			labelAlign: 'top',
			region : "center",
			autoScroll: true,
			waitMsgTarget: true,
			items : [
				{
					xtype: "panel",
					border: false,
					layout: "form",
					bodyStyle: "padding:10px;",
					items: [
						{
							xtype: 'textarea',
							fieldLabel: '办理意见',
							width: 450,
							height: 210,
							value :'已阅',
							name: 'say',
							id: this.ID_say
						},{
							xtype: 'radiogroup',
							labelWidth: 1,
							width: 180,
							hideLabel: true,
							items:[
								{
									boxLabel: '已阅',
									name: 'send_check_say',
									checked: true,
									inputValue: 1
								},{
									boxLabel: '同意',
									name: 'send_check_say',
									inputValue: 2
								},{
									boxLabel: '不同意',
									name: 'send_check_say',
									inputValue: 3
								}
							],
							listeners : {
								"change" : function(th, checked){
									if(checked.inputValue=="1"){
										Ext.getCmp(_this.ID_say).setValue("已阅");
									}else if(checked.inputValue=="2"){
										Ext.getCmp(_this.ID_say).setValue("同意");
									}else if(checked.inputValue=="3"){
										Ext.getCmp(_this.ID_say).setValue("不同意");
									}
								}
							}
						}
					]
				}
			]
		});
		//绑定流程结束
		
		this.saveDocFile = function(){
			if(Ext.isIE){
				try{
					var webObj = document.getElementById("CNOA_WEBOFFICE");
					var returnValue;
					webObj.HttpInit();
					webObj.HttpAddPostString("id", id);
					webObj.HttpAddPostCurrFile("msOffice", "");
					returnValue = webObj.HttpPost(CNOA_odoc_send_check_edit.baseURI + CNOA_odoc_send_check_edit.baseUrl + "&task=submitFileData");
				}catch(e){
					alert("异常\r\nError:"+e+"\r\nError Code:"+e.number+"\r\nError Des:"+e.description);
				}
			}
		};
		
		
		/**
		 * 保存审批结果和意见
		 */
		this.saveTo = function(){
			var f = this.fixed.getForm();
			if (f.isValid()) {
				f.submit({
					url: _this.baseUrl + '&task=saveSay',
					waitTitle: lang('notice'),
					method: 'POST',
					waitMsg: lang('waiting'),
					params: {id: id},
					success: function(form, action) {
						_this.saveDocFile();
						CNOA.msg.notice(action.result.msg, "公文管理");
						try{
							opener.CNOA_odoc_send_check.store.reload();
						}catch(e){}
						_this.mainPanel.close();
						window.close();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}
				});
			}else{
				CNOA.miniMsg.alertShowAt(ID_btn_save, lang('formValid'));
			}
			
			if (CNOA_odoc_send_check_edit.form.getForm().isValid()) {
				CNOA_odoc_send_check_edit.form.getForm().submit({
					url: _this.baseUrl + "&task=submitFormData",
					waitMsg: lang('waiting'),
					params: {id : id},
					method: 'POST',	
					success: function(form, action) {
						
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}
		}
		
		this.mainPanel = new Ext.Window({
			title : "审批步骤",
			layout : "border",
			resizable : false,
			autoScroll: false,
			width : 500,
			height : 380,
			modal : true,
			items : [this.fixed],
			buttons : [
				{
					text : "提交",
					iconCls: 'icon-dialog-apply2',
					handler : function(){
						_this.saveTo();
					}
				},
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function(){
						_this.mainPanel.close();
					}
				}
			],
			listeners: {
				'show' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(false);
				},
				'close' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(true);
				}
			}
		}).show();
	}
};


CNOA_odoc_send_check_editClass = CNOA.Class.create();
CNOA_odoc_send_check_editClass.prototype = {
	init : function(){
		var _this = this;
		
		this.id	= CNOA.odoc.id;
		this.ID_attachCt = Ext.id();
		
		this.baseUrl = "index.php?app=odoc&func=send&action=check";
		this.baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
		
		/*
		this.webofficeHtml  = '<object id="CNOA_WEBOFFICE" height="768" width="100%" style="left: 0px; TOP: 0px" classid="clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5" codebase="resources/weboffice/v6.0.5.0.cab#version=6,0,5,0">';
		this.webofficeHtml += '<param name="_ExtentX" value="6350">';
		this.webofficeHtml += '<param name="_ExtentY" value="6350">';
		this.webofficeHtml += '要显示公文正文内容，请切换到IE浏览器下查看';
		this.webofficeHtml += '</object>';
		*/

		this.webofficeHtml = CNOA.webObject.getWebOffice("100%", 768, 'left: 0px; TOP: 0px');

		this.attachTpl = new Ext.XTemplate(
			'		{attach}'
		);
		
		this.hurryListStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl+"&task=getHurryList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name : "tid"},
					{name : "title"}
				]
			})
		});
		
		this.hurryListStore.load();
		
		
		this.baseField = [
			/*{
				xtype: "fieldset",
				title: "发文单",
				anchor: "97%",
				autoHeight: true,
				items: [
					{
						xtype : "textfield",
						width : 500,
						fieldLabel : "公文标题",
						allowBlank : false,
						name : "title",
						value : CNOA.odoc.title
					},
					{
						xtype : "textfield",
						fieldLabel : "文件字号",
						allowBlank : false,
						name : "number",
						width : 188
					},
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 2
						},
						items : [
							{
								xtype : "panel",
								layout : "form",
								items : [
									{
										xtype : "textfield",
										width : 188,
										fieldLabel : "拟稿人",
										readOnly : true,
										name : "createpeople"
									}
								]
							},
							{
								xtype : "panel",
								layout : "form",
								items : [
									{
										xtype : "textfield",
										width : 188,
										fieldLabel : "拟稿单位",
										readOnly : true,
										name : "createdept"
									}
								]
							}
						]
					}
				]
			},*/
			{
				xtype: "fieldset",
				title: "发文单",
				anchor: "97%",
				html: "{FAWENFORM}"
			},
			{
				xtype: "fieldset",
				title: "审批进度",
				//anchor: "95%",
				//layout: 'fit',
				items: _this.checkProgress()
			},
			{
				xtype: "fieldset",
				title: lang('attach'),
				autoHeight: true,
				id: this.ID_attachCt
			},
			{
				xtype: "fieldset",
				title: "公文正文",
				anchor: "97%",
				items: [
					{
						xtype: 'panel',
						html: this.webofficeHtml,
						tbar: [
							{
								text: "显示痕迹",
								cls: "x-btn-over",
								listeners: {"mouseout" : function(btn){btn.addClass("x-btn-over");}},
								handler: function(){
									
								}
							},
							{
								text: "隐藏痕迹",
								cls: "x-btn-over",style: "margin-left:2px;",
								listeners: {"mouseout" : function(btn){btn.addClass("x-btn-over");}},
								handler: function(){
									
								}
							},"-"
						]
					}
				],
				listeners: {
					afterrender : function(){
						jQuery("input[name^='id_radio_']").each(function(){
							_this.makeHuiQianFunc(jQuery(this));
						});
					}
				}
			}
		];
		
		
		this.ID_saveButton	= Ext.id();
		this.ID_huiqian		= Ext.id();
		this.ID_nextStep	= Ext.id();
		this.ID_reStep		= Ext.id();
		this.ID_previous	= Ext.id();
		
		this.form = new Ext.form.FormPanel({
			labelWidth: 120,
			labelAlign: 'right',
			region : "center",
			waitMsgTarget: true,
			layout: "border",
			items:[
				{
					xtype: "panel",
					bodyStyle: "padding:10px;",
					border: false,
					//layout: "form",
					region: "center",
					autoScroll : true,
					items: this.baseField
				}
			],
			tbar: [
				"发文审批： ",
				{
					text: lang('save'),
					iconCls : "icon-btn-save",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submitFormData();
					},
					id: _this.ID_saveButton
				}
				
				,'-',
				{
					text: '会签',
					cls: "x-btn-over",
					iconCls : "icon-order-s-signIn",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler: function(){
						_this.huiqian();
					},
					id: _this.ID_huiqian
				}
				
				,'-',
				{
					text: "转下一步",
					iconCls : "icon-btn-arrow-right",
					cls: "x-btn-over",style: 'margin-left:3px;',
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submitFormData(true);
						//
					},
					id: _this.ID_nextStep
				}				
				,'-',//'-',
				{
					text: '退件',
					cls: "x-btn-over",
					iconCls : "icon_cnoa_dialog-cancel",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler: function(){
						//_this.huiqian();
						_this.reStep();
					},
					id: _this.ID_reStep
				}
				
				,'-',
				{
					text: '退上一步',
					cls: "x-btn-over",
					iconCls : "icon_cnoa_arrow_undo",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler: function(){
						_this.toPrevious();
					},
					id: _this.ID_previous
				}
				
				
				,"->",
				new Ext.BoxComponent({
					id: "CNOA_SERVERTIME_BAR",
					autoEl: {
						tag: 'div',
						html: ''
					}
				}),
				{
					text:"重新加载",
					iconCls:'icon-system-refresh',
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler:function(button, event){
						location.reload();
					}
				},"-",
				{
					text : "打印",
					iconCls: 'icon_cnoa_print',
					style: "margin-left:5px",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						//window.close();
					}
				},"-",
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					style: "margin-left:5px",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						window.close();
					}
				}
			]
		});
		
		this.mainPanel = new Ext.Viewport({
			layout: 'border',
			style: 'background-color:#fff;border-width: 1px;',
			autoScroll: true,
			items: [this.form]
		});
		
		this.loadFormData();
	},
	
	
	checkProgress : function(_this){		
		var _this = this;
		
		
		
		
		var fields = [
			{name : "id"},
			{name: "stepname"},
			{name: "from"},
			{name: "say"},
			{name: "stime"},
			{name: "status"}
		];
		
		var store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getStepList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
			listeners:{
				exception : function(th, type, action, options, response, arg){
					var result = Ext.decode(response.responseText);
					if(result.failure){
						CNOA.msg.alert(result.msg);
					}
				},
				"load" : function(th, rd, op){
					
				}
			}
		});
		
		store.setBaseParam('fid', _this.id);
		store.setBaseParam('status', 1);
		store.load();
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		});
		
		
		
		var colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			//sm,
			{header: "id", dataIndex: 'id', hidden: true},			
			{header: '步骤名称', dataIndex: 'stepname', width: 150, sortable: true, menuDisabled :true},
			{header: '状态', dataIndex: 'status', width: 100, sortable: true, menuDisabled :true},
			{header: '所在部门／经办人', dataIndex: 'from', width: 200, sortable: true, menuDisabled :true},
			{header: '意见', dataIndex: 'say', width: 180, sortable: true, menuDisabled :true},
			{header: '时间', dataIndex: 'stime', width: 123, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false, hidden: true}
		]);
		
		var grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store:store,
			loadMask : {msg: lang('waiting')},
			cm: colModel,
			sm: sm,
			autoHeight: true,
			listeners:{
				"rowdblclick":function(button, event){
					
				}
			}
		});
		
		
		
		return grid;
	},
	
	
	/**
	 * 退件操作
	 */
	reStep : function(){
		var _this = this;
		
		
		
		var frmSay = new Ext.form.FormPanel({
			border: false,			
			bodyStyle: "padding:20px;",
			//labelAlign: 'right',
			labelWidth: 70,
			//labelAlign: "top",
			items: [
				{
					xtype: 'textarea',
					width: 380,
					height: 170,
					fieldLabel: '退件原因',
					name: 'reason',
					allowBlank: false
				}
			]
		});
		
		
		
		
		 
		var winSay = new Ext.Window({
			title: "退件",
			modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 300,
			layout: "fit",
			items: frmSay,
			buttons:[
				{
					text: lang('ok'),
					handler: function(){
						//winSay.close();
						submit(_this);
					}
				},{
					text: lang('close'),
					handler: function(){
						winSay.close();
					}
				}
			],
			listeners: {
				'show' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(false);
				},
				'close' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(true);
				}
			}
		}).show();
		
		
		var submit = function(_this){
			
			var f = frmSay.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl + "&task=reStep",
					//waitTitle: lang('notice'),
					method: 'POST',
					//waitMsg: lang('waiting'),
					params: {fid: _this.id},
					success: function(form, action){
						CNOA.msg.notice(action.result.msg, "公文管理");
						window.close();
					}.createDelegate(this),
					
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}
		};
		
		return winSay;
		
		/*
		CNOA.msg.cf('执行退件操作后，不可恢复，您确定要继续吗？', function(btn) {
			if(btn == 'no'){				
				return;
			}
		});
		
		Ext.Ajax.request({
			url: _this.baseUrl + "&task=reStep&fid=" + _this.id,
			method: 'GET',
			success: function(r){
				var sText = r.responseText;
				var result = Ext.decode(sText);

				CNOA.msg.alert(result.msg, function(){
					window.close();
				});
			},
			failure: function(response, opts) {
				CNOA.msg.alert(result.msg);
			}
		});
		*/
	},
	
	
	loadFormData : function(){
		var _this = this;
		
		this.form.getForm().load({
			url: _this.baseUrl + "&task=loadFormData",
			method:'POST',
			params : {id : _this.id},
			waitTitle: lang('notice'),
			success: function(form, action){
				var data	= action.result.data;
				
				_this.attachTpl.overwrite(Ext.getCmp(_this.ID_attachCt).body, data);
				//cdump(data);
				
				var step	= data.stepData;
				var stepType= step.stepType;
				
				switch(parseInt(stepType)){
					case 1:
						break;
					case 2:		//会签
					/*
						this.ID_saveButton	= Ext.id();
						this.ID_huiqian		= Ext.id();
						this.ID_nextStep	= Ext.id();
						this.ID_reStep		= Ext.id();
						this.ID_previous	= Ext.id();
					*/
						Ext.getCmp(_this.ID_huiqian).setVisible(false);
						Ext.getCmp(_this.ID_reStep).setVisible(false);
						Ext.getCmp(_this.ID_previous).setVisible(false);
						Ext.getCmp(_this.ID_saveButton).setVisible(false);
						break;
				}
			},
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg, function(){
					
				});
			}
		});
	},
	
	submitFormData : function(gonext){
		var _this = this;
		
		this.setOfficeAcitveXDisplay(false);
		
		//this.form.getForm().findField('content').syncValue(); 
		
		var saveDocFile = function(){
			if(Ext.isIE){
				try{
					var webObj = document.getElementById("CNOA_WEBOFFICE");
					var returnValue;
					webObj.HttpInit();
					webObj.HttpAddPostString("id", _this.id);
					webObj.HttpAddPostCurrFile("msOffice", "");
					returnValue = webObj.HttpPost(_this.baseURI + _this.baseUrl + "&task=submitFileData");
					if("1" == returnValue){
						if(gonext != true){
							CNOA.msg.alert("已保存", function(){
								_this.setOfficeAcitveXDisplay(true);
							});
						}else{
							//转下一步
							_this.sendNext();
						}
						
						//try{
						//	//opener.CNOA_odoc_setting_editTemplate.reloadDocTpl();
						//}catch(e){}
						//window.close();
					}else if("0" == returnValue)
						alert("操作失败");
				}catch(e){
					alert("异常\r\nError:"+e+"\r\nError Code:"+e.number+"\r\nError Des:"+e.description);
				}
			}else{
				_this.sendNext();
			}
		};

		if (this.form.getForm().isValid()) {
			this.form.getForm().submit({
				url: _this.baseUrl + "&task=submitFormData",
				waitMsg: lang('waiting'),
				params: {id : this.id},
				method: 'POST',	
				success: function(form, action) {
					saveDocFile();
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}.createDelegate(this)
			});
		}
	},

	makeHuiQianFunc : function(radio){
		var tarea_id = radio.attr('name').replace("id_radio_", "id_");
		var tarea_va = radio.attr('cname') + " ("+CNOA.odoc.truename+" {TIME})";

		radio.click(function(){
			getServerTime(tarea_id, tarea_va);
		});
	},
	
	sendNext : function(){
		var _this =this;
		CNOA_odoc_send_check_selectFlow = new CNOA_odoc_send_check_selectFlowClass(_this.id);
	},
	
	setOfficeAcitveXDisplay : function(showHide){
		if(showHide){
			jQuery('#CNOA_WEBOFFICE').show();
		}else{
			jQuery('#CNOA_WEBOFFICE').hide();
		}
	},
	
	
	huiqian : function(){
		var _this = this;

		var ID_window	= Ext.id();
		var ID_stepName	= Ext.id();
		
		/*var submitFormData = function(){
			if (this.form.getForm().isValid()) {
				this.form.getForm().submit({
					url: _this.baseUrl + "&task=submitFormData",
					waitMsg: lang('waiting'),
					params: {id : this.id},
					method: 'POST',	
					success: function(form, action) {
						saveDocFile();
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}
		}*/
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=setHuiQianInfo",
					waitTitle: lang('notice'),
					method: 'POST',
					waitMsg: lang('waiting'),
					params: {fid: _this.id},
					success: function(form, action){
						//submitFormData();
						CNOA.msg.notice(action.result.msg, "公文管理");
						win.close();
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}
		};
		var getHuiQianInfo = function(){
			var f = form.getForm();
			f.load({
				url: _this.baseUrl + "&task=getHuiQianInfo",
				params: {fid: _this.id},
				method:'POST',
				waitTitle: lang('notice'),
				success: function(form, action){				
					var stepName = Ext.getCmp(ID_stepName);
					stepName.setValue('步骤名称: ' + action.result.stepname);
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}
			})
		};
		var form = new Ext.form.FormPanel({
			border: false,
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			items: [
				{
					xtype: "displayfield",
					hideLabel: true,
					value: "步骤名称: " ,
					name: "stepText",
					id: ID_stepName
				},
				{
					xtype: "textarea",
					width: 483,
					height: 188,
					//anchor: "-10",
					readOnly: true,
					fieldLabel: '会签人员',
					name: "allUserNames"
				},
				{
					xtype: "hidden",
					name: "allUids"
				},
				{
					xtype: "btnForPoepleSelector",
					autoWidth: true,
					dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					text: lang('selectPeople'),
					listeners: {
						"selected" : function(th, data){
							var names = new Array();
							var uids = new Array();
							if (data.length>0){
								for (var i=0;i<data.length;i++){
									names.push(data[i].uname);
									uids.push(data[i].uid);
								}
							}
							form.getForm().findField("allUserNames").setValue(names.join(","));
							form.getForm().findField("allUids").setValue(uids.join(","));
						},

						"onrender" : function(th){
							th.setSelectedUids(form.getForm().findField("allUids").getValue().split(","));
						},
						
						"afterrender" : function(){
							getHuiQianInfo();
						}
					}
				}
			]
		});
		var win = new Ext.Window({
			title: "设置本步骤会签人员",
			modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 360,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : lang('ok'),
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : lang('cancel'),
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			],
			listeners: {
				'show' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(false);
				},
				'close' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(true);
				}
			}
		}).show();
	},
	
	
	/**
	 * 退上一步
	 */
	toPrevious : function(){
		var _this = this;
		
		
		function submit33(){
			CNOA.msg.cf('执行“退上一步”操作后，不可恢复，您确定要继续吗？', function(btn) {
				if(btn == 'no'){				
					return false;
				}
			});
			return true;
		}
		
		
		
		var fields = [
			{name : "id"},
			{name: "stepname"},
			{name: "from"},
			{name: "say"},
			{name: "stime"},
			{name: "status"}
		];
		
		var store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getStepList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
			listeners:{
				exception : function(th, type, action, options, response, arg){
					var result = Ext.decode(response.responseText);
					if(result.failure){
						CNOA.msg.alert(result.msg);
					}
				},
				"load" : function(th, rd, op){
					
				}
			}
		});
		
		store.setBaseParam('fid', _this.id);
		store.setBaseParam('status', 1);
		store.setBaseParam('prev', true);
		store.load();
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: true
		});
		
		
		
		var colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			//sm,
			{header: "id", dataIndex: 'id', hidden: true},			
			{header: '步骤名称', dataIndex: 'stepname', width: 150, sortable: true, menuDisabled :true},
			{header: '状态', dataIndex: 'status', width: 100, sortable: true, menuDisabled :true},
			{header: '所在部门／经办人', dataIndex: 'from', width: 200, sortable: true, menuDisabled :true},
			{header: '意见', dataIndex: 'say', width: 180, sortable: true, menuDisabled :true},
			{header: '时间', dataIndex: 'stime', width: 123, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false, hidden: true}
		]);
		
		var grid = new Ext.grid.GridPanel({
			stripeRows : true,
			border:false,
			store:store,
			loadMask : {msg: lang('waiting')},
			cm: colModel,
			sm: sm,
			hideBorders: true,
			listeners:{
				"rowdblclick":function(button, event){
					
				}
			},
			tbar: new Ext.Toolbar({
				items:[
					{
						text: lang('refresh'),
						iconCls:'icon-system-refresh',
						handler: function(){
							store.reload();
						}
					}
					,'-',
					{
						xtype: 'label',
						style: 'color: #678',
						text: '选择要退回哪一个步骤，然后点击“确定”按钮即可.'
					}
					
				]
			})
		});
		
		/*
		function submit(fromId, id, reason){
			CNOA.msg.cf('执行“退上一步”操作后，不可恢复，您确定要继续吗？', function(btn) {
				if(btn == 'yes'){				
					Ext.Ajax.request({
					url: _this.baseUrl+"&task=toPrevious&fromId=" + fromId + "&id=" + id,
					method: 'GET',
					params: {'reason': reason},
					success: function(r){
						var sText = r.responseText;
						var result = Ext.decode(sText);
						CNOA.msg.alert(result.msg, function(){
							window.close();
							//win.close();
						});		
					},
					failure: function(response, opts) {
						CNOA.msg.alert(result.msg, function(){
							//
						});
					}
				});
				}
			});
		}
		*/
		function submit(fromId, id, reason){
			CNOA.msg.cf('执行“退上一步”操作后，不可恢复，您确定要继续吗？', function(btn) {
				if (btn == 'yes') {
					var f = frmSay.getForm();
					if(f.isValid()){
						f.submit({
							url: _this.baseUrl + "&task=toPrevious&fromId=" + fromId + "&id=" + id,
							//waitTitle: lang('notice'),
							method: 'POST',
							//waitMsg: lang('waiting'),
							params: {reason: reason},
							success: function(form, action){
								CNOA.msg.notice(action.result.msg, "公文管理");
									window.close();								
							}.createDelegate(this),
							
							failure: function(form, action) {
								CNOA.msg.alert(action.result.msg);
							}.createDelegate(this)
						});
					}
				}
			});
			
		};
		
		
		
		var frmSay = new Ext.form.FormPanel({
			border: false,			
			bodyStyle: "padding:20px;",
			labelWidth: 70,
			items: [
				{
					xtype: 'textarea',
					width: 380,
					height: 170,
					fieldLabel: '退上一步原因',
					name: 'reason',
					allowBlank: false
				}
			]
		});
		
		
	//选定人后，让他填写退上一步原因
		var winSay = new Ext.Window({
			title: "退上一步",
			modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 300,
			layout: "fit",
			items: frmSay,
			buttons:[
				{
					text: lang('ok'),
					handler: function(){
						//winSay.close();
						//submit(_this);
						//*
						var rows = grid.getSelectionModel().getSelections();
						if (rows.length == 0) {
							CNOA.msg.alert('请您先选择要删除的数据.');
							return;
						}
						var json	= rows[0].json;
						var id		= json.id;
						var fromId	= json.fromId;
						//alert(fromId + "," + id);
						
						
						submit(fromId, id);
						//*/
						
					}
				},{
					text: lang('close'),
					handler: function(){
						winSay.close();
					}
				}
			],
			listeners: {
				'show' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(false);
				},
				'close' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(true);
				}
			}
		});
		
		
		
		
		
		var win = new Ext.Window({
			title: "退上一步",
			modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 400,
			layout: "fit",
			items: [grid],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : lang('ok'),
					handler : function(button, event) {
						winSay.show();
						//winSay.show();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : lang('cancel'),
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			],
			listeners: {
				'show' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(false);
				},
				'close' : function(){
					CNOA_odoc_send_check_edit.setOfficeAcitveXDisplay(true);
				}
			}
		}).show();
	}
}

Ext.onReady(function() {
	if(!Ext.isIE){
		CNOA.msg.alert("此功能因使用Office控件，需要使用IE浏览器，请使用IE浏览器重新进入本功能。", function(){
		window.close();
		});
	}else{
		CNOA_odoc_send_check_edit = new CNOA_odoc_send_check_editClass();
		CNOA_odoc_send_check_edit.mainPanel.doLayout();
	}
});