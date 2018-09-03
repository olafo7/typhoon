//定义全局变量：
var CNOA_user_task_listWatchClass, CNOA_user_task_listWatch;


/**
* 主面板-列表
*
*/
CNOA_user_task_listWatchClass = CNOA.Class.create();
CNOA_user_task_listWatchClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;

		this.content_start = 1;

		this.baseUrl = "index.php?app=user&func=task&action=watch";
		this.action = "add";
		this.actionTitle = this.action == "add" ? lang('addPlan') : lang('editPlan');

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_plan_content = Ext.id();
		
		this.search = {
			title: "",
			uid: 0,
			execman: 0,
			examapp: 0
		}
		
		/**
		 * 我的个人计划列表
		 姓名
		 性别
		 所属组
		 出生日期
		 手机
		 电话
		 电子邮箱
		 */
		this.fields = [
			{name : "tid"},
			{name : "title"},
			{name : "eenable"},		//是否可编辑
			{name : "denable"},		//是否可删除
			{name : "repealenable"},	//是否可撤销
			{name : "urgeenable"},	//是否可督办
			{name : "failenable"},	//是否可失败
			{name : "jixiao"},
			{name : "status"},
			{name : "progress"},
			{name : "execman"},
			{name : "postter"},
			{name : "uid"},
			{name : "stime"},
			{name : "etime"},
			{name : "statusText"},
			{name : "statusColor"},
			{name : "_id"},
			{name : "_parent"},
			{name : "_is_leaf"},
			{name : "_level"}
		];
		
		try{
			this.store = new Ext.ux.maximgb.tg.AdjacencyListStore({
				autoLoad : true,
				url: this.baseUrl+"&task=getTaskTreeList",
				reader: new Ext.data.JsonReader(
					{
						id: '_id',
						root: 'data',
						totalProperty: 'total',
						successProperty: 'success'
					}, 
					this.fields
				)
				
				//proxy: new Ext.data.MemoryProxy(this.colModel)
			});
			
			/*this.store.on("load",function(th,record,options){
				//cdump(record);
				//cdump(th);
				th.each(function(record) {
					if(!th.isLeafNode(record)){
						th.expandNode(record);
						//th.expandAll(record);
					}
				});
			});*/
		}catch(e){
		}
		//this.store.load();
		
		/*
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.store.load();
		*/
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		
		/*try{
			this.store = new Ext.ux.maximgb.treegrid.AdjacencyListStore({
				//autoLoad : true,
	    		url: this.baseUrl+"&task=getTaskTreeList",
				reader: new Ext.data.JsonReader(
					{
						id: '_id',
						root: 'data',
						totalProperty: 'total',
						successProperty: 'success'
					},
					this.fields
				),
				'load' : function(){
					load.expandAll();
				}
			});
		}catch(e){
		}*/
		
		//this.store.load();
		
		/*
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('taskTitle'), dataIndex: 'title', width: 140, sortable: true, menuDisabled :true},
			{header: lang('status'), dataIndex: 'status', width: 70, sortable: false,menuDisabled :true, renderer: this.makeStatus.createDelegate(this)},
			{header: lang('jdjx'), dataIndex: 'jixiao', width: 50, sortable: false,menuDisabled :true},
			{header: lang('principal'), dataIndex: 'execman', width: 50, sortable: false,menuDisabled :true},
			{header: lang('bzr'), dataIndex: 'postter', width: 50, sortable: false,menuDisabled :true},
			{header: lang('startTime'), dataIndex: 'stime', width: 60, sortable: false,menuDisabled :true},
			{header: lang('endTime'), dataIndex: 'etime', width: 60, sortable: false,menuDisabled :true},
			{header: lang('opt'),dataIndex: '',width: 20, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);*/
		
		this.colModel = [
			//expander,
			{header: lang('taskTitle'), dataIndex: 'title', width: 220},
			{header: lang('status'), dataIndex: 'status', width: 120, renderer: this.makeStatus.createDelegate(this)},
			{header: lang('jdjx'), dataIndex: 'jixiao', width: 120},
			{header: lang('principal'), dataIndex: 'execman', width: 80},
			{header: lang('bzr'), dataIndex: 'postter', width: 80},
			{header: lang('startTime'), dataIndex: 'stime', width: 80},
			{header: lang('endTime'), dataIndex: 'etime', width: 80},
			{header: lang('opt'),dataIndex: '',width: 80, renderer:this.makeOperate.createDelegate(this)}
		];
		
		
		/*this.pagingBar = new Ext.PagingToolbar({
			//style: 'border-left-width:1px;',
			displayInfo:true,
			
			   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					Ext.apply(params,_this.search);
				}
			}
		});*/
		
		
		
		this.pagingBar =  new Ext.ux.maximgb.tg.PagingToolbar({
			store: this.store,
			displayInfo: true,
			pageSize: 15
		});
		
		var ID_Search_execman = Ext.id();
		var ID_Search_tasktitle = Ext.id();
		var ID_Search_uid = Ext.id();
		var ID_Search_examapp = Ext.id();
		this.searchBar = new Ext.Toolbar({
			items: [
				lang('taskName')+':',
				{
					xtype: "textfield",
					id: ID_Search_tasktitle,
					width: 128
				},
				'&nbsp;'+lang('bzr')+':',
				{
					xtype: 'hidden',
					id: ID_Search_uid
				},
				{
					xtype: "triggerForPeople",
					dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					width: 100,
					id: ID_Search_uid+"Tr",
					listeners: {
						"selected":function(th, uid, uname){
							Ext.getCmp(ID_Search_uid).setValue(uid);
						}
					}
				},
				'&nbsp;'+lang('principal')+':',
				{
					xtype: 'hidden',
					id: ID_Search_execman
				},
				{
					xtype: "triggerForPeople",
					dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					width: 100,
					id: ID_Search_execman+"Tr",
					listeners: {
						"selected":function(th, uid, uname){
							Ext.getCmp(ID_Search_execman).setValue(uid);
						}
					}
				},
				'&nbsp;'+lang('approvalor')+':',
				{
					xtype: 'hidden',
					id: ID_Search_examapp
				},
				{
					xtype: "triggerForPeople",
					dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					width: 100,
					id: ID_Search_examapp+"Tr",
					listeners: {
						"selected":function(th, uid, uname){
							Ext.getCmp(ID_Search_examapp).setValue(uid);
						}
					}
				},
				{
					xtype: "button",
					text: lang('search'),
					style: "margin-left:5px",
					//handleMouseEvents: false,
					handler: function(){
						_this.search.title = Ext.getCmp(ID_Search_tasktitle).getValue();
						_this.search.uid = Ext.getCmp(ID_Search_uid).getValue();
						_this.search.execman = Ext.getCmp(ID_Search_execman).getValue();
						_this.search.examapp = Ext.getCmp(ID_Search_examapp).getValue();
						_this.store.load({params:{title: _this.search.title, uid: _this.search.uid, execman: _this.search.execman, examapp: _this.search.examapp}});
					}
				},
				{
					xtype: "button",
					text: lang('clear'),
					style: "margin-left:5px",
					handler: function(){
						Ext.getCmp(ID_Search_tasktitle).setValue("");
						Ext.getCmp(ID_Search_uid).setValue("");
						Ext.getCmp(ID_Search_execman).setValue("");
						Ext.getCmp(ID_Search_examapp).setValue("");
						Ext.getCmp(ID_Search_uid+"Tr").setValue("");
						Ext.getCmp(ID_Search_execman+"Tr").setValue("");
						Ext.getCmp(ID_Search_examapp+"Tr").setValue("");
						_this.search = {
							title: "",
							uid: 0,
							execman: 0,
							examapp: 0
						}
						_this.store.load();
					}
				}
			]
		}); 
		
		this.grid = new Ext.ux.maximgb.tg.GridPanel({
			columns: this.colModel,
			store: this.store,
			border: false,
        	enableDD: true,
			useArrows: false,
			stripeRows: true,
			//viewConfig: {
			//	forceFit: true
			//},
			//dataUrl: this.baseUrl + '&task=getTaskTreeList',
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					//_this.loadAddEditForm();
				},  
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					
				},
				//单元格单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					
					if(columnIndex == 2){
						this.viewTask(record.data.tid);
					}
				}.createDelegate(this),
				"render" : function(){
					_this.searchBar.render(this.tbar);
				}
			},
			tbar : new Ext.Toolbar({
				//style: 'border-left-width:1px;',
				items: [
					//"<span style='font-weight:bold;margin-right:20px;'>任务列表</span>",
					{
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},{
						handler : function(button, event) {
							_this.openExportExcelWindow();
						}.createDelegate(this),
						iconCls: 'icon-excel',
						text : lang('export2')
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 65}
				]
			}),
			bbar: this.pagingBar
			
		});
		
		/*
		this.grid = new Ext.grid.GridPanel({
			//bodyStyle: 'border-left-width:1px;',
			store: this.store,
			loadMask : {msg: lang('waiting')},
			sm: this.sm,
			cm: this.colModel,
			hideBorders: true,
			border: false,
			stripeRows: true,
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					//_this.loadAddEditForm();
				},  
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					
				},
				//单元格单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					
					if(columnIndex == 2){
						this.viewTask(record.data.tid);
					}
				}.createDelegate(this),
				"render" : function(){
					_this.searchBar.render(this.tbar);
				}
			},
			tbar : new Ext.Toolbar({
				//style: 'border-left-width:1px;',
				items: [
					//"<span style='font-weight:bold;margin-right:20px;'>任务列表</span>",
					{
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},'-',{
						handler : function(button, event) {
							_this.openExportExcelWindow();
						}.createDelegate(this),
						iconCls: 'icon-excel',
						tooltip: '导出任务列表为Excel文件',
						text : '导出'
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 65}
				]
			}),
			bbar: this.pagingBar
		});*/
		
		this.centerPanel = new Ext.Panel({
			region: "center",
			layout: "fit",
			items: [this.grid]
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
		var ID_export_execman = Ext.id();
		var formPanel = new Ext.form.FormPanel({
			border: false,
			bodyStyle: "padding:10px;",
			waitMsgTarget: true,
			labelWidth: 110,
			items: [
				{
					xtype: 'hidden',
					name: 'execman',
					id: ID_export_execman
				},
				{
					xtype: "triggerForPeople",
					fieldLabel: lang('principal'),
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					width: 100,
					allowBlank: false,
					listeners: {
						"selected":function(th, uid, uname){
							Ext.getCmp(ID_export_execman).setValue(uid);
						}
					}
				},
				{
					xtype: "datefield",
					fieldLabel: lang('startTime'),
					width: 150,
					format: "Y-m-d",
					editable: false,
					allowBlank: false,
					name: "stime"
				},
				{
					xtype: "datefield",
					fieldLabel: lang('endTime'),
					width: 150,
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
			title: lang('exportTask'),
			resizable: false,
			modal: true,
			layout: "fit",
			items: formPanel,
			buttons : [
				//汇报
				{
					text : lang('export2'),
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
			title: lang('downExcel'),
			resizable: false,
			modal: true,
			layout: "fit",
			bodyStyle: "padding:10px;background-color: #FFF;",
			html: lang('clickToDownload')+":<br/>" + code,
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

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		h = "<a href='javascript:void(0);' onclick='CNOA_user_task_listWatch.viewTask("+rd.tid+");'>"+lang('view')+"</a>";
		return h;
	},

	makeStatus : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var st = rd.status;
		var title = "&nbsp;";
		var width = 0;
		var color = "#5B5B5B";
		
		title = rd.statusText;
		width = rd.progress;
		color = rd.statusColor;

		var conHTML  = "<div style='color:#808080;margin-top:3px;'>" + title + "</div>";
			conHTML += "<div style='width:100px;height:5px;background-color:#D4D4D4;'><div style='font-size:0;line-height:0;height:5px;width:" + width + "px;background-color:" + color + ";'></div></div>";

		return conHTML;
	},
	
	viewTask : function(tid){
		mainPanel.closeTab("CNOA_MENU_USER_TASK_WATCH_VIEW");
		mainPanel.loadClass("index.php?app=user&func=task&action=watch&task=loadPage&from=view&tid="+tid, "CNOA_MENU_USER_TASK_WATCH_VIEW", lang('viewTask'), "icon-page-view");
	}
}