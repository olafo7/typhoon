var CNOA_flow_table_listClass, CNOA_flow_table_list;

/**
* 主面板-列表
*
*/
CNOA_flow_table_listClass = CNOA.Class.create();
CNOA_flow_table_listClass.prototype = {
	init : function(){
		var _this = this;
		
		this.edit_id = 0;
		this.lid = CNOA.flow.table.list.lid;
		
		this.baseUrl = "index.php?app=flow&func=table&action=list&lid="+this.lid;

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		//查询
		this.ID_find_name		= Ext.id();
		this.ID_find_title		= Ext.id();
		this.ID_find_beginTime	= Ext.id();
		this.ID_find_endTime	= Ext.id(); 

		//查询参数
		this.searchParams = {
			name: '',
			title: '',
			beginTime: '',
			endTime: '',
			status: 0
		};
		
		this.fields = [
			{name:"lid"},
			{name:"ulid"},
			{name:"name"},
			{name:"flowname"},
			{name:"title"},
			{name:"level"},
			{name:"step"},
			{name:"posttime"},
			{name:"status"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getMyFlowJsonData", disableCaching: true}),   
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
			{header: '流程编号 / 标题', dataIndex: 'name', width: 500, sortable: true, menuDisabled :true, renderer:this.makeTitle.createDelegate(this)},
			{header: "发起日期", dataIndex: 'posttime', width: 110, sortable: false,menuDisabled :true},
			{header: lang('opt'), dataIndex: 'ulid', width: 160, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			
			   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
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
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
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
				items: [
					{
						iconCls: 'icon-flow-new',
						text : "填写表单",
						handler : function(button, event) {
							_this.newFlow();
						}.createDelegate(this)
					},'-',
					{
						iconCls: 'icon-system-refresh',
						text : lang('refresh'),
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 18}
				]
			}),
			bbar: this.pagingBar
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
						items: [
							'发起开始时间：',
							{
								xtype: "datefield",
								width: 170,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_beginTime
							},
							' 发起最后时间：',
							{
								xtype: "datefield",
								width: 170,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_endTime
							},
							'-',
							{
								xtype: 'button',
								text: lang('search'),
								handler: function(){
									_this.searchParams = {
										name: Ext.getCmp(_this.ID_find_name).getValue(),
										title: Ext.getCmp(_this.ID_find_title).getValue(),
										beginTime: Ext.getCmp(_this.ID_find_beginTime).getRawValue(),
										endTime: Ext.getCmp(_this.ID_find_endTime).getRawValue()								
									};
									_this.store.load({params:_this.searchParams});
								}
							},'-',
							{
								xtype: 'button',
								text: lang('clear'),
								handler: function(){
									Ext.getCmp(_this.ID_find_name).setValue('');
									Ext.getCmp(_this.ID_find_title).setValue('');
									Ext.getCmp(_this.ID_find_beginTime).setRawValue('');
									Ext.getCmp(_this.ID_find_endTime).setRawValue('');
									_this.searchParams = {
										name: '',
										title: '',
										beginTime: 0,
										endTime: 0,
										status: 0
									};
									_this.store.load();
								}
							}
						]
					}).render(this.tbar);
				}
			},
			tbar: new Ext.Toolbar({
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
			items: [this.gridCt]
		});
	},
	
	makeTitle : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var h = value + "<br />&nbsp;<span class='cnoa_color_gray'>" + record.data.title + "</span>";
		return h;
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
		var h  = "<a href='javascript:void(0);' onclick='CNOA_flow_table_list.showFlow("+value+");'>查看</a>";
		return h;
	},
	
	//查看流程(操作)
	showFlow : function(ulid){
		mainPanel.closeTab("CNOA_MENU_FLOW_TABLE_VIEW");
		mainPanel.loadClass("index.php?app=flow&func=table&action=list&task=loadPage&from=view&ulid="+ulid, "CNOA_MENU_FLOW_TABLE_VIEW", "查看工作流程", "icon-flow");
	},
	
	//查看流程(操作)
	newFlow : function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_NEWFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=newflow&ac=add&lid="+this.lid, "CNOA_MENU_FLOW_USER_NEWFLOW", "新建工作流程", "icon-flow-new");
	}
};

