//定义全局变量：
var CNOA_user_task_totalClass, CNOA_user_task_total;

/**
* 主面板-列表
*
*/
CNOA_user_task_totalClass = CNOA.Class.create();
CNOA_user_task_totalClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;

		this.content_start = 1;

		this.baseUrl = "index.php?app=user&func=task&action=total";
		//this.action = "add";
		//this.actionTitle = this.action == "add" ? lang('addPlan') : lang('editPlan');

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_plan_content = Ext.id();
		
		var ID_SEARCH_STIME = Ext.id();
		var ID_SEARCH_ETIME = Ext.id();
		
		this.storeBar = {
			execman : 0,
			stime : "",
			etime : ""
		};
		
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
			{name : "name"},
			{name : "dept"},
			{name : "jixiaofast"},
			{name : "jixiaoslow"},
			{name : "jixiaoaver"},
			{name : "jixiaoall"},
			{name : "done"},
			{name : "doing"},
			{name : "all"},
			{name : "overdone"},
			{name : "overdoing"},
			{name : "cancel"},
			{name : "wait"},
			{name : "refuse"},
			{name : "execman"}
		];
		
		this.store = new Ext.data.GroupingStore({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
			listeners : {
				exception : function(th, type, action, options, response, arg){
					var result = Ext.decode(response.responseText);
					if(result.failure){
						CNOA.msg.alert(result.msg);
					}
				}
			}
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		});
		
		this.store.load();
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "uid", dataIndex: 'uid', hidden: true},
			{header: lang('truename'), dataIndex: 'name', width: 80, sortable: true, menuDisabled :true},
			{header: lang('inDepartment'), dataIndex: 'dept', width: 160, sortable: true, menuDisabled :true},
			{header: lang('allTask'), dataIndex: 'all', width: 70, sortable: false,menuDisabled :true},
			{header: lang('taskwcs'), dataIndex: 'done', width: 80, sortable: false,menuDisabled :true},
			{header: lang('taskWaitingCount'), dataIndex: 'wait', width: 80, sortable: false,menuDisabled :true},
			{header: lang('taskIng'), dataIndex: 'doing', width: 100, sortable: false,menuDisabled :true},
			{header: lang('jxzcqs'), dataIndex: 'overdoing', width: 100, sortable: false,menuDisabled :true},
			{header: lang('wcycqs'), dataIndex: 'overdone', width: 100, sortable: false,menuDisabled :true},
			{header: lang('recellCount'), dataIndex: 'cancel', width: 80, sortable: false,menuDisabled :true},
			{header: lang('rejectCount'), dataIndex: 'refuse', width: 80, sortable: false,menuDisabled :true},
			//{header: lang('opt'),dataIndex: '',width: 150, renderer:this.makeOperate.createDelegate(this)},
			//{header: "进度绩效(高)", dataIndex: 'jixiaofast', width: 90, sortable: false,menuDisabled :true, renderer : this.jixiaofast.createDelegate(this)},
			//{header: "进度绩效(低)", dataIndex: 'jixiaoslow', width: 90, sortable: false,menuDisabled :true, renderer : this.jixiaoslow.createDelegate(this)},
			//{header: "进度绩效(总)", dataIndex: 'jixiaoall', width: 90, sortable: false,menuDisabled :true, renderer : this.jixiaoall.createDelegate(this)},
			//{header: "进度绩效(平均)", dataIndex: 'jixiaoaver', width: 90, sortable: false,menuDisabled :true, renderer : this.jixiaoaver.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.pagingBar = new Ext.PagingToolbar({
			//style: 'border-left-width:1px;',
			displayInfo:true,
			
			   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					Ext.apply(params, _this.storeBar);
				}
			}
		});
		
		var ID_Search_execman = Ext.id();
		var ID_Search_tasktitle = Ext.id();
		var ID_Search_uid = Ext.id();
		var ID_Search_examapp = Ext.id();
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			sm: this.sm,
			autoWidth: true,
			border: false,
			listeners:{
				//单元格单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					
					/*if(columnIndex == 2){
						this.viewTask(record.data.tid);
					}*/
					
					try{
						var status = '';
						if(columnIndex == 5){
							status = 'all';
						}else if(columnIndex == 6){
							status = 'done';
						}else if(columnIndex == 7){
							status = 'wait';
						}else if(columnIndex == 8){
							status = 'doing';
						}else if(columnIndex == 9){
							status = 'overdoing';
						}else if(columnIndex == 10){
							status = 'overdone';
						}else if(columnIndex == 11){
							status = 'cancel';
						}else if(columnIndex == 12){
							status = 'refuse';
						}
						
						if(columnIndex==0 || columnIndex ==1 || columnIndex ==2 || columnIndex ==3 || columnIndex ==4){
						
						}else{
							this.showTaskList(record.data.execman, status);
						}
					}catch(e){}
					
					
				}.createDelegate(this)
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
					},/*"-",
					{
						text:"进行中的任务",
						enableToggle: true,
						allowDepress: false,
						iconCls:"icon-application-list",
						toggleGroup: "task_btn_group",
						handler:function(button, event){
							_this.storeBar.storeType = "doing";
							_this.store.load({params : _this.storeBar});
						}
					},"-",
					{
						text:"超期的任务",
						pressed:true,
						enableToggle: true,
						allowDepress: false,
						iconCls:"icon-application-list",
						toggleGroup: "task_btn_group",
						handler:function(button, event){
							_this.storeBar.storeType = "over";
							_this.store.load({params : _this.storeBar});
						}
					},"-",
					{
						text:"已完成的任务",
						enableToggle: true,
						allowDepress: false,
						iconCls:"icon-application-list",
						toggleGroup: "task_btn_group",
						handler:function(button, event){
							_this.storeBar.storeType = "already";
							_this.store.load({params : _this.storeBar});
						}
					},"-",
					{
						text:"所有人任务",
						enableToggle: true,
						allowDepress: false,
						iconCls:"icon-application-list",
						toggleGroup: "task_btn_group",
						handler:function(button, event){
							_this.storeBar.storeType = "all";
							_this.store.load({params : _this.storeBar});
						}
					},"-",
					{
						text:"列表模式",
						enableToggle: true,
						allowDepress: false,
						iconCls:"icon-system-s-report",
						toggleGroup: "task_btn_list_group",
						tooltip: "列表模式",
						handler:function(button, event){
							_this.store.clearGrouping();
						}
					},"-",
					{
						text:"分组模式",
						pressed:true,
						enableToggle: true,
						allowDepress: false,
						iconCls:"icon-view-form-png",
						toggleGroup: "task_btn_list_group",
						tooltip: "分组模式",
						handler:function(button, event){
							_this.store.groupBy('info', true);
							_this.grid.view.collapseAllGroups();
						}
					},"-",
					{
						text:"展开所有分组",
						enableToggle: true,
						allowDepress: false,
						iconCls:"icon-style-tree",
						toggleGroup: "task_btn_list_group",
						tooltip: "展开所有分组",
						handler:function(button, event){
							_this.store.groupBy('info', true);
							_this.grid.view.expandAllGroups();
						}
					},"-",*/
					"->",{xtype: 'cnoa_helpBtn', helpid: 64}
				]
			}),
			bbar: this.pagingBar
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'fit',
			autoScroll: false,
			items: [this.grid],
			tbar : new Ext.Toolbar({
				items: [
				/*
					'任务名称:',
					{
						xtype: "textfield",
						id: ID_Search_tasktitle,
						width: 128
					},
					'&nbsp;布置人:',
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
					},*/
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
					},"&nbsp;"+lang('startTime')+"",
					{
						xtype : "datefield",
						width : 120,
						format : "Y-m-d",
						id : ID_SEARCH_STIME
					},"&nbsp;"+lang('endTime'),
					{
						xtype : "datefield",
						width : 120,
						format : "Y-m-d",
						id : ID_SEARCH_ETIME
					},
					
					/*
					'&nbsp;审批人:',
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
					},*/
					{
						xtype: "button",
						text: lang('search'),
						style: "margin-left:5px",
						handler: function(){
							_this.storeBar.execman = Ext.getCmp(ID_Search_execman).getValue();
							_this.storeBar.stime   = Ext.getCmp(ID_SEARCH_STIME).getRawValue();
							_this.storeBar.etime   = Ext.getCmp(ID_SEARCH_ETIME).getRawValue();
							_this.store.load({params : _this.storeBar});
						}
					},
					{
						xtype: "button",
						text: lang('clear'),
						style: "margin-left:5px",
						handler: function(){
							Ext.getCmp(ID_Search_execman).setValue("");
							Ext.getCmp(ID_Search_execman+"Tr").setValue("");
							Ext.getCmp(ID_SEARCH_STIME).setValue("");
							Ext.getCmp(ID_SEARCH_ETIME).setValue("");
							
							_this.storeBar.stime 	= "";
							_this.storeBar.etime	= "";
							_this.storeBar.execman 	= "";
							
							_this.store.load({params : _this.storeBar});
						}
					}
				]
			})
		});
	},
	
	showTaskList: function(execman, status){
		mainPanel.closeTab("CNOA_MENU_USER_TASK_VIEW_DETAIL");
		mainPanel.loadClass("index.php?app=user&func=task&action=total&task=loadPage&from=viewTotalDetial&execman="+execman+"&status="+status, "CNOA_MENU_USER_TASK_VIEW_DETAIL", lang('viewTask'), "icon-page-view");
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
		
		var formPanel = new Ext.form.FormPanel({
			border: false,
			bodyStyle: "padding:10px;",
			waitMsgTarget: true,
			labelWidth: 110,
			items: [
				{
					xtype: "datefield",
					fieldLabel: lang('startTime'),
					width: 170,
					format: "Y-m-d",
					editable: false,
					allowBlank: false,
					name: "stime"
				},
				{
					xtype: "datefield",
					fieldLabel: lang('endTime'),
					width: 170,
					format: "Y-m-d",
					editable: false,
					allowBlank: false,
					name: "etime"
				},
				{
					xtype: "displayfield",
					hideLabel: true,
					value: lang('exportTaskNotice2')
				}
			]
		});
		
		var win = new Ext.Window({
			width: 320,
			height: 160,
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
			html: lang('clickToDownload')+"：<br/>" + code,
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

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var st = rd.status;
		var em = false;	//可修改
		var dm = false; //可删除
		var rm = false; //可撤销
		//var vm = false; //可浏览
		var um = false; //可督办
		var fm = false; //可失败

		em = rd.eenable;
		dm = rd.denable;
		rm = rd.repealenable;
		um = rd.urgeenable;
		fm = rd.failenable;

		//var h  = em ? "<a href='javascript:void(0);' onclick='CNOA_user_task_list.editTask("+rd.tid+");'>修改</a>" : "<span class='cnoa_color_gray2'>修改</span>";
		//	h += "&nbsp;&nbsp;";
		//	h += dm ? "<a href='javascript:void(0);' onclick='CNOA_user_task_list.deleteTask("+rd.tid+", "+rd.status+");'>删除</a>" : "<span class='cnoa_color_gray2'>删除</span>";
	//		h += "&nbsp;&nbsp;";
		//	h += rm ? "<a href='javascript:void(0);' onclick='CNOA_user_task_list.repealTask("+rd.tid+", "+rd.status+");'>撤销</a>" : "<span class='cnoa_color_gray2'>撤销</span>";
		//	h += "&nbsp;&nbsp;";
		var	h = "<a href='javascript:void(0);' onclick='CNOA_user_task_total.viewTask("+rd.tid+");'>"+lang('view')+"</a>";

		//	h += "<br />";
		//	h += um ? "<a href='javascript:void(0);' onclick='CNOA_user_task_list.urgeTask("+rd.tid+", "+rd.status+");'>督办</a>" : "<span class='cnoa_color_gray2'>督办</span>";
		//	h += "&nbsp;&nbsp;";
		//	h += um ? "<a href='javascript:void(0);' onclick='CNOA_user_task_list.delayTask("+rd.tid+", "+rd.status+", \""+rd.etime+"\");'>延期</a>" : "<span class='cnoa_color_gray2'>延期</span>";
		//	h += "&nbsp;&nbsp;";
		//	h += fm ? "<a href='javascript:void(0);' onclick='CNOA_user_task_list.failTask("+rd.tid+", "+rd.status+");'>失败</a>" : "<span class='cnoa_color_gray2'>失败</span>";
		return h;
	},
	
	/*
	editTask : function(tid){
		mainPanel.closeTab("CNOA_MENU_USER_TASK_ADDEDIT");
		mainPanel.loadClass("index.php?app=user&func=task&action=default&task=loadPage&from=addedit&job=edit&tid="+tid, "CNOA_MENU_USER_TASK_ADDEDIT", lang('editTask'));
	},

	deleteTask : function(tid, status){
		var _this = this;
		CNOA.msg.cf("确定删除本任务吗？", function(btn){
			if(btn == "yes"){
				Ext.Ajax.request({  
					url: _this.baseUrl + "&task=operateTask&tid="+tid,
					method: 'POST',
					params: {tid: tid, job: "delete", status: status},
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.alert(result.msg, function(){
								_this.store.reload();
							});
						}else{
							CNOA.msg.alert(result.msg);
						}
					}
				});
			}
		});
	},
	
	repealTask : function(tid, status){
		var _this = this;
		CNOA.msg.cf("确定撤销本任务吗？", function(btn){
			if(btn == "yes"){
				_this.makeMiniWindow("撤销任务", "撤销理由", "repeal", tid, status);
			}
		});
		
	},

	urgeTask : function(tid, status){
		var _this = this;
		CNOA.msg.cf("确定督办本任务吗？", function(btn){
			if(btn == "yes"){
				_this.makeMiniWindow("督办任务", "督办内容", "urge", tid, status);
			}
		});
		
	},

	failTask : function(tid, status){
		var _this = this;
		CNOA.msg.cf("确定使本任务失败吗？", function(btn){
			if(btn == "yes"){
				_this.makeMiniWindow("任务失败", "失败原因", "fail", tid, status);
			}
		});
	},

	delayTask : function(tid, status, etime){
		var _this = this;
		CNOA.msg.cf("确定延长本任务的完成时间吗？", function(btn){
			if(btn == "yes"){
				_this.makeDelayWindow("任务延期", "delay", tid, status, etime);
			}
		});
	},*/
	
	makeMiniWindow : function(title, label, job, tid, status){

		var _this = this;

		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding-top:20px;",
			items: [
				{
					xtype: "textarea",
					//width: 288,
					//height: 130,
					anchor: "-20 -20",
					name: "content",
					fieldLabel: label
				}
			]
		});
		
		var win = new Ext.Window({
			width: 400,
			height: 270,
			title: title,
			resizable: true,
			modal: true,
			layout: "fit",
			items: formPanel,
			buttons : [
				//汇报
				{
					text : lang('ok'),
					iconCls: 'icon-order-s-accept',
					handler : function() {
						submit();
						win.close();
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
		
		var submit = function(){
			var c = formPanel.getForm().findField("content").getValue();
			Ext.Ajax.request({  
				url: _this.baseUrl + "&task=operateTask&tid="+tid,
				method: 'POST',
				params: {tid: tid, job: job, status: status, content: c},
				success: function(r) {
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						CNOA.msg.notice2(result.msg);
						_this.store.reload();
					}else{
						CNOA.msg.alert(result.msg);
					}
				}
			});
		}
	},

	makeDelayWindow : function(title, job, tid, status, etime){

		var _this = this;

		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding-top:20px;",
			items: [
				{
					xtype: "displayfield",
					fieldLabel: lang('oldEndTime'),
					value: etime
				},
				{
					xtype: "datefield",
					name: "newtime",
					width: 160,
					minValue: etime,
					format: "Y-m-d",
					fieldLabel: lang('editTo')
				}
			]
		});
		
		var win = new Ext.Window({
			width: 400,
			height: 270,
			title: title,
			resizable: true,
			modal: true,
			layout: "fit",
			items: formPanel,
			buttons : [
				//汇报
				{
					text : lang('ok'),
					iconCls: 'icon-order-s-accept',
					handler : function() {
						submit();
						win.close();
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
		
		var submit = function(){
			var newtime = formPanel.getForm().findField("newtime").getRawValue();
			Ext.Ajax.request({  
				url: _this.baseUrl + "&task=operateTask&tid="+tid,
				method: 'POST',
				params: {tid: tid, job: job, status: status, newtime: newtime},
				success: function(r) {
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						CNOA.msg.notice2(result.msg);
						_this.store.reload();
					}else{
						CNOA.msg.alert(result.msg);
					}
				}
			});
		}
	},
	
	/*viewTask : function(tid){
		mainPanel.closeTab("CNOA_MENU_USER_TASK_VIEW");
		mainPanel.loadClass("index.php?app=user&func=task&action=total&task=loadPage&from=view&tid="+tid, "CNOA_MENU_USER_TASK_VIEW", lang('viewTask'), "icon-page-view");
	},*/
	
	/*
	addTask : function(){
		mainPanel.closeTab("CNOA_MENU_USER_TASK_ADDEDIT");
		mainPanel.loadClass("index.php?app=user&func=task&action=default&task=loadPage&from=addedit&job=add", "CNOA_MENU_USER_TASK_ADDEDIT", lang('addTask'), "icon-page-addedit");
	},
	*/
	
	planTime : function(value, p, record){
		return "<span >"+value+"</span><br /><span >"+record.data.p_etime+"</span>";
	},
	
	trueTime : function(value, p, record){
		return "<span >"+value+"</span><br /><span >"+record.data.t_etime+"</span>";
	},
	
	jixiaofast : function(value, p, record){
		return "<span class='cnoa_color_green'><b>"+value+"</b></span>";
	},
	
	jixiaoslow : function(value, p, record){
		return "<span class='cnoa_color_red'><b>"+value+"</b></span>";
	},
	
	jixiaoall : function(value, p, record){
		return "<span class='cnoa_color_green'><b>"+value+"</b></span>";
	},
	
	jixiaoaver : function(value, p, record){
		return "<span class='cnoa_color_green'><b>"+value+"</b></span>";
	}
}

/*
Ext.onReady(function() {
		CNOA_user_task_total = new CNOA_user_task_totalClass();
		Ext.getCmp(CNOA.user.task.total.parentID).add(CNOA_user_task_total.mainPanel);
		Ext.getCmp(CNOA.user.task.total.parentID).doLayout();

});*/