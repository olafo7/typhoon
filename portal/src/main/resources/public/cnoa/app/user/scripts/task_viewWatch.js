//定义全局变量：
var CNOA_user_task_viewWatchClass, CNOA_user_task_viewWatch;
var CNOA_user_task_discussAddEditClass, CNOA_user_task_discussAddEdit;

/**
* 主面板-列表
*
*/
CNOA_user_task_viewWatchClass = CNOA.Class.create();
CNOA_user_task_viewWatchClass.prototype = {
	init : function(){
		var _this = this;
		
		this.tid 	 = CNOA.user.task.viewWatch.tid;
		this.status  = CNOA.user.task.viewWatch.status;
		this.from	 = CNOA.user.task.viewWatch.from;
		this.title	 = "";
		this.etime	 = "";
		this.baseUrl = "index.php?app=user&func=task&action=watch&tid="+this.tid+"&status="+this.status;

		this.ID_btn_edit	 = Ext.id();
		this.ID_btn_delete	 = Ext.id();
		this.ID_btn_repeal	 = Ext.id();
		this.ID_btn_complete = Ext.id();
		this.ID_btn_accept	 = Ext.id();
		this.ID_btn_reject	 = Ext.id();
		this.ID_btn_agree  	 = Ext.id();
		this.ID_btn_disagree = Ext.id();
		this.ID_btn_finish	 = Ext.id();
		this.ID_btn_nofinish = Ext.id();
		this.ID_btn_viewedurge = Ext.id();
		this.ID_btn_fail	 = Ext.id();
		this.ID_btn_report_window_report = Ext.id();
		
		this.dataSource		 = null;
		
		//三个tab的载入总数
		this.tab_event_loadCount = 0;
		this.tab_discuss_loadCount = 0;
		this.tab_document_loadCount = 0;
		
		this.tplTitle = new Ext.XTemplate(
			'<div style="width:770px;margin-bottom:5px;height:40px;background-color:#FEFBDC;font-size:16px;font-weight:bold;line-height:40px; text-align: center;">[任务]{title}',
			'</div>',
			'<div style="width:768px;height:auto;padding-bottom:3px;border:1px solid #F6C4AB;margin-top:5px;margin-bottom:5px;background-color:#FFF1CC;font-size:16px;display:{showTip}">',
			'	<div style="font-size:12px;padding:5px;">',
			'	{tip}',
			'	</div>',
			'	<div style="padding-left:5px;" id="CNOA_task_view_btn_job">',
			'	</div>',
			'	<div class="clear"></div>',
			'</div>'
		);
		
		this.tplBody = new Ext.XTemplate(
			'<div style="margin-bottom:5px;margin-top:1px;width:770px;">',
			'	<div style="display:none;" id="CNOA_task_view_btn_operate_Ct"><div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('optArea')+'</span></div>',
			'	<div style="background-color:#EFF5FF;height:22px;padding:5px;" id="CNOA_task_view_btn_operate">',
			'	</div>',
			'	<div class="clear"></div></div>',
			'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('baseInfo2')+'</span></div>',
			'	<div style="background-color:#EFF5FF;padding-bottom:5px;">',
			'		<ul class="user_task_view_ul">',
			'			<li><span class="first">'+lang('nowStatus')+': </span><span class="second" style="color:{statusColor};">{statusText}</span></li>',
			'			<li><span class="first">'+lang('taskAddTime')+': </span><span class="second">{posttime}</span></li>',
			'			<li>&nbsp;</li>',
			'			<li><span class="first">'+lang('taskStartTime')+': </span><span class="second">{stime}</span></li>',
			'			<li><span class="first">'+lang('taskAddEndTime')+': </span><span class="second">{etime}</span></li>',
			'			<li><span class="first">'+lang('taskRatings')+': </span><span class="second">{point}</span></li>',
			'			<li><span class="first">'+lang('sjStartTime')+': </span><span class="second">{sttime}</span></li>',
			'			<li><span class="first">'+lang('sjwcsj')+': </span><span class="second">{entime}</span></li>',
			'			<li><span class="first">'+lang('taskProgress')+': </span><span class="second" style="text-align:left;"><div style="width:100px;height:5px;background-color:#D4D4D4;float:left;margin-top:10px;"><div style="font-size:0;line-height:0;height:5px;width:{progress}px;background-color:{statusColor};"></div></div><span style="font-family:\'Arial\';font-size:9px;line-heigth:9px;height:9px;color:#7C7C7C;">&nbsp;{progress}%</span></span></li>',
			'			<li><span class="first">'+lang('bzr')+': </span><span class="second"><a>{postter}</a></span></li>',
			'			<li><span class="first">'+lang('principal')+': </span><span class="second"><a>{execman}</a></span></li>',
			'			<li><span class="first">'+lang('approvalor')+': </span><span class="second"><a>{examapp}</a></span></li>',
			'			<li style="width:700px;"><span class="first">'+lang('participant')+': </span><span class="second" style="width:600px;text-align:left;">{participant}</span></li>',
			'		</ul>',
			'		<div class="clear"></div>',
			'	</div>',
			'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('taskContent')+'</span></div>',
			'	<div style="padding:20px;background-color:#EFF5FF;">',
			'	{content}',
			'	</div>',
			'	<tpl if="attachCount &gt; 0">',
			'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('attach')+'</span></div>',
			'	<div style="padding:20px;background-color:#EFF5FF;">',
			'		{attach}',
			'	</div>',
			'	</tpl>',
			'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('jfbz')+'</span></div>',
			'	<div style="padding:20px;background-color:#EFF5FF;">',
			'	{prizepunish}',
			'	</div>',
			'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('leaderInstruction')+'</span></div>',
			'	<div style="padding:20px;background-color:#EFF5FF;">',
			'	{leadersay}',
			'	</div>',
			'</div>'
		);
		
		this.infoPanel = new Ext.Panel({
			border: false,
			
			listeners: {
				render : function(){
					_this.readInfo();
				}
			}
		});
		
		this.infoPanelBody = new Ext.Panel({
			border: false,
			
			listeners: {
				render : function(){
					
				}
			}
		});
		
		/**
		 * 事件查看列表
		 */
		this.fieldsEvent = [
			{name:"id"},
			{name:"type"},
			{name:"typename"},
			{name:"title"},
			{name:"content"},
			{name:"user"},
			{name:"posttime"}
		];
		this.storeEvent = new Ext.data.GroupingStore({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getEventList"}),
			//groupField: 'date',
			//sortInfo: {field: 'id', direction: 'ASC'},
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fieldsEvent})
		});
		//this.storeEvent.load();
		this.colModelEvent = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', width: 20, sortable: true, menuDisabled: true, hidden: true},
			{header: lang('type'), dataIndex: 'type', width: 40, menuDisabled: true, sortable: true, renderer : _this.renderEventType.createDelegate(this)},
			{header: lang('eventTitle') + " / " + lang('content'), dataIndex: 'content', width: 574, menuDisabled: true, sortable: false, renderer : _this.renderEventContent.createDelegate(this)},
			{header: lang('operator')+" / "+lang('time'), dataIndex: 'other', width: 160, sortable: false,resizable: false, menuDisabled: true, renderer : _this.renderEventOther.createDelegate(this)}
		]);
		this.gridEvent = new Ext.grid.GridPanel({
			stripeRows : true,
			ds: this.storeEvent,
			//hideHeaders: true,
			loadMask : {msg: lang('waiting')},
			cm: this.colModelEvent,
			hideBorders: true,
			border: false,
			autoHeight: true,
			listeners:{  
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var rd = grid.getStore().getAt(rowIndex).data;
					if(rd.content!=""){
						var win = new Ext.Window({
							title: lang('viewTaskEvent'),
							layout: "fit",
							width: 400,
							height: 260,
							modal: true,
							maximizable: true,
							items: [
								{
									xtype: "panel",
									border: false,
									autoScroll: true,
									bodyStyle: "padding:10px;",
									html: rd.content.replace(/\r\n/ig,"<br />").replace(/\n/ig,"<br />")
								}
							],
							buttons : [
								{
									text : lang('close'),
									iconCls: 'icon-dialog-cancel',
									handler : function() {
										win.close();
									}.createDelegate(this)
								}
							]
						}).show();
					}
				}
			}
		});
		
		/**
		 * 讨论区
		 */
		/*
		this.fieldsDiscuss = [
			{name:"id"},
			{name:"fid"},
			{name:"title"},
			{name:"content"},
			{name:"user"},
			{name:"posttime"}
		];
		this.storeDiscuss = new Ext.data.GroupingStore({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getDiscussList"}),
			//proxy:new Ext.data.HttpProxy({url: "a.txt"}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fieldsDiscuss})
		});
		this.colModelDiscuss = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', hidden: true},
			new Ext.grid.RowNumberer(),
			{header: lang('titleContent'), dataIndex: 'content', width: 594, menuDisabled: true, sortable: false, renderer : _this.renderDiscussContent.createDelegate(this)},
			{header: "发布人 / 时间", dataIndex: 'other', width: 160, sortable: false,resizable: false, menuDisabled: true, renderer : _this.renderDiscussOther.createDelegate(this)}
		]);
		this.gridDiscuss = new Ext.grid.GridPanel({
			ds: this.storeDiscuss,
			//hideHeaders: true,
			loadMask : {msg: lang('waiting')},
			cm: this.colModelDiscuss,
			hideBorders: true,
			border: false,
			autoHeight: true,
			listeners:{  
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var rd = grid.getStore().getAt(rowIndex).data;
					var win = new Ext.Window({
						title: "查看任务讨论详细",
						layout: "fit",
						width: 726,
						height: makeWindowHeight(520),
						modal: true,
						id: "CNOA_USER_PLAN_TASK_DISCUSS_VIEW",
						maximizable: true,
						autoLoad: {url: _this.baseUrl+"&ajax=1&parentid=CNOA_USER_PLAN_TASK_DISCUSS_VIEW&task=loadPage&from=discussview&did="+rd.id+"&ttitle="+_this.title, scripts: true, scope: this, nocache: true}
					}).show();
				}
			},
			tbar : new Ext.Toolbar({
				//style: 'border-left-width:1px;',
				items: [
					{
						handler : function(button, event) {
							_this.storeDiscuss.load();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},'-',{
						handler : function(button, event) {
							CNOA_user_task_discussAddEdit = new CNOA_user_task_discussAddEditClass("add");
						}.createDelegate(this),
						iconCls: 'icon-utils-s-add',
						text : '发表讨论'
					}
				]
			})
		});
		*/
		
		//子任务区
		this.fields = [
			{name:"tid"},
			{name:"title"},
			{name:"eenable"},		//是否可编辑
			{name:"denable"},		//是否可删除
			{name:"repealenable"},	//是否可撤销
			{name:"urgeenable"},	//是否可督办
			{name:"failenable"},	//是否可失败
			{name:"status"},
			{name:"progress"},
			{name:"execman"},
			{name:"postter"},
			{name:"stime"},
			{name:"etime"},
			{name:"statusText"},
			{name:"statusColor"}
		];
		
		this.storeChild = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		
		this.storeChild.load();
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "tid", dataIndex: 'tid', hidden: true},
			{header: lang('taskTitle'), dataIndex: 'title', width: 140, sortable: true, menuDisabled :true},
			{header: lang('status'), dataIndex: 'status', width: 100, sortable: false,menuDisabled :true,renderer: this.makeStatus.createDelegate(this)},
			{header: lang('principal'), dataIndex: 'execman', width: 90, sortable: false,menuDisabled :true},
			{header: lang('bzr'), dataIndex: 'postter', width: 90, sortable: false,menuDisabled :true},
			{header: lang('startTime'), dataIndex: 'stime', width: 90, sortable: false,menuDisabled :true},
			{header: lang('endTime'), dataIndex: 'etime', width: 90, sortable: false,menuDisabled :true},
			{header: lang('opt'),dataIndex: '',width: 60, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.gridChild = new Ext.grid.GridPanel({
			store: this.storeChild,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			autoHeight: true,
			stripeRows : true,
			tbar : new Ext.Toolbar({
				items: [
					{
						handler : function(button, event) {
							_this.refreshChildTaskList();
							//_this.storeChild.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},{
						handler : function(button, event) {
							if(_this.from == "mothertask"){
								//CNOA.msg.alert("此任务已是子任务,不能再布置");
								CNOA.msg.alert(lang('zTaskNoBZ'));
							}else{
								_this.addTask();
							}
						}.createDelegate(this),
						iconCls: 'icon-utils-s-add',
						text : lang('bzZTask')
					}
				]
			})
		});
		
		/**
		 * 文档区
		 */
		this.fieldsDocument = [
			{name:"id"},
			{name:"tid"},
			{name:"attach"},
			{name:"content"},
			{name:"user"},
			{name:"posttime"}
		];
		this.storeDocument = new Ext.data.GroupingStore({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getDocumentList"}),
			//proxy:new Ext.data.HttpProxy({url: "a.txt"}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fieldsDocument})
		});
		this.colModelDocument = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', hidden: true},
			new Ext.grid.RowNumberer(),
			{header: lang('document'), dataIndex: 'attach', width: 300, menuDisabled: true, sortable: false},
			{header: lang('remark'), dataIndex: 'content', width: 294, menuDisabled: true, sortable: false, renderer : _this.renderDocumentContent.createDelegate(this)},
			{header: lang('postter')+" / "+lang('time'), dataIndex: '', width: 160, sortable: false,resizable: false, menuDisabled: true, renderer : _this.renderDocumentOther.createDelegate(this)}
		]);
		this.gridDocument = new Ext.grid.GridPanel({
			stripeRows : true,
			ds: this.storeDocument,
			//hideHeaders: true,
			loadMask : {msg: lang('waiting')},
			cm: this.colModelDocument,
			hideBorders: true,
			border: false,
			autoHeight: true,
			listeners:{  
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					if((columnIndex == 3) || (columnIndex == 4)){
						var rd = grid.getStore().getAt(rowIndex).data;
						if(rd.content!=""){
							var win = new Ext.Window({
								title: lang('viewDetailDocument'),
								layout: "fit",
								width: 400,
								height: 260,
								modal: true,
								maximizable: true,
								items: [
									{
										xtype: "panel",
										border: false,
										autoScroll: true,
										bodyStyle: "padding:10px;",
										html: rd.content.replace(/\r\n/ig,"<br />").replace(/\n/ig,"<br />")
									}
								],
								buttons : [
									{
										text : lang('close'),
										iconCls: 'icon-dialog-cancel',
										handler : function() {
											win.close();
										}.createDelegate(this)
									}
								]
							}).show();
						}
					}
				}
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						handler : function(button, event) {
							_this.storeDocument.load();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},{
						handler : function(button, event) {
							_this.showDocumentAddWindow();
						}.createDelegate(this),
						iconCls: 'icon-file-upload',
						text : lang('uploadDocument')
					}
				]
			})
		});
		
		this.tabItems = new Array();
		this.tabItems.push({
			xtype: "panel",
			title: lang('baseInfo'),
			autoHeight: true,
			items: [this.infoPanelBody],
			listeners : {
				
			}
		});
		
		this.tabItems.push({
			xtype: "panel",
			title: lang('taskEvent'),
			autoHeight: true,
			items: [this.gridEvent],
			listeners : {
				activate : function(){
					if(_this.tab_event_loadCount < 1){
						_this.storeEvent.load();
					}
					_this.tab_event_loadCount ++;
				}.createDelegate(this)
			}
		});
		
		if(_this.from != "mothertask"){
			this.tabItems.push({
				xtype: "panel",
				title: lang('subtaskArea'),
				autoHeight: true,
				items: [this.gridChild],
				listeners : {
					activate : function(){
						if(_this.tab_discuss_loadCount < 1){
							_this.storeChild.load();
						}
						_this.tab_discuss_loadCount ++;
					}.createDelegate(this)
				}
			});
		}
		
		this.tabItems.push({
			xtype: "panel",
			title: lang('taskDocumentArea'),
			autoHeight: true,
			items: [this.gridDocument],
			listeners : {
				activate : function(){
					if(_this.tab_document_loadCount < 1){
						_this.storeDocument.load();
					}
					_this.tab_document_loadCount ++;
				}.createDelegate(this)
			}
		});
		
		this.tabPanel = new Ext.TabPanel({
			width: 770,
			border: false,
			activeTab: 0,
			autoHeight: true,
			bodyStyle: "margin-bottom:100px;",
			items: this.tabItems
		});

		this.centerPanel = new Ext.Panel({
			region: "center",
			autoScroll: true,
			bodyStyle: "padding:5px",
			items: [this.infoPanel, this.tabPanel],
			tbar : new Ext.Toolbar({
				//style: 'border-left-width:1px;',
				items: [
					"<span style='font-weight:bold;margin-right:20px;'>"+lang('task2')+" - "+lang('viewTask')+"</span>",
					{
						handler : function(button, event) {
							_this.goToList();
						}.createDelegate(this),
						iconCls: 'icon-btn-arrow-left',
						text : lang('backToList')
					},
					{
						handler : function(button, event) {
							_this.reloadInfo();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},
					//关闭
					{
						text: lang('close'),
						iconCls: 'icon-dialog-cancel',
						handler: function() {
							_this.closeTab();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 67}
				]
			})
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
	
	//生成gridChild区域的相关列表内容
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
		
		var	h = "<a href='javascript:void(0);' onclick='CNOA_user_task_viewWatch.viewTask("+rd.tid+");'>"+lang('view')+"</a>";
		return h;
		
	},
	
	viewTask : function(tid){
		mainPanel.closeTab("CNOA_MENU_USER_TASK_VIEW");
		mainPanel.loadClass("index.php?app=user&func=task&action=default&task=loadPage&from=view&tid="+tid, "CNOA_MENU_USER_TASK_VIEW", lang('viewTask'), "icon-page-view");
	},
	
	addTask : function(){
		var _this = this;
		
		var win = new Ext.Window({
			width: 700,
			height: makeWindowHeight(550),
			title: lang('addSubtask'),
			resizable: true,
			modal: true,
			layout: 'fit',
			id: "CNOA_user_task_viewWatch_addedit",
			autoLoad: {url: "index.php?app=user&func=task&action=default&ajax=1&parentid=CNOA_user_task_viewWatch_addedit&task=loadPage&from=taskviewWatch_add&tid="+_this.tid, scripts: true, scope: this, nocache: true}		
		}).show();
	},
	
	refreshChildTaskList : function(){
		var _this = this;
		
		_this.storeChild.reload();
	},
	
	//生成gridEvent区域的相关列表内容
	renderEventType : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var co = "#00000";

		if(rd.type == 1){
			co = "#FF0000";
		}else if(rd.type == 2){
			co = "#804040";
		}else if(rd.type == 3){
			co = "#FF8040";
		}else if(rd.type == 4){
			co = "#FF00FF";
		}else if(rd.type == 5){
			co = "#008000";
		}else if(rd.type == 6){
			co = "#408080";
		}else if(rd.type == 7){
			co = "#0080FF";
		}else if(rd.type == 8){
			co = "#8000FF";
		}else if(rd.type == 9){
			co = "#FF0000";
		}else if(rd.type == 10){
			co = "#2BB7D5";
		}else if(rd.type == 11){
			co = "#808080";
		}else if(rd.type == 12){
			co = "#0080C0";
		}
		var h = "<span style='color:#FFF;display:block;width:30px;height:16px;background-color:"+co+";text-indent:3px;'>"+rd.typename+"</span>";
		return h;
	},
	renderEventContent : function(value, cellmeta, record){ 
		var rd = record.data;
		var h  = rd.title+"<br />";
			h += "<span style='color:#8C8C8C'>"+value.substr(0,150)+"</span>";
		//cellmeta.attr = 'style="white-space:normal;"'; 
		return h; 
	},
	renderEventOther : function(value, cellmeta, record){
		var rd = record.data;
		var h  = rd.user+"<br />";
			h += "<span style='color:#8C8C8C'>"+rd.posttime+"</span>";
		return h; 
	},
	
	//生成gridDiscuss区域的相关列表内容
	renderDiscussContent : function(value, cellmeta, record){ 
		var rd = record.data;
		var h  = rd.title+"<br />";
			h += "<span style='color:#8C8C8C'>"+value.substr(0,150)+"</span>";
		//cellmeta.attr = 'style="white-space:normal;"'; 
		return h; 
	},
	renderDiscussOther : function(value, cellmeta, record){
		var rd = record.data;
		var h  = rd.user+"<br />";
			h += "<span style='color:#8C8C8C'>"+rd.posttime+"</span>";
		return h; 
	},
	renderDocumentContent : function(value, cellmeta, record){ 
		var rd = record.data;
		var a = "";
		if(value !== ""){
			var a = "<br /><span style='color:#00006F'>("+lang('clickToView')+")</span>";
		}
		var h  = "<span style='color:#676767'>"+value.substr(0,150)+"</span>"+a;
		return h; 
	},
	renderDocumentOther : function(value, cellmeta, record){
		var rd = record.data;
		var h  = rd.user+"<br />";
			h += "<span style='color:#8C8C8C'>"+rd.posttime+"</span>";
		return h; 
	},
	
	//重新刷新整个内容
	reloadInfo : function(){
		//刷新基本信息
		this.closeTab();
		mainPanel.loadClass("index.php?app=user&func=task&action=default&task=loadPage&from=view&tid="+this.tid, "CNOA_MENU_USER_TASK_VIEW", lang('viewTask'));
	},

	readInfo : function(){
		var _this = this;
		
		_this.centerPanel.getEl().mask(lang('waiting'));
		
		Ext.Ajax.request({  
			url: this.baseUrl + "&task=viewTask",
			method: 'GET',
			params: {},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					var rd = result.data;
					rd.showTip = rd.showTip ? "block" : "none";

					_this.tplTitle.overwrite(_this.infoPanel.body, rd);
					
					_this.tplBody.overwrite(_this.infoPanelBody.body, rd);
					
					_this.dealJob(rd);
					
					//更新任务状态
					_this.status = rd.status;
					
					//更新标题
					_this.title = rd.title;

					//更新结束时间
					_this.etime = rd.etime;
					
					_this.centerPanel.getEl().unmask();
					
					//更新事件列表
					_this.storeEvent.reload();
				}else{
					CNOA.msg.alert(result.msg, function(){
						_this.closeTab();
					});
				}
			}
		});
	},

	goToList : function(){
		mainPanel.loadClass("index.php?app=user&func=task&action=watch&task=loadPage&from=list", "CNOA_MENU_USER_TASK_WATCH", lang('taskList'));
	},

	closeTab : function(){
		mainPanel.closeTab(CNOA.user.task.viewWatch.parentID.replace("docs-", ""));
	},

	//XTemplate渲染完成后处理后续事务
	dealJob : function(rd){
		var _this = this;

		var id_job = "CNOA_task_view_btn_job";
		var id_opt = "CNOA_task_view_btn_operate";

		var btnNum = 0;

		//生成操作区按钮
		if (rd.eenable){
			btnNum ++;
			new Ext.Button({
				text: lang('editTask'),
				renderTo: id_opt,
				handler: function(){
					_this.editTask();
				}
			});
		}
		if (rd.denable){
			btnNum ++;
			new Ext.Button({
				text: lang('delTask'),
				id: _this.ID_btn_delete,
				renderTo: id_opt,
				handler: function(){
					_this.deleteTask();
				}
			});
		}
		if (rd.renable){
			btnNum ++;
			new Ext.Button({
				text: lang('recellTask'),
				renderTo: id_opt,
				id: _this.ID_btn_repeal,
				handler: function(){
					_this.repealTask();
				}
			});
		}
		if (rd.uenable){
			btnNum ++;
			new Ext.Button({
				text: lang('dbTask'),
				renderTo: id_opt,
				handler: function(){
					_this.urgeTask();
				}
			});
		}
		if (rd.uenable){
			btnNum ++;
			new Ext.Button({
				text: lang('delayTask'),
				renderTo: id_opt,
				handler: function(){
					_this.delayTask();
				}
			});
		}
		if (rd.fenable){
			btnNum ++;
			new Ext.Button({
				text: lang('failTask'),
				renderTo: id_opt,
				id: _this.ID_btn_fail,
				handler: function(){
					_this.failTask();
				}
			});
		}
		if (rd.aenable){
			btnNum ++;
			new Ext.Button({
				text: lang('reportProgress'),
				renderTo: id_opt,
				handler: function(){
					_this.reportTask();
				}
			});
		}
		if (rd.benable){
			btnNum ++;
			new Ext.Button({
				text: lang('finishTask'),
				renderTo: id_opt,
				id: _this.ID_btn_complete,
				handler: function(){
					_this.completeTask();
				}
			});
		}
		//生成提示区按钮
		// - 接收任务
		if (rd.tipJob == "receive"){
			new Ext.Button({
				text: lang('receiveTask'),
				renderTo: id_job,
				id: _this.ID_btn_accept,
				handler: function(){
					_this.acceptTask();
				}
			});
			new Ext.Button({
				text: lang('rejectReceive'),
				renderTo: id_job,
				id: _this.ID_btn_reject,
				handler: function(){
					_this.rejectTask();
				}
			});
		}
		// - 审批任务
		else if (rd.tipJob == "examapp"){
			new Ext.Button({
				text: lang('approvalAgree'),
				renderTo: id_job,
				id: _this.ID_btn_agree,
				handler: function(){
					_this.agreeTask();
				}
			});
			new Ext.Button({
				text: lang('disagree'),
				renderTo: id_job,
				id: _this.ID_btn_disagree,
				handler: function(){
					_this.disagreeTask();
				}
			});
		}
		// - 审核任务
		else if (rd.tipJob == "check"){
			new Ext.Button({
				text: lang('agreeTaskComplete'),
				renderTo: id_job,
				id: _this.ID_btn_finish,
				handler: function(){
					_this.finishTask();
				}
			});
			new Ext.Button({
				text: lang('disagree'),
				renderTo: id_job,
				id: _this.ID_btn_nofinish,
				handler: function(){
					_this.nofinishTask();
				}
			});
		}
		// - 任务被催促
		else if (rd.tipJob == "viewedurge"){
			new Ext.Button({
				text: lang('gotIt'),
				renderTo: id_job,
				id: _this.ID_btn_viewedurge,
				handler: function(){
					_this.viewedurge();
				}
			});
		}

		if(btnNum > 0){
			Ext.fly("CNOA_task_view_btn_operate_Ct").show();
		}
	},
	
	//编辑任务
	editTask : function(){
		mainPanel.closeTab("CNOA_MENU_USER_TASK_ADDEDIT");
		mainPanel.loadClass("index.php?app=user&func=task&action=default&task=loadPage&from=addedit&job=edit&tid="+this.tid, "CNOA_MENU_USER_TASK_ADDEDIT", lang('editTask'));
	},
	
	//删除任务
	deleteTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_delete).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmDelTask'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.doAjax("delete", {}, function(){
						_this.closeTab();
					});
				}
			}.createDelegate(this)
		});
	},
	
	//撤销任务
	repealTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_repeal).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmToRecell'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.makeMiniWindow(lang('recellTask'), lang('recellContent'), "repeal");
				}
			}.createDelegate(this)
		});
	},

	failTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_fail).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmFailTask'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.makeMiniWindow(lang('failTask'), lang('failReason'), "fail");
				}
			}.createDelegate(this)
		});
	},
	
	//汇报进度
	reportTask : function(){
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
					xtype: "panel",
					layout: 'table',
	            	layoutConfig: {
						columns: 2
					},
					items: [
						{
							xtype: "panel",
							border: false,
							layout: "form",
							width: 150,
							items: [
								{
									xtype: "textfield",
									width: 70,
									emptyText: "0-100",
									allowBlank: false,
									vtype: "num0_100",
									vtypeText: lang('only0to100'),
									fieldLabel: lang('taskProgress')+"(%)",
									name: "progress"
								}
							]
						},
						{
							xtype: "panel",
							html: "<span style='font-size:18px;font-family:\'Arial\''>%</span>",
							border: false
						}
					]
				},
				{
					xtype: "textarea",
					//width: 288,
					//height: 130,
					anchor: "-20 -45",
					name: "content",
					fieldLabel: lang('progressDes')
				}
			]
		});
		var win = new Ext.Window({
			width: 400,
			height: 270,
			title: lang('reportTaskProgress'),
			resizable: true,
			modal: true,
			layout: "fit",
			items: formPanel,
			buttons : [
				//汇报
				{
					text : lang('report'),
					id: _this.ID_btn_report_window_report,
					iconCls: 'icon-order-s-accept',
					handler : function() {
						submitReport();
					}.createDelegate(this)
				},
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

		var submitReport = function(){
			if (formPanel.getForm().isValid()) {
				var f = formPanel.getForm();
				var p = f.findField("progress").getValue();
				var c = f.findField("content").getValue();

				_this.doAjax("report", {progress: p, content: c});
				
				win.close();
			}else{
				var position = Ext.getCmp(_this.ID_btn_report_window_report).getEl().getBox();
					position = [position['x']+35, position['y']+26];

				CNOA.miniMsg.alert(lang('formValid'), position);
			}
		};
	},
	
	//提交审核(任务做完)
	completeTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_complete).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmFinishTask'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.makeMiniWindow(lang('confirmFinishTask2'), lang('taskSummary'), "complete");
				}
			}.createDelegate(this)
		});
	},
	
	//接收任务
	acceptTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_accept).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmReceiveTask'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.doAjax("accept");
				}
			}.createDelegate(this)
		});
	},
	
	//拒绝接收任务
	rejectTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_reject).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmRejectTask'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.makeMiniWindow(lang('rejectTask'), lang('rejectReason'), "reject");
				}
			}.createDelegate(this)
		});
	},
	
	//同意审批
	agreeTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_agree).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmAgreeSPTask'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					//_this.doAjax("agree");
					_this.makeMiniWindow(lang('tyTaskZX'), lang('yjjy'), "agree");
				}
			}.createDelegate(this)
		});
	},
	
	//不同意审批
	disagreeTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_disagree).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmDisagreeTaskSP'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.makeMiniWindow(lang('approvalTaskDisagree'), lang('dontAgreeReason'), "disagree");
				}
			}.createDelegate(this)
		});
	},
	
	//通过审核(同意任务完成)
	finishTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_finish).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('agreeTaskComplete2'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.makeMiniWindowForFinish(lang('agreeTaskComplete'), lang('taskOpinion'), "finish");
				}
			}.createDelegate(this)
		});
	},
	
	//审核不通过(不同意任务完成)
	nofinishTask : function(){
		var _this = this;

		var position = Ext.getCmp(_this.ID_btn_nofinish).getEl().getBox();
			position = [position['x']+32, position['y']+26];

		CNOA.miniMsg.show({
			msg: lang('confirmUngreeTaskComplete'),
			xy: position,
			modal: false,
			fn: function(btn) {
				if (btn == 'confirm') {
					_this.makeMiniWindow(lang('dontAgree'), lang('dontAgreeReason'), "nofinish");
				}
			}.createDelegate(this)
		});
	},

	viewedurge : function(){
		var _this = this;

		Ext.Ajax.request({
			url: this.baseUrl + "&task=operateTask",
			method: 'POST',
			params: {tid: _this.tid, job: "viewedurge", status: _this.status},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					_this.readInfo();
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	},

	urgeTask : function(tid, status){
		var _this = this;
		CNOA.msg.cf(lang('confirmToDBTask'), function(btn){
			if(btn == "yes"){
				//_this.makeMiniWindow("督办任务", "督办内容", "urge", tid, status);
				_this.makeMiniWindow(lang('dbTask'), lang('dbContent'), "urge");
			}
		});
		
	},

	delayTask : function(){
		var _this = this;
		CNOA.msg.cf(lang('confirmDelayTask'), function(btn){
			if(btn == "yes"){
				_this.makeDelayWindow(lang('delayTask'), "delay", _this.tid, _this.status, _this.etime);
			}
		});
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
			_this.doAjax(job, {newtime: newtime});
		}
	},

	//private - 处理所有按钮事务
	doAjax : function(job, other, callback){
		var _this = this;
		var params;
		
		if(Ext.isObject(other)){
			params = other;
			params.tid = _this.tid;
			params.job = job;
			params.status = _this.status;
		}else{
			params = {tid: _this.tid, job: job, status: _this.status}
		}
		
		Ext.Ajax.request({  
			url: this.baseUrl + "&task=operateTask",
			method: 'POST',
			params: params,
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice2(result.msg);
					try {
						callback.call(this);
					} catch (e) {
						_this.readInfo();
					}
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	},
	
	makeMiniWindow : function(title, label, job, callback){

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
					html: "",
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
			_this.doAjax(job, {content: c}, callback);
		}
	},
	
	makeMiniWindowForFinish : function(title, label, job, callback){
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
					xtype: "panel",
					layout: 'table',
	            	layoutConfig: {
						columns: 2
					},
					items: [
						{
							xtype: "panel",
							border: false,
							layout: "form",
							width: 150,
							items: [
								{
									xtype: "textfield",
									width: 70,
									emptyText: "0-100",
									allowBlank: false,
									vtype: "num0_100",
									vtypeText: lang('only0to100'),
									fieldLabel: lang('taskRatings'),
									name: "point"
								}
							]
						},
						{
							xtype: "panel",
							html: lang('point'),
							border: false
						}
					]
				},{
					xtype: "textarea",
					anchor: "-20 -45",
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
			var p = formPanel.getForm().findField("point").getValue();
			_this.doAjax(job, {content: c, point: p}, callback);
		}
	},
	
	/* 显示文档上传窗口 */
	showDocumentAddWindow : function(){
		var _this = this;

		var ID_window = Ext.id();

		//添加短信组件
		this.smsSender = new Ext.form.SMSSender({
			from: "task",
			//modal: true,
			//fwindow: ID_window,
			fieldLabel: lang('smsNotice')
		});

		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			autoScroll: true,
			bodyStyle: "padding-top:5px;",
			items: [
				{
					xtype: "flashfile",
					fieldLabel: lang('document'),
					name: "files",
					inputPre: "filesUpload"
				},
				{
					xtype: "textarea",
					width: 290,
					height: 146,
					name: "content",
					fieldLabel: lang('remark')
				}
			]
		});
		
		var win = new Ext.Window({
			id: ID_window,
			width: 400,
			height: 270,
			title: lang('uploadDocument'),
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
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: _this.baseUrl+"&task=uploadDocument",
					waitTitle: lang('notice'),
					method: 'POST',
					waitMsg: lang('waiting'),
					//params: {task : this.action, id : this.edit_id},
					success: function(form, action) {
						//提交手机短信
						try{
							_this.smsSender.submit();
						}catch(e){}
						_this.storeDocument.reload();
						CNOA.msg.notice2(action.result.msg);
						win.close();
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							
						}.createDelegate(this));
					}.createDelegate(this)
				});
			}
		}
	}
}
