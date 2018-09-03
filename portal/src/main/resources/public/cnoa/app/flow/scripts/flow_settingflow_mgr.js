/**
* 主面板-列表
*
*/
CNOA_flow_flow_settingflow_mgrClass = CNOA.Class.create();
CNOA_flow_flow_settingflow_mgrClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;
		this.sort = 0;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		this.baseUrl = "index.php?app=flow&func=flow&action=setting";
		
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		
		//查询
		this.ID_find_name		= Ext.id();
		this.ID_find_title		= Ext.id();
		this.ID_find_beginTime	= Ext.id();
		this.ID_find_endTime	= Ext.id(); 
		this.ID_find_status		= Ext.id();
		this.ID_find_buildUser	= Ext.id();
		this.ID_find_getUser	= Ext.id();
		
		//查询参数
		this.searchParams = {
			name: '',
			title: '',
			beginTime: '',
			endTime: '',
			status: 0,
			buildUser: ''
		};

		this.fields = [
			{name:"lid"},
			{name:"ulid"},
			{name:"uname"},
			{name:"name"},
			{name:"flowname"},
			{name:"title"},
			{name:"level"},
			{name:"step"},
			{name:"despanseStatus"},
			{name:"posttime"},
			{name:"status"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getAllFlowJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			//this.sm,
			{header: "ulid", dataIndex: 'ulid', hidden: true},
			{header: '流程编号', dataIndex: 'name', width: 180, sortable: true, menuDisabled :true},
			{header: '发起人', dataIndex: 'uname', width: 100, sortable: true, menuDisabled :true},
			{header: '所属流程', dataIndex: 'flowname', width: 120, sortable: true, menuDisabled :true},
			{header: lang('title'), dataIndex: 'title', width: 120, sortable: true, menuDisabled :true},
			{header: "重要等级", dataIndex: 'level', width: 60, sortable: false,menuDisabled :true},
			{header: "当前步骤", dataIndex: 'step', width: 70, sortable: false,menuDisabled :true},
			{header: "状态", dataIndex: 'status', width: 50, sortable: false,menuDisabled :true, renderer:this.makeStatus.createDelegate(this)},
			{header: "分发状态", dataIndex: 'despanseStatus', width: 70, sortable: false,menuDisabled :true},
			{header: "发起日期", dataIndex: 'posttime', width: 110, sortable: false,menuDisabled :true},
			{header: lang('opt'), dataIndex: 'ulid', width: 80, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			
			   
			store: this.store,
			style: 'border-left-width:1px;',
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					params.sort = _this.sort;
					
					//查询参数
					if(_this.searchParams.name != ''){
						params.name = _this.searchParams.name;
					}
					if(_this.searchParams.title != ''){
						params.title = _this.searchParams.title;
					}
					if(_this.searchParams.beginTime != ''){
						params.beginTime = _this.searchParams.beginTime;
					}
					if(_this.searchParams.endTime != ''){
						params.endTime = _this.searchParams.endTime;
					}
					if(_this.searchParams.status != 0){
						params.status = _this.searchParams.status;
					}
					if(_this.searchParams.buildUser != ''){
						params.buildUser = _this.searchParams.buildUser;
					}
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
			bodyStyle: 'border-left-width:1px;',
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				//forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : lang('refresh'),
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},'-',{
						handler : function(button, event) {
							_this.openExportExcelWindow();
						}.createDelegate(this),
						iconCls: 'icon-excel',
						tooltip: '导出流程列表为Excel文件',
						text : '导出'
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 11}
				]
			}),
			bbar: this.pagingBar
		});
		
		
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			text: "所有分类",
			sid: '0',
			iconCls: "icon-tree-root-cnoa",
			id: this.ID_tree_treeRoot,
			expanded: true//,
		});
		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl+"&task=getSortList&type=tree",
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
				"load" : function(node){
					_this.sortTree.selectPath(_this.ID_tree_treeRoot);
				}.createDelegate(this)
			}
		})
		this.sortTree = new Ext.tree.TreePanel({
			hideBorders: true,
			border: false,
			rootVisible: true,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: this.treeLoader,
			root: this.treeRoot,
			autoScroll: true,
			listeners:{
				"click":function(node){
					_this.sort = node.attributes.sid;
					_this.store.load({params:{sort: _this.sort}});
				}
			}
		});
		this.sortPanel = new Ext.Panel({
			region: 'west',
			layout:'fit',
			split: true,
			width: 120,
			minWidth: 80,
			maxWidth: 380,
			bodyStyle: 'border-right-width:1px;',
			items: [this.sortTree],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					"流程分类",
					"->",
					//刷新tree
					{
						handler : function(button, event) {
							_this.treeRoot.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					}
				]
			})
		});
		
		
	//查询工具条
		this.gridCt = new Ext.Panel({
			border: false,
			layout: 'fit',
			items: [this.grid],
			region: 'center',
			listeners: {
				afterrender: function(){
					new Ext.Toolbar({
						style: 'border-left-width:1px;',
						items: [
							'发起开始时间：',
							{
								xtype: "datefield",
								width: 130,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_beginTime
							},
							' 发起最后时间：',
							{
								xtype: "datefield",
								width: 130,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_endTime
							},
							{
								xtype: 'hidden',
								id: _this.ID_find_buildUser
							},
							'发起人',
							{
								xtype: "triggerForPeople",
								dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
								width: 130,
								id: _this.ID_find_getUser,								
								listeners: {
									"selected":function(th, uid, uname){
										Ext.getCmp(_this.ID_find_buildUser).setValue(uid);
									}
								}
							},
							'-',
							{
								text: lang('search'),
								handler: function(){
									_this.searchParams = {
										name: Ext.getCmp(_this.ID_find_name).getValue(),
										title: Ext.getCmp(_this.ID_find_title).getValue(),
										beginTime: Ext.getCmp(_this.ID_find_beginTime).getRawValue(),
										endTime: Ext.getCmp(_this.ID_find_endTime).getRawValue(),
										status: Ext.getCmp(_this.ID_find_status).getValue(),
										buildUser: Ext.getCmp(_this.ID_find_buildUser).getValue()
									};
									_this.store.load({params:_this.searchParams});
								}
							},'-',
							{
								text: lang('clear'),
								handler: function(){
									Ext.getCmp(_this.ID_find_name).setValue('');
									Ext.getCmp(_this.ID_find_title).setValue('');
									Ext.getCmp(_this.ID_find_beginTime).setRawValue('');
									Ext.getCmp(_this.ID_find_endTime).setRawValue('');
									Ext.getCmp(_this.ID_find_status).setValue(-99);
									Ext.getCmp(_this.ID_find_buildUser).setValue('');
									Ext.getCmp(_this.ID_find_getUser).setValue('');
									
									_this.searchParams = {
										name: '',
										title: '',
										beginTime: '',
										endTime: '',
										status: 0,
										buildUser: ''
									}
									_this.store.load();
								}
							}

						]
					}).render(this.tbar);
				}
			},
			tbar: new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [					
					'标题：',
					{
						xtype: 'textfield',
						width: 200,
						id: _this.ID_find_title
					},
					' 编号名称',
					{
						xtype: 'textfield',
						width: 150,
						id: _this.ID_find_name
					},
					'当前状态：',
					{
						xtype: 'combo',
						id: _this.ID_find_status,
						editable: false,
						mode: 'local',
						triggerAction: 'all',
						valueField: 'value',
						displayField: 'display',
						store: new Ext.data.ArrayStore({
							fields: ['value', 'display'],
							data: [[-99, ''], [-1, '已删除'], [0, '未发布'], [1, '办理中'], [2, '已办理'], [3, '已退件'], [4, '已撤销']]
						}),
						value: -99
					}
				]				
			})		
		});


		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.gridCt, this.sortPanel]
		});
	},
	
	openExportExcelWindow : function(){
		var _this = this;
		
		var submit = function(){
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: _this.baseUrl + "&task=exportExcel",
					waitMsg: lang('waiting'),
					params: {},
					method: 'POST',
					success: function(form, action) {
						win.close();
						_this.openDownloadExcelWindow(action.result.msg);
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
						});
					}
				});
			}
		}
		var ID_export_uid = Ext.id();
		var formPanel = new Ext.form.FormPanel({
			border: false,
			bodyStyle: "padding:10px;",
			waitMsgTarget: true,
			labelWidth: 110,
			items: [
				{
					xtype: 'hidden',
					name: 'uid',
					id: ID_export_uid
				},
				{
					xtype: "triggerForPeople",
					fieldLabel: "发起人",
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					width: 100,
					allowBlank: false,
					listeners: {
						"selected":function(th, uid, uname){
							Ext.getCmp(ID_export_uid).setValue(uid);
						}
					}
				},
				{
					xtype: "datefield",
					fieldLabel: "流程发起开始时间",
					width: 170,
					format: "Y-m-d",
					editable: false,
					allowBlank: false,
					name: "stime"
				},
				{
					xtype: "datefield",
					fieldLabel: "流程发起最后时间",
					width: 170,
					format: "Y-m-d",
					editable: false,
					allowBlank: false,
					name: "etime"
				}
			]
		});
		var win = new Ext.Window({
			width: 320,
			height: 170,
			title: "导出工作流程",
			resizable: false,
			modal: true,
			layout: "fit",
			items: formPanel,
			buttons : [
				//汇报
				{
					text : "导出",
					iconCls: 'icon-excel',
					handler : function() {
						submit();
					}.createDelegate(this)
				},
				//关闭
				{
					text : lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	openDownloadExcelWindow : function(code){
		//var fname = url.substring(url.lastIndexOf('/') + 1, url.length);
		var win = new Ext.Window({
			width: 320,
			height: 150,
			title: "下载EXCEL表格",
			resizable: false,
			modal: true,
			layout: "fit",
			bodyStyle: "padding:10px;background-color: #FFF;",
			html: "请点击下载：<br/>"+code,
			buttons : [
				//关闭
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>未发送</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>办理中</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>已办理</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>已退件</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_gray'>已撤销</span>";}
		return h;
	},

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;

		var h  = "<a href='javascript:void(0);' onclick='CNOA_flow_flow_settingflow_mgr.showFlow("+value+");'>查看</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_settingflow_mgr.deleteFlow("+value+");'>删除</a>";
		return h;
	},
	
	deleteFlow : function(ulid){
		var _this = this;
		
		CNOA.msg.cf("确认删除吗？", function(btn){
			if(btn == "yes"){
				Ext.Ajax.request({  
					url: _this.baseUrl + "&task=deleteflow",
					method: 'POST',
					params: { ulid: ulid },
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.notice(result.msg, "工作流管理");
							_this.store.reload();
						}else{
							CNOA.msg.alert(result.msg);
						}
					}
				});
			}
		});
	},
	
	//查看流程(操作)
	showFlow : function(ulid){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_VIEWFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=showflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_SHOWFLOW", "查看工作流程", "icon-flow");
	}
}

