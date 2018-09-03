var CNOA_main_system_loginlimit, CNOA_main_system_loginlimit_Class;
var CNOA_main_system_loginlimit_addEdit, CNOA_main_system_loginlimit_addEdit_Class;

CNOA_main_system_loginlimit_addEdit_Class = CNOA.Class.create();
CNOA_main_system_loginlimit_addEdit_Class.prototype = {
	init: function(tp, id){
		var _this = this;
		
		this.tp = tp;
		this.edit_id = id;
		
		this.baseUrl = "index.php?app=main&func=system&action=loginlimit";
		this.title = tp == "edit" ? "修改访问控制规则" : "添加访问控制规则";
		this.action = tp == "edit" ? "edit" : "add";
		
		this.ID_DEPT = Ext.id();
		this.ID_JOBS = Ext.id();
		this.ID_USER = Ext.id();
		this.ID_STAT = Ext.id();
		this.ID_TARGET = Ext.id();
		
		this.firstEditLoaded = false;
	
		//部门
		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl + "&task=getSelectorData&target=1",
			preloadChildren: true,
			clearOnLoad: false
		});
		
		this.dataStore = new Ext.data.SimpleStore({
			fields: ['name', 'id'],
			data: []
		});
		
		//职位
		this.jobComboBoxDataStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + "&task=getSelectorData&target=2"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name:"jobId", mapping: 'id'},
					{name:"value", mapping: 'name'}
				]
			})
		});
		this.jobComboBoxDataStore.load();
		
		//人员
		this.userDataUrl = this.baseUrl + "&task=getSelectorData&target=3";
		
		//岗位
		this.stationComboBoxDataStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + "&task=getSelectorData&target=4"
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
		
		this.formPanel = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			items: [
				{
					xtype: "panel",
					border: false,
					layout: "form",
					bodyStyle: "padding: 10px",
					defaults: {
						width: 400,
						allowBlank: false
					},
					items: [
						{
							xtype: 'radiogroup',
							fieldLabel: lang('sort'),
							name: "typeGroup",
							width: 180,
							items: [
								{boxLabel: lang('loginLimit'), name: 'type', inputValue: "1"},
								{boxLabel: lang('kqLimit'), name: 'type', inputValue: "2"}
							]
						},
						{
							xtype: 'textfield',
							fieldLabel: lang('rulesName'),
							name: 'name'
						},
						{
							xtype: 'textfield',
							fieldLabel: lang('startIp'),
							name: 'sip',
							regex: /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/,
							regexText: lang('ipAddrNotice')
						},
						{
							xtype: 'displayfield',
							hideLabel: true,style:'margin-left:75px',
							value: lang('startIpNotice')
						},
						{
							xtype: 'textfield',
							fieldLabel: lang('endIp'),
							name: 'eip',
							regex: /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/,
							regexText: lang('ipAddrNotice')
						},
						{
							xtype: 'displayfield',
							hideLabel: true,style:'margin-left:75px',
							value: lang('endIpNotice')
						},
						{
							xtype: 'radiogroup',
							fieldLabel: lang('rulesTarget'),
							name: "targetGroup",
							disabled: this.action == 'edit' ? true : false,
							width: 364,
							items: [
								{boxLabel: lang('department'), name: 'target', inputValue: "1", checked: true},
								{boxLabel: lang('job'), name: 'target', inputValue: "2"},
								{boxLabel: lang('people'), name: 'target', inputValue: "3"},
								{boxLabel: lang('station'), name: 'target', inputValue: "4"}
							],
							listeners:{
								"change":function(th, checked){
									if(this.action == 'edit' && !this.firstEditLoaded){
										this.firstEditLoaded = true;
									}else{
										this.dataStore.removeAll();
									}
									
									var d = Ext.getCmp(_this.ID_DEPT),
										j = Ext.getCmp(_this.ID_JOBS),
										u = Ext.getCmp(_this.ID_USER),
										s = Ext.getCmp(_this.ID_STAT);
									d.setVisible(false);
									j.setVisible(false);
									u.setVisible(false);
									s.setVisible(false);
									if(checked.inputValue == "1"){										
										d.setVisible(true);
									}
									if(checked.inputValue == "2"){										
										j.setVisible(true);
									}
									if(checked.inputValue == "3"){										
										u.setVisible(true);
									}
									if(checked.inputValue == "4"){										
										s.setVisible(true);
									}
								}.createDelegate(this)
							}
						},
						{
						    xtype: 'compositefield',
							allowBlank: true,
							fieldLabel: lang('select'),
						    items: [
						        {
									xtype: "triggerForDept",
									allowBlank: true,
									id: this.ID_DEPT,
									width: 60,hidden: false,
									loader: this.treeLoader,
									listeners:{
										"selected":function(th, node){
											this.addToTarget(node.attributes.selfid, node.attributes.text);
											th.setValue("");
										}.createDelegate(this)
									}
								},
						        new Ext.form.ComboBox({
									store:  this.jobComboBoxDataStore,
									id: this.ID_JOBS,
									valueField: 'jobId',
									displayField:'value',
									mode: 'local',
									width: 60,hidden: true,
									allowBlank: true,
									triggerAction:'all',
									forceSelection: true,
									editable: false,
									listeners:{
										select : function(th, record, index){
											this.addToTarget(th.getValue(), th.getRawValue());
											th.setValue("");
										}.createDelegate(this)
									}
								}),
								{
									xtype: "btnForPoepleSelector",
									text: lang('selectPeople'),
									allowBlank: true,
									id: this.ID_USER,hidden: true,
									dataUrl: this.userDataUrl,
									listeners: {
										"selected" : function(th, data){
											if (data.length>0){
												for (var i=0;i<data.length;i++){
													this.addToTarget(data[i].uid, data[i].uname);
												}
											}
										}.createDelegate(this)
									}
								},
								new Ext.form.ComboBox({
									fieldLabel: lang('station'),
									id: this.ID_STAT,hidden: true,
									store:  this.stationComboBoxDataStore,
									valueField: 'stationid',
									displayField:'name',
									mode: 'local',
									width: 60,
									allowBlank: true,
									triggerAction:'all',
									forceSelection: true,
									editable: false,
									listeners:{
										select : function(th, record, index){
											this.addToTarget(th.getValue(), th.getRawValue());
											th.setValue("");
										}.createDelegate(this)
									}
								}),
						        {
						            xtype: 'button',
						            text: lang('del'),
									handler: function(){
										var indexs = Ext.getCmp(_this.ID_TARGET).view.getSelectedIndexes();
										if (indexs.length == 0) {
											return '';
										}
										var rds = [];
										for(var i=indexs.length;i>=0;i--){
											rds.push(_this.dataStore.getAt(indexs[i]));
											
										}
										_this.dataStore.remove(rds);
									}
						        },
						        {
						            xtype: 'displayfield'
						        }
						    ]
						},
						{
							xtype: 'multiselect',
							fieldLabel: lang('targetList'),
							id: this.ID_TARGET,
							height: 160,
							allowBlank:false,
							store: this.dataStore,
							multiSelect: false,
							singleSelect: true,
							ddReorder: true,
							name: 'items',
							hiddenName: 'items',
							valueField: 'id',
							displayField: 'name'
						},
						{
							xtype: 'displayfield',
							hideLabel: true,style:'margin-left:75px',width: 420,
							value: lang('limitNotice1')
						},
						{
							xtype: 'displayfield',
							hideLabel: true,style:'margin-left:75px',width: 420,
							value: lang('limitNotice2')
						},
						{
							xtype: 'displayfield',
							hideLabel: true,style:'margin-left:75px',width: 420,
							value: lang('limitNotice3')
						}
					]
				}
			]
		});
		
		this.mainPanel = new Ext.Window({
			title: this.title + ' - ' + lang('yourIpIs') + CNOA.config.myIp,
			resizable: false,
			modal: true,
			width: 530,
			height: makeWindowHeight(510),
			items: [this.formPanel],
			buttonAlign: "right",
			layout: "fit",
			buttons : [
				//保存
				{
					text : lang('save'),
					id: this.ID_btn_save,
					iconCls: 'icon-btn-save',
					handler : function() {
						this.submitForm({close: true});
					}.createDelegate(this)
				},
				//关闭
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						this.close();
					}.createDelegate(this)
				}
			]
		});
		
		if(this.tp == "edit"){
			this.loadFormData();
		}
	},
	
	show : function(){
		this.mainPanel.show();
	},
	
	close : function(){
		this.mainPanel.close();
	},
	
	addToTarget : function(id, name){
		var items = this.dataStore.data.items;
		var repeat = false;
		for (var i=0;i<items.length ;i++ ){
			if(items[i].get('id') == id){
				repeat = true;
			}
		}
		if(repeat){
			CNOA.msg.notice2("<b>" + name + "</b> " + lang('haveAdded'));
			return ;
		}
		
		var rs = new Ext.data.Record({
			id: id,
			name: name
		});
		this.dataStore.add(rs);
	},
	
	submitForm : function(){
		var _this = this;
		
		var f = this.formPanel.getForm();
		
		var items = this.dataStore.data.items;
		var content = [];
		for (var i=0;i<items.length ;i++ ){
			content.push(items[i].get('id'));
		}
		f.findField("items").view.select([0]);

		if (this.formPanel.getForm().isValid()) {
			this.formPanel.getForm().submit({
				url: _this.baseUrl + "&task=submitForm",
				waitMsg: lang('waiting'),
				params: {id : _this.edit_id, content: Ext.encode(content)},
				method: 'POST',	
				success: function(form, action) {
					CNOA.msg.notice2(action.result.msg);
					_this.mainPanel.close();
					CNOA_main_system_loginlimit.store.reload();
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}.createDelegate(this)
			});
		}
	},
	
	loadFormData : function(){
		var _this = this;

		this.formPanel.getForm().load({
			url: this.baseUrl + "&task=editLoadFormData",
			params: {id: _this.edit_id},
			method:'POST',
			success: function(form, action){
				var items = action.result.data.items;
				for (var i=0;i<items.length ;i++ ){
					var rs = new Ext.data.Record({
						id: items[i].id,
						name: items[i].name
					});
					_this.dataStore.add(rs);
				}
				_this.formPanel.getForm().findField("items").view.select([0]);
			}.createDelegate(this),
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg, function(){
					_this.mainPanel.close();
				});
			}.createDelegate(this)
		});
	}
}

