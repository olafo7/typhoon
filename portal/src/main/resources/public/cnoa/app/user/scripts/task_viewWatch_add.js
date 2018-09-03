//定义全局变量：
var CNOA_user_task_viewWatch_addeditClass, CNOA_user_task_viewWatch_addedit;

/**
* 主面板-列表
*
*/
CNOA_user_task_viewWatch_addeditClass = CNOA.Class.create();
CNOA_user_task_viewWatch_addeditClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = CNOA.user.task_viewWatch.addedit.tid;
		
		this.loadCount = 0;

		this.baseUrl = "index.php?app=user&func=task&action=default";
		this.action  = "add";
		this.actionTitle = this.action == "add" ? lang('addTask') : lang('editTask');

		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_btn_examapp = Ext.id();
		this.ID_checkbox_needexamapp = Ext.id();
		this.ID_from_panel = Ext.id();
		this.ID_from_combobox = Ext.id();

		//添加短信组件
		this.smsSender = new Ext.form.SMSSender({
			from: "task",
			//modal: true,
			fieldLabel: lang('smsNotice')
		});

		this.baseField = [
			{
				xtype: "fieldset",
				title: lang('taskBaseInfo'),
				anchor: "99%",
				autoHeight: true,
				defaults: {
					//width: 325,
					xtype: "textfield"
				},
				items: [
					{
						name: "title",
						fieldLabel: lang('taskTitle'),
						inputType: "text",
						allowBlank: false,
						maxLength: 200,
						width: 480,
						maxLengthText: lang('maxLengthText') + 200
					},
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 3
						},
						items: [
							{
								xtype: "panel",
								border: false,
								layout: "form",
								items: [
									{
										xtype: "textfield",
										fieldLabel: lang('taskComeFrom'),
										name: "fromName",
										width: 480,
										readOnly: true,
										value: "[" + lang('parentTask') + ":]"+CNOA_user_task_viewWatch.title
									}
								]
							},
							{
								xtype: "hidden",
								name: "from",
								value: "mothertask"
							},
							{
								xtype: "hidden",
								name: "fromid",
								value: CNOA.user.task_viewWatch.addedit.tid
							}
						]
					},
					{
						xtype: "datefield",
						format: "Y-m-d",
						name: "stime",
						allowBlank: false,
						editable: false,
						fieldLabel: lang('addTime'),
						width: 150
					},
					{
						xtype: "textfield",
						name: "worktime1",
						fieldLabel: lang('workTimePG'),
						width: 150
					},
					{
						xtype: "datefield",
						format: "Y-m-d",
						name: "etime",
						allowBlank: false,
						editable: false,
						fieldLabel: lang('finishTime'),
						width: 150
					},
					{
						xtype: 'compositefield',
						fieldLabel: lang('approvalor'),
						items: [
							{
								xtype: 'userselectorfield',
		                        disabled: true,
		                        width: 250,
		                        name: 'examappUid',
		                        multiSelect: false
							},{
								xtype: 'checkbox',
								boxLabel: lang('xysprsp'),
								name: 'needexamapp',
								id: this.ID_checkbox_needexamapp,
								style: 'margin-left:5px;margin-top:3px;',
								listeners: {
									check: function(th, ck) {
										var examappField = _this.formPanel.getForm().findField('examappUid');
										if (ck) {
											if (_this.loadCount !== 0 || _this.action !== 'edit') {
												CNOA.msg.alert(lang('taskNeedSpNotice'));
											}

											examappField.allowBlank = false;
											examappField.validationEvent = true;
											examappField.clearInvalid();
											examappField.enable();
										} else {
											examappField.allowBlank = true;
											examappField.validationEvent = false;
											examappField.clearInvalid();
											
											examappField.disable();
											examappField.setValue('');
										}
									}
								}
							}
						]
					},
					{
						xtype: 'userselectorfield',
                        fieldLabel: lang('principal'),
                        width: 250,
                        allowBlank: false,
                        name: 'execmanUid',
                        multiSelect: false
					},
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 3
						},
						items: [
							{
								xtype: "panel",
								layout: "form",
								autoWidth: true,
								items: [
									{
										xtype: "textarea",
										fieldLabel: lang('participant'),
										emptyText: lang('pstp'),
										height: 35,
										width: 438,
										//allowBlank: false,
										readOnly: true,
										name: "participant"
									}
								]
							},
							{
								xtype: "hidden",
								name: "participantUids"
							},
							{
								xtype: "btnForPoepleSelector",
								text: lang('select'),
								dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
								style: "margin-left:6px",
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
										_this.formPanel.getForm().findField("participant").setValue(names.join(","));
										_this.formPanel.getForm().findField("participantUids").setValue(uids.join(","));
									},

									"onrender" : function(th){
										th.setSelectedUids(_this.formPanel.getForm().findField("participantUids").getValue().split(","));
									}
								}
							}
						]
					},
					{
						xtype: "textarea",
						fieldLabel: lang('taskContent'),
						width: 480,
						height: 140,
						name: "content",
						value: ""
					},
					{
						xtype: "flashfile",
						fieldLabel: lang('attach'),
						name: "files",
						inputPre: "filesUpload"
					},
					this.smsSender,
					{
						xtype: "textarea",
						fieldLabel: lang('jfbz'),
						width: 480,
						height: 80,
						name: "prizepunish",
						value: ""
					},
					{
						xtype: "textarea",
						fieldLabel: lang('leaderInstruction'),
						width: 480,
						height: 80,
						name: "leadersay",
						value: ""
					}
				]
			}
		];
		
		this.formPanel = new Ext.form.FormPanel({
			border: false,
			labelWidth: 90,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:5px 10px 10px 10px;",
			items: [this.baseField],
			listeners: {
				render : function(th){
					if(_this.action == "edit"){
						_this.loadFormData(th);
					}
				}
			}
		});

		this.addEditPanelTitle = new Ext.BoxComponent({
			autoEl: {
				tag: 'div',
				html: "<span style='font-weight:bold;margin-right:20px;'>" + lang('task2') + " - "+this.actionTitle+"</span>"
			}
		});

		this.addEditPanel = new Ext.Panel({
			hideBorders: true,
			autoScroll: true,
			border: false,
			items: [this.formPanel],
			buttons : [
				{
					text: lang('save'),
					id: this.ID_btn_save,
					iconCls: 'icon-btn-save',
					handler: function() {
						_this.submitForm();
						
					}.createDelegate(this)
				},
				//关闭
				{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function() {
						_this.closeWindow();
					}.createDelegate(this)
				}
			]
		});

		this.centerPanel = new Ext.Panel({
			region: "center",
			layout: "card",
			activeItem: 0,
			items: [this.addEditPanel]
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel]//, this.bottomPanel
		});
	},
	closeWindow : function(){
		Ext.getCmp(CNOA.user.task_viewWatch.addedit.parentID).close();
		//mainPanel.closeTab(CNOA.user.plan_task.addedit.parentID.replace("docs-", ""));
	},

	submitForm : function(){
		var _this = this;

		if (this.formPanel.getForm().isValid()) {
			this.formPanel.getForm().submit({
				url: _this.baseUrl,
				waitTitle: lang('notice'),
				method: 'POST',
				waitMsg: lang('waiting'),
				params: {task : _this.action, tid : _this.edit_id},
				success: function(form, action) {
					try{
						//提交手机短信
						_this.smsSender.submit();
					}catch(e){}

					CNOA.msg.alert(action.result.msg+"", function(btn){
						//刷新计划中的任务列表
						try{
							CNOA_user_task_viewWatch.refreshChildTaskList();
						}catch(e){}
						//关掉本标签
						_this.closeWindow();
					});
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						//this.mainPanel.enable();
					});
				}.createDelegate(this)
			});
		}
	}
}

/*
Ext.onReady(function() {
	CNOA_user_task_viewWatch_addedit = new CNOA_user_task_viewWatch_addeditClass();
	Ext.getCmp(CNOA.user.task_viewWatch.addedit.parentID).add(CNOA_user_task_viewWatch_addedit.mainPanel);
	Ext.getCmp(CNOA.user.task_viewWatch.addedit.parentID).doLayout();
});*/