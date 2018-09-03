var CNOA_odoc_setting_editTemplate, CNOA_odoc_setting_editTemplateClass;

window.focus();

CNOA_odoc_setting_editTemplateClass = CNOA.Class.create();
CNOA_odoc_setting_editTemplateClass.prototype = {
	init: function(){
		var _this = this;
		
		this.id = CNOA.odoc.templateid;
		
		this.ID_textarea_deptNames	 = Ext.id();
		this.ID_button_deptNames	 = Ext.id();
		
		this.baseUrl = "index.php?app=odoc&func=setting&action=template";
		this.baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);

		

		this.typeListStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl+"&task=getTypeList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name : "tid"},
					{name : "title"}
				]
			})
		});
		
		this.typeListStore.load();
		
		this.baseField = [
			{
				xtype: "fieldset",
				title: "基本设置",
				anchor: "97%",
				autoHeight: true,
				items: [
					{
						xtype: 'radiogroup',
						labelWidth: 1,
						width: 180,
						style : "margin-left : 130px",
						hideLabel: true,
						items:[
							{
								boxLabel: '发文',
								name: 'fromType',
								inputValue: 1,
								checked : true
							},{
								boxLabel: '收文',
								name: 'fromType',
								inputValue: 2
							}
						]
					},
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
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
										width : 200,
										fieldLabel : "模板名称",
										allowBlank: false,
										name: 'title'
									}
								]
							},
							{
								xtype : "panel",
								layout : "form",
								items : [
									new Ext.form.ComboBox({
										name: 'title',
										store: _this.typeListStore,
										width: 200,
										fieldLabel : "所属类型",
										hiddenName: 'tid',
										valueField: 'tid',
										displayField: 'title',
										mode: 'local',
										triggerAction: 'all',
										forceSelection: true,
										allowBlank: false,
										editable: false
									})
								]
							}
						]
					},
					/*{
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
										width : 200,
										fieldLabel : "默认文号前缀",
										allowBlank: false,
										name: 'num1'
									}
								]
							},
							{
								xtype : "panel",
								layout : "form",
								items : [
									{
										xtype : "textfield",
										width : 200,
										fieldLabel : "默认文号后缀",
										allowBlank: false,
										name: 'num2'
									}
								]
							}
						]
					},
					{
						xtype: 'hidden',
						name: "deptIds"
					},
					{
						xtype: 'textarea',
						width:525,
						height: 50,
						fieldLabel:"使用权限",
						style: "margin-top:5px;",
						id: this.ID_textarea_deptNames,
						name: "deptNames",
						readOnly: true
					},
					{
						xtype: "deptMultipleSelector",
						style: "margin-left : 125px;margin-bottom : 10px",
						autoWidth: true,
						id: this.ID_button_deptNames,
						deptListUrl : _this.baseUrl + "&task=getStructTree",
						listeners:{
							"selected" : function(th, textString, idString){
								_this.formPanel.getForm().findField("deptNames").setValue(textString);
								_this.formPanel.getForm().findField("deptIds").setValue(idString);
							},
							"load" : function(th){
								th.setSelectedIds(_this.formPanel.getForm().findField("deptIds").getValue());
							}
						}
					},*/
					{
						xtype : "textarea",
						width : 525,
						fieldLabel : "描述",
						name: 'about'
					},
					{
						xtype : "displayfield",
						width : 525,
						fieldLabel : "红头模板",
						name: 'template'
					}/*,
					{
						xtype : "fileuploadfield",
						width : 525,
						allowBlank: false,
						blankText: lang('updateFaceBlankText'),
						buttonCfg: {
							text: "浏览文件"
						},
						fieldLabel : "红头模板",
						name: 'msOffice'
					}*/
				]
			},
			{
				xtype: "fieldset",
				title: "发文单",
				anchor: "97%",
				height : 600,
				tbar: [
					"插入： ",
					{
						xtype : "button",
						iconCls: "icon-form-textfield",
						text: '单行文本框',
						cls: "x-btn-over",style: 'margin-right:2px;',
						listeners: {
							"mouseout" : function(btn){
								btn.addClass("x-btn-over");
							}
						},
						handler: function(){
							_this.CKExecuteCmd('textfield');
						}
					},
					{
						xtype : "button",
						iconCls: "icon-form-textarea",
						text: '多行文本框',
						cls: "x-btn-over",style: 'margin-right:2px;',
						listeners: {
							"mouseout" : function(btn){
								btn.addClass("x-btn-over");
							}
						},
						handler: function(){
							_this.CKExecuteCmd('textarea');
						}
					},
					{
						xtype : "button",
						iconCls: "icon-form-combobox",
						text: '下拉列表框',
						cls: "x-btn-over",style: 'margin-right:2px;',
						listeners: {
							"mouseout" : function(btn){
								btn.addClass("x-btn-over");
							}
						},
						handler: function(){
							_this.CKExecuteCmd('select');
						}
					},
					{
						xtype : "button",
						iconCls: "icon-form-combobox",
						text: '宏控件',
						cls: "x-btn-over",style: 'margin-right:2px;',
						listeners: {
							"mouseout" : function(btn){
								btn.addClass("x-btn-over");
							}
						},
						handler: function(){
							_this.CKExecuteCmd('odoc');
						}
					},"->",
					{
						xtype : "button",
						iconCls: "arrow_inout",
						text: '全屏编辑',
						cls: "x-btn-over",style: 'margin-right:2px;',
						listeners: {
							"mouseout" : function(btn){
								btn.addClass("x-btn-over");
							}
						},
						handler: function(){
							_this.CKFullScreen();
						}
					}
				],
				items: [
					{
						xtype : "panel",
						border : false,
						items : [
							{
				                xtype: 'ckeditor',
				                fieldLabel: 'Editor',
				                name: 'htmlcode',
								hideLabel: true,
								height : 563,
								//value: Ext.getDom("FORM_CONTENT_2").value,
								id: "fawenform",
								name: "fawenform",
								CKConfig: {
									skin : 'v2',
									toolbarCanCollapse: false,
									resize_enabled: false,
									toolbar : [
										['Source'],['-','Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
										['Cut','Copy','Paste','PasteText','PasteFromWord'],
										['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],['Maximize'],
										'/',
										['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
										['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
										['BidiLtr', 'BidiRtl' ],
										['Link','Unlink'],
										['Image','Table','HorizontalRule','SpecialChar','PageBreak'],
										['TextField', 'Textarea', 'Select', 'Odoc'],
										'/',
										['Styles','Format','Font','FontSize'],
										['TextColor','BGColor'],
										['ShowBlocks', "Pager"]
										//,['TextField', 'Textarea', 'Select', 'Macro']
									]
									,extraPlugins : "pager,odoc"//,tableresize
								},
								listeners: {
									afterrender : function(){
										//fckLoaded
										try {
											parent.CNOA_flow_flow_settingform.fckLoaded();
										} catch (e) {}
									}
								}
				            }	
						]
					}			
				]
			}
		];
		
		/*
		this.tplPanel = new Ext.Panel({
			title: "红头模板",
			//bodyStyle: "border:1px solid red;"
			html: this.webofficeHtml,
			listeners: {
				activate : function(th){
					th.doLayout();
					_this.tabPanel.doLayout();
				}
			}
		});
		
		
		this.tabPanel = new Ext.TabPanel({
			activeTab: 0,
			minTabWidth: 185,
			//deferredRender: false,
			region : "center",
			border: false,
			items: [this.baseField, this.tplPanel]
		});
		*/
		
		this.formPanel = new Ext.form.FormPanel({
			title: '编辑公文模板',
			border: false,
			labelWidth: 120,
			labelAlign: 'right',
			region : "center",
			//fileUpload: true,
			autoScroll : true,
			waitMsgTarget: true,
			bodyStyle: "padding:10px",
			items: this.baseField,
			tbar: [
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler : function(){
						_this.submitForm();
					}
				},"->",
				//关闭
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						window.close();
					}.createDelegate(this)
				}
			]
		});
		
		this.mainPanel = new Ext.Viewport({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'border',
			autoScroll: false,
			items: [this.formPanel]
		});
		
		_this.loadFormData();
	},
	
	loadTemplate : function(){
		try{
			var webObj = document.getElementById("CNOA_WEBOFFICE");
			webObj.OptionFlag |= 128;
			webObj.LoadOriginalFile(this.baseURI + this.baseUrl + "&task=loadTemplate&id="+this.id, "doc");
			webObj.ShowToolBar = false;
		}catch(e){}
	},
	
	loadFormData : function(){
		var _this = this;
		
		this.formPanel.getForm().load({
			url: this.baseUrl + "&task=editLoadFormData",
			params: {id: _this.id},
			method:'POST',
			waitMsg: lang('waiting'),
			success: function(form, action){
				//action.result.data
			}.createDelegate(this),
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg, function(){
					_this.close();
				});
			}.createDelegate(this)
		});
	},
	
	submitForm : function() {
		//this.formPanel.getForm().findField('content').syncValue(); 	
		if (this.formPanel.getForm().isValid()) {
			this.formPanel.getForm().submit({
				url: this.baseUrl + "&task=submit",
				waitMsg: lang('waiting'),
				params: {id : this.id},
				method: 'POST',	
				success: function(form, action) {
					//if(config.close){
					//	this.close();
					//}else{
					//	
					//}
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}.createDelegate(this)
			});
		}else{
			CNOA.msg.alert("表单未完成，请检查");
			return;
		}
	},

	CKFullScreen : function () {
		var oEditor = CKEDITOR.instances['fawenform'];

		oEditor.execCommand('maximize');
	},

	CKExecuteCmd : function (commandName) {
		var oEditor = CKEDITOR.instances['fawenform'];

		if (oEditor.mode == 'wysiwyg'){
			// Execute the command.
			// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#execCommand
			oEditor.execCommand(commandName);

		}else{
			CNOA.msg.alert("请先切换到设计模式界面");
		}
	},
	
	reloadDocTpl : function(){
		var _this = this;
		
		Ext.Ajax.request({  
			url: this.baseUrl + "&task=editLoadFormData",
			method: 'POST',
			params: {id: _this.id},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				_this.formPanel.getForm().findField("template").setValue(result.data.template);
			}.createDelegate(this)
		});
	}
}


Ext.onReady(function(){
	
	CNOA_odoc_setting_editTemplate = new CNOA_odoc_setting_editTemplateClass();
	CNOA_odoc_setting_editTemplate.mainPanel.doLayout();
});