//主面板
CNOA_main_system_loginlimit_Class = CNOA.Class.create();
CNOA_main_system_loginlimit_Class.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = "index.php?app=main&func=system&action=loginlimit";
		this.ID_btn_save = Ext.id();
		
		this.type = 1;
		
		this.fields = [
			{name:"id"},
			{name:"name"},
			{name:"sip"},
			{name:"eip"},
			{name:"target"},
			{name:"inuse"},
			{name:"content"},
			{name:"uid"},
			{name:"posttime"}
		];

		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + '&task=getList'}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});

		this.store.load({params:{type:1}});

		this.colModel = new Ext.grid.ColumnModel({
			columns : [
				new Ext.grid.RowNumberer(),
				{header: lang('rulesName'), dataIndex: 'name', width: 120, sortable: true},
				{header: lang('rulesTarget'), dataIndex: 'target', width: 70, sortable: true,renderer:this.mkTarget.createDelegate(this)},
				{header: lang('rulesContent'), dataIndex: 'content', id: 'content', width: 50, sortable: true,renderer:this.mkContent.createDelegate(this)},
				{header: lang('isEnable'), dataIndex: 'inuse', width: 90, sortable: true,renderer:this.mkInUse.createDelegate(this)},
				{header: lang('opt'), dataIndex: 'id', width: 120, sortable: true,renderer:this.mkOpt.createDelegate(this)},
				{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
			]
		});
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			region: "center",
			autoExpandColumn: 'content',
			tbar: new Ext.Toolbar({
				style: "border-top-width:1px;",
				items: [
					{
						text: lang('loginLimitRules'),
						allowDepress: false,
						pressed: true,
						toggleGroup: "ruleType",
						toggleHandler: function(btn, state){
							_this.type = state ? 1 : 2;
							_this.store.load({params:{type:state?1:2}});
						}
					},
					{
						text: lang('kqLimitRules'),
						allowDepress: false,
						toggleGroup: "ruleType"
					},"-",
					{
						id: this.ID_btn_refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					},'-',
					{
						id: this.ID_btn_add,
						handler : function(button, event) {
							CNOA_main_system_loginlimit_addEdit = new CNOA_main_system_loginlimit_addEdit_Class("add", 0);
							CNOA_main_system_loginlimit_addEdit.show();
						}.createDelegate(this),
						iconCls: 'icon-utils-s-add',
						text : lang('add')
					}
				]
			}),
			listeners:{
				rowdblclick : function(grid, row){
					this.editInfoPanel();
				}.createDelegate(this)
			}
		});

		this.notice = new Ext.form.FormPanel({
			region: "north",
			border: false,
			height: 65,
			padding: 10,
			labelWidth: 60,
			items: [
				{
					xtype: 'radiogroup',
					fieldLabel: lang('visitControl'),
					name: "login_limitGroup",
					width: 480,
					items: [
						{boxLabel: lang('noUse'), name: 'login_limit', inputValue: "0", checked: true},
						{boxLabel: lang('enableLoginLimit'), name: 'login_limit', inputValue: "1"},
						{boxLabel: lang('enableKQlimig'), name: 'login_limit', inputValue: "2"},
						{boxLabel: lang('enableLoginKQlimit'), name: 'login_limit', inputValue: "3"}
					],
					listeners:{
						"change":function(th, checked){
							_this.setLoginLimit(checked.inputValue);
						}
					}
				}
			],
			tbar: [
				lang('sysLoginLimit') + " - " + lang('templates.login') + "/" + lang('attendance2')
			]
		});

		this.mainPanel = new Ext.Panel({
			layout: "border",
			border: false,
			items: [this.grid, this.notice]
		});
		
		this.notice.getForm().load({
			url: this.baseUrl + "&task=getlSetting",
			method:'POST'
		});
	},
	
	mkTarget : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var h;
		switch(value){
			case "1":
				h = lang('department');
				break;
			case "2":
				h = lang('job');
				break;
			case "3":
				h = lang('people');
				break;
			case "4":
				h = lang('station');
				break;
		}
		
		return h;
	},
	
	mkContent : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var h = "";
		
		h += rd.sip;
		h += " - ";
		h += rd.eip;
		h += "<br />";
		h += value;
		
		return h;
	},
	
	mkInUse : function(value){
		return value == 1 ? "<span style='color:green'>" + lang('enabled') + "</span>" : "<span style='color:gray'>" + lang('disabled') + "</span>";
	},
	
	mkOpt : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var h = "";
		
		if(rd.inuse == 1){
			h += "<a href='javascript:void(0);' onclick='CNOA_main_system_loginlimit.changeInUse("+value+", 0);'>" + lang('disable') + "</a>&nbsp;&nbsp;&nbsp;";
		}else{
			h += "<a href='javascript:void(0);' onclick='CNOA_main_system_loginlimit.changeInUse("+value+", 1);'>" + lang('enable') + "</a>&nbsp;&nbsp;&nbsp;";
		}
	  	
		h += "<a href='javascript:void(0);' onclick='CNOA_main_system_loginlimit.showEdit("+value+");'>" + lang('edit') + "</a>&nbsp;&nbsp;&nbsp;";
		h += "<a href='javascript:void(0);' onclick='CNOA_main_system_loginlimit.delList("+value+");'>" + lang('del') + "</a>";
		
		return h;
	},
	
	setLoginLimit : function(value){
		var _this = this;
		
		Ext.Ajax.request({  
			url: this.baseUrl + "&task=setLoginLimit",
			method: 'POST',
			params: { login_limit: value },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(result.msg, lang('sysLoginLimit'));
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	},
	
	changeInUse : function(id, inuse){
		var _this = this;
		
		Ext.Ajax.request({
			url: _this.baseUrl + "&task=changeInUse",
			method: 'POST',
			params: { id: id, inuse: inuse },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(result.msg, lang('sysLoginLimit'));
					_this.store.reload();
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	},
	
	showEdit : function(id){
		CNOA_main_system_loginlimit_addEdit = new CNOA_main_system_loginlimit_addEdit_Class("edit", id);
		CNOA_main_system_loginlimit_addEdit.show();
	},
	
	delList : function(id){
		var _this = this;
		
		CNOA.msg.cf(lang('areYouDelete'), function(btn){
			if(btn == 'yes'){
				Ext.Ajax.request({
					url: _this.baseUrl + "&task=delete",
					method: 'POST',
					params: { id: id},
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.notice(result.msg, lang('sysLoginLimit'));
							_this.store.reload();
						}else{
							CNOA.msg.alert(result.msg);
						}
					}
				});
			}
		});
	}
}

