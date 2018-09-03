//停用 -> 已经不用了

// 修改用户档案资料
CNOA_main_user_baseInfoClass = CNOA.Class.create();
CNOA_main_user_baseInfoClass.prototype = {
	init : function(tp){
		this.tp = tp;
		this.falceUrl = "";
		this.baseUrl = "index.php?app=main&func=user&action=";
		//this.actionUrl = tp == "edit" ? "index.php?app=main&func=user&action=edit" : "index.php?app=main&func=user&action=add";
		this.title = tp == "edit" ? lang('modify')+lang('user') : lang('add')+lang('user');
		this.action = tp == "edit" ? "edit" : "add";
		this.edit_uid = 0;
		
		/**
		 * 同级部门的职位comboBox
		 *
		 */
		this.jobComboBoxDataStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: null//"index.php?app=main&func=struct&action=list&task=getPartTimeJobBrotherList&fid=0"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name:"jobId", mapping: 'id'},
					{name:"value", mapping: 'name'}
				]
			})
		});
		this.jobComboBox = new Ext.form.ComboBox({
			fieldLabel: lang('atJob'),
			name: 'jobId',
			store:  this.jobComboBoxDataStore,
			hiddenName: 'jobId',
			valueField: 'jobId',
			displayField:'value',
			mode: 'local',
			width: 160,
			allowBlank: false,
			triggerAction:'all',
			forceSelection: true,
			editable: false,
			listeners:{
				select : function(th, record, index){
					this.Panel_base_form.getForm().findField("jobId").setValue(record.data.jobId);
				}.createDelegate(this)
			}
		});
		//岗位
		this.stationComboBoxDataStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: "index.php?app=main&func=user&action=list&task=getStationList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name:"stationid"},
					{name:"name"}
				]
			})
		});
		this.stationComboBoxDataStore.load();
		this.stationComboBox = new Ext.form.ComboBox({
			fieldLabel: lang('atStation'),
			name: 'stationid',
			store:  this.stationComboBoxDataStore,
			hiddenName: 'stationid',
			valueField: 'stationid',
			displayField:'name',
			mode: 'local',
			width: 160,
			allowBlank: false,
			triggerAction:'all',
			forceSelection: true,
			editable: false,
			listeners:{
				select : function(th, record, index){
					
				}.createDelegate(this),
				
				change : function(){
					
				}.createDelegate(this)
			}
		});
		// userAddWindow->userAddTabPanel->userAddPanel->userAddFormPanel
		this.Panel_base_form = new Ext.form.FormPanel({
			frame: false,
			autoHeight: true,
			width: 660,
			border: false,
			bodyStyle: "padding:10px 10px 10px 20px;",
			labelWidth: 95,
			labelAlign: 'right',
			hideBorders: true,
			waitMsgTarget: true,
			items: [{
				layout: 'column',
				hideBorders: true,
				border: false,
				items: [
					{
						columnWidth: .75,
						items: this.CNOA_main_user_baseInfo_getFormFields(this.baseUrl + this.action)
					},
					{
						columnWidth: .25,
						bodyStyle: 'text-align:center;margin-top:7px;',
						hideBorders: true,border: false,
						items: [
							{
								html: '<div style="border:1px solid #CCC;width:120px;height:160px;padding:3px;display: table-cell;vertical-align: middle;"><img src="./resources/images/empty_photo.png" id="CNOA_MAIN_USER_FACEBOX"></div>'
							},
							{
								xtype: "button",
								width: 128,
								style: "margin-top: 3px;",
								text: this.tp=="edit"?lang('change')+lang('photo'):lang('add')+lang('photo'),
								handler: function(){
									this.showUpdateFaceDialog();
								}.createDelegate(this)
							},
							{
								xtype: "hidden",
								name: "faceUrl"
							}
						]
					}
					
				]
			}]
		});

		this.Panel_base = new Ext.Panel({
			border: false,
			hideBorders: true,
			height: 440,
			items: [this.Panel_base_form],
			buttonAlign: "right",
			autoScroll: true,
			bodyStyle: 'border-bottom-width:1px;',
			buttons: [
			//保存
			{
				text: lang('save'),
				iconCls: 'icon-btn-save',
				handler: function() {
					this.submitForm({close: true});
				}.createDelegate(this)
			},
			//应用
			{
				text : lang('apply'),
				iconCls: 'icon-order-s-accept',
				hidden: this.action=='edit'?false:true,
				handler : function() {
					this.submitForm({close: false});
				}.createDelegate(this)
			},
			//关闭
			{
				text: lang('close'),
				iconCls: 'icon-dialog-cancel',
				handler: function() {
					this.close();
				}.createDelegate(this)
			}]
		});

		this.tabPanel = new Ext.TabPanel({
			activeTab: 0,
			border: false,
			minTabWidth: 185,
			height: 467,
			items: [{
				title: lang('baseInfo'),
				items: [this.Panel_base]
			}]
		});

		this.mainPanel = new Ext.Window({
			width: 700,
			height: makeWindowHeight(500),
			resizable: false,
			modal: true,
			items: [this.tabPanel],
			title: this.title
		});
	},

	show : function(){
		this.mainPanel.show();
	},
	
	close : function(){
		this.mainPanel.close();
	},

	showUpdateFaceDialog: function(){

		this.FACE_WINDOW_FORMPANEL = new Ext.FormPanel({
			fileUpload: true,
			autoHeight: true,
			autoScroll: false,
			waitMsgTarget: true,
			hideBorders: true,
			border: false,
			bodyStyle: "padding: 5px;",
			buttonAlign: "right",
			items: [
				{
					xtype: 'fileuploadfield',
					name: 'face',
					allowBlank: false,
					blankText: lang('updateFaceBlankText'),
					buttonCfg: {
						text: lang('browse')
					},
					hideLabel : true,
					width: 370,
					listeners: {
						'fileselected': function(fb, v){
							
						}
					}
				}
			],
			buttons: [
				{
					text: lang('save'),
					handler: function(){
						if (this.FACE_WINDOW_FORMPANEL.getForm().isValid()) {
							this.FACE_WINDOW_FORMPANEL.getForm().submit({
								url: this.baseUrl + this.tp,
								waitMsg: lang('waiting'),
								params: {task : "upFace", uid : this.edit_uid},
								success: function(form, action) {
									this.Panel_base_form.getForm().findField("faceUrl").setValue(action.result.msg);
									Ext.fly("CNOA_MAIN_USER_FACEBOX").dom.src = action.result.msg;
									this.FACE_WINDOW.close();
								}.createDelegate(this),
								failure: function(form, action) {
									CNOA.msg.alert(action.result.msg, function(){
										this.FACE_WINDOW.close();
									});
								}
							});
						}
					}.createDelegate(this)
				},{
					text: lang('close'),
					handler: function(){
						this.FACE_WINDOW.close();
					}.createDelegate(this)
				}
			]
		});

		this.FACE_WINDOW = new Ext.Window({
			width: 398,
			height: 103,autoScroll: false,
			modal: true,resizable: false,
			title: lang('upload') + lang('photo'),
			items: [
				this.FACE_WINDOW_FORMPANEL
			]
		}).show();
	},

	loadFormData : function(uid){
		var _this = this;
		this.Panel_base_form.getForm().load({
			url: 'index.php?app=main&func=user&action=edit',
			params: { uid: uid, task: 'loadDataBaseInfo' },
			method:'POST',
			waitMsg:'Loading',
			success: function(form, action){
				var loadTime = 0;
				this.edit_uid = uid;

				if(action.result.data.face != ""){
					Ext.fly("CNOA_MAIN_USER_FACEBOX").dom.src = action.result.data.face;
				}

				this.reloadJobComboBoxDataStore(action.result.data.deptId);
				this.jobComboBoxDataStore.on("load", function(){
					//如果是初次载入
					if(loadTime == 0){
						this.jobComboBox.setValue(action.result.data.jobId);
						loadTime++;
					}
				}.createDelegate(this));
			}.createDelegate(this),
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg, function(){
					_this.close();
				});
			}
		})
	},

	submitForm : function(config){
		if (this.Panel_base_form.getForm().isValid()) {
			this.mainPanel.disable();
			this.Panel_base_form.getForm().submit({
				url: this.baseUrl + this.tp,
				waitTitle: lang('notice'),
				method: 'POST',
				waitMsg: lang('notice'),
				params: {task : this.action + "BaseInfo", uid : this.edit_uid},
				success: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						CNOA_main_user_baseInfo_edit.mainPanel.enable();
						CNOA_main_user_list.store.reload();
						if (config.close){
							CNOA_main_user_baseInfo_edit.close();
						}
					});
				},
				failure: function(form, action) {
					CNOA_main_user_baseInfo_edit.mainPanel.enable();
					CNOA.msg.alert(action.result.msg);
				}
			});
		}
	},

	//异步动态载入位置combobox数据
	reloadJobComboBoxDataStore : function(fid){
		this.jobComboBox.clearValue();
		this.jobComboBoxDataStore.removeAll();
		this.jobComboBoxDataStore.proxy = new Ext.data.HttpProxy({
			url: "index.php?app=main&func=user&action="+this.action+"&task=getJobListByDeptId&deptId=" + fid
		});
		this.jobComboBoxDataStore.load();
	},

	CNOA_main_user_baseInfo_getFormFields : function (url){
		var field = [
			{
				xtype: "fieldset",
				title: lang('baseInfo'),
				width: 422,
				defaults: {
					width: 300,
					xtype: "textfield"
				},
				items: [
					{
						name: "truename",
						fieldLabel: lang('truename'),
						inputType: "text",
						allowBlank: false,
						maxLength: 10,
						maxLengthText: lang('maxLengthText') + 10
					},
					{
						name: "bianhao",
						fieldLabel: lang('yuanGongBianHao'),
						inputType: "text",
						maxLength: 20,
						maxLengthText: lang('maxLengthText') + 20
					},
					new Ext.form.ComboBox({
						fieldLabel: lang('sex'),
						name: 'sex',
						store: new Ext.data.SimpleStore({
							fields: ['value', 'sex'],
							data: [['1', lang('male')], ['2', lang('female')]]
						}),
						hiddenName: 'sex',
						valueField: 'value',
						displayField: 'sex',
						mode: 'local',
						triggerAction: 'all',
						forceSelection: true,
						editable: false,
						value: lang('male')
					}),
					{
						name: "mobile",
						fieldLabel: lang('mobile'),
						inputType: "text",
						maxLength: 15,
						maxLengthText: lang('maxLengthText') + 15
					},
					{
						name: "workphone",
						fieldLabel: lang('workPhone'),
						inputType: "text",
						maxLength: 15,
						maxLengthText: lang('maxLengthText') + 15
					},
					{
						name: "phone",
						fieldLabel: lang('lifePhone'),
						inputType: "text",
						maxLength: 15,
						maxLengthText: lang('maxLengthText') + 15
					},
					{
						vtype: "email",
						name: "email",
						fieldLabel: lang('email'),
						inputType: "text",
						maxLength: 40,
						maxLengthText: lang('maxLengthText') + 40
					},
					{
						xtype: 'datefield',
						name: 'birthday',
						width: 140,
						fieldLabel: lang('birthday'),
						format: 'Y-m-d',
						maxLength: 10,
						maxLengthText: lang('maxLengthText') + 10
					},
					{
						xtype: "hidden",
						name: "deptId"
					},
					{
						xtype: "triggerForDept",
						fieldLabel: lang('inDepartment'),
						name: "deptName",
						//deptListUrl : "tree.txt",
						loader: CNOA_main_user_list.treeLoader,
						listeners:{
							"selected":function(th, node){
								this.Panel_base_form.getForm().findField("deptId").setValue(node.attributes.selfid);
								this.reloadJobComboBoxDataStore(node.attributes.selfid);
							}.createDelegate(this)
						}
					},
					this.jobComboBox,
					this.stationComboBox,
					{
						xtype: 'datefield',
						name: 'ruzhishijian',
						width: 140,
						fieldLabel: lang('hiredate'),
						maxLength: 10,
						format: 'Y-m-d',
						maxLengthText: lang('maxLengthText') + 10
					},
					{
						name: 'address',
						fieldLabel: lang('nowAddress'),
						maxLength: 60,
						maxLengthText: lang('maxLengthText') + 60
					},
					{
						name: 'idcard',
						vtype: 'idCard',
						vtypeText: lang('idCardHaoMaWrong'),
						fieldLabel: lang('idCardHaoMa'),
						maxLength: 20,
						maxLengthText: lang('maxLengthText') + 20
					}
				]
			},
			{
				xtype: "fieldset",
				title: lang('extendInfo'),
				width: 422,
				defaults: {
					width: 300,
					xtype: "textfield"
				},
				items: new CNOA_main_user_baseInfo_getFormExtFields()
			}
			

		];
		return field;
	}
}



