var CNOA_monitor_mgr_planViewClass, CNOA_monitor_mgr_planView;
CNOA_monitor_mgr_planViewClass = CNOA.Class.create();
CNOA_monitor_mgr_planViewClass.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = "index.php?app=monitor&func=mgr&action=plan";
		this.pid     = CNOA.monitor.mgr.plan.view.pid;
		this.status  = 0;

		var fields = [
			{name :"tid"},
			{name :"title"},
			{name : "status"},
			{name : "execman"},
			{name : "statusText"},
			{name : "statusColor"},
			{name : "progress"},
			{name:"stime"},
			{name:"etime"}
		];
		
		//部门计划任务列表
		this.planTaskGridStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({url: _this.baseUrl + "&task=getPlanTaskList&pid=" + this.pid}),
			reader: new Ext.data.JsonReader({root:'data', fields: fields})
		});
		
		var renOpeare = function(value){
			return "<a href='javascript:void(0);' onclick='CNOA_monitor_mgr_planView.viewTask("
					+ value + ");'>" + lang('view') + "</a>";
		};
		var colModel = new Ext.grid.ColumnModel({
			defaults: {sortable: false, menuDisabled: true},
			columns: [
				new Ext.grid.RowNumberer(),
				{header: "tid", dataIndex: 'tid', hidden: true},
				{header: lang('taskName'), dataIndex: 'title', width: 200},
				{header: lang('status'), dataIndex: 'status', width: 130, renderer: this.makeStatus},
				{header: lang('principal'), dataIndex: 'execman', width: 110},
				{header: lang('startTime'), dataIndex: 'stime', width: 100},
				{header: lang('endTime'), dataIndex: 'etime', width: 100},
				{header: lang('opt'), dataIndex: 'tid', width: 70, renderer: renOpeare}
			]
		});
		
		this.planTaskGrid = new Ext.grid.GridPanel({
			stripeRows : true,
			autoScroll: true,
			border: false,
			autoWidth: true,
			autoHeight: true,
			loadMask : lang('waiting'),
			cm: colModel,
			store: this.planTaskGridStore,
			tbar : new Ext.Toolbar({
				items: [
					lang('taskInPlan')
				]
			})
		});
		
		this.planTaskPanel = new Ext.Panel({
			width: 775,
			bodyStyle: "margin:5px;margin-bottom:25px;",
			border: false,
			hidden: true,
			items: [this.planTaskGrid]
		});
		
		this.reportPanel = new Ext.Panel({
			border: false,
			html: lang('nodata')
		});

		this.planreportField = [
			{
				xtype: "fieldset",
				title: lang('planOfLeader'),
				style: "margin:5px",
				width: 770,
				height: 350,
				collapsible: true,
				autoHeight: true,
				items: [this.reportPanel]
			}
		]
		
		this.commentPanel = new Ext.Panel({
			border: false,
			html: lang('nodata')
		});

		this.plancommentField = [
			{
				xtype: "fieldset",
				title: lang('commentOfPlan'),
				style: "margin:5px",
				width: 770,
				height: 350,
				collapsible: true,
				autoHeight: true,
				items: [this.commentPanel]
			}
		];
		
		this.infoPanel = new Ext.Panel({
			border: false,
			bodyStyle: "padding:5px",
			listeners: {
				render : function(){
					//载入基本资料
					_this.readInfo(_this.pid);
					//载入评论数据
					_this.loadComment(_this.pid);
				}
			}
		});

		this.mainPanel = new Ext.Panel({
			region: "center",
			autoScroll: true,
			border: false,
			items: [this.infoPanel, this.planTaskPanel, this.planreportField, this.plancommentField],
			tbar: new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : lang('refresh'),
						handler : function(button, event) {
							_this.refresh();
						}
					},'-',
					{
						text: lang('close'),
						iconCls: 'icon-dialog-cancel',
						handler: function() {
							mainPanel.closeTab('CNOA_MENU_USER_PLAN_VIEW');
						}
					}
				]
			})
		});
	},
	
	makeStatus : function(value, cellmeta, record){
		var rd = record.data;
		var conHTML  = "<div style='color:#808080;margin-top:3px;'>" + rd.statusText + "</div>"
					 + "<div style='width:100px;height:5px;background-color:#D4D4D4;'>"
					 + "<div style='font-size:0;line-height:0;height:5px;width:" + rd.progress 
					 + "px;background-color:" + rd.statusColor + "'></div></div>";

		return conHTML;
	},
	
	readInfo : function(pid){
		var me = this;
		Ext.Ajax.request({  
			url: me.baseUrl + "&task=loadPlanInfo",
			method: 'POST',
			params: {pid: pid, comefrom: this.from},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				//console.log(result);
				if (result.success === true){
					var rd = result.data;
					me.getPlanInfoTpl().overwrite(me.infoPanel.body, rd);
					
					//显示计划任务列表 - 如果是部门计划
					if(rd.plantype == 2){
						me.planTaskPanel.show();
						me.planTaskPanel.doLayout();
						me.planTaskGridStore.load();
					}
					
				} else {
					CNOA.msg.alert(result.msg, function(){
						CNOA_USER_PLAN_TAB_CLOSE("view");
					});
				}
			}
		});
	},

	refresh : function(){
		mainPanel.closeTab('CNOA_MENU_USER_PLAN_VIEW');
		var url = "index.php?app=monitor&func=mgr&action=plan&task=loadPage&from=view&pid="+this.pid+"&comefrom="+this.from;
		mainPanel.loadClass(url, 'CNOA_MENU_USER_PLAN_VIEW', lang('viewTask'), "icon-page-view");
	},
	
	viewTask : function(tid){
		mainPanel.closeTab("CNOA_MONITOR_TASK_VIEW");
		mainPanel.loadClass("index.php?app=monitor&func=mgr&action=plan&task=loadPage&from=viewTask&tid="+tid, "CNOA_MONITOR_TASK_VIEW", lang('viewTask'), "icon-page-view");
	},

	loadComment : function(pid){
		var me = this;
		Ext.Ajax.request({  
			url: me.baseUrl + '&task=loadPlanComment',
			method: 'POST',
			params: {pid: pid, from: "comment"},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				var comment = result.data.comment,
					report = result.data.report;
					
				if (comment){
					comment.title = lang('planComment');
					me.getCommentTpl().overwrite(me.commentPanel.body, comment);
				}
				if (report){
					report.title = lang('planLeadSay');
					me.getCommentTpl().overwrite(me.reportPanel.body, report);
				}
			}
		});
	},
	
	getCommentTpl: function(){
		return new Ext.XTemplate(
			'<div style="border-width:0px;background-color:#F5F5F5">',
			'	<div style="border:2px solid #E6E6E6;border-bottom-width:0px;height:auto;">',
			'		<div style="height:25px;background-color:#E6E6E6;line-height:25px;">',
			'			<span style="font-size:12px;font-weight:bold;">{title}</span>({totalCount}'+lang('tiao')+')',
			'		</div>',
			'	   <tpl if="totalCount &gt; 0">',
			'		<ul style="margin-top:10px;">',
			'			<tpl for="list">',
			'			<li style="border-bottom:1px solid #DDDDDD;margin:2px 0;background-color:{[xindex % 2 === 0 ? "#FFF" : "##F6F6F6"]}">',
			'				<div style="height:160px;width:120px;float:left;border:1px solid #D8D8D8;margin:3px 5px 5px 5px;"><img src="{face}" onerror="this.src=\'./resources/images/empty_photo.png\'" style="background-color:#FFF;"></div>',
			'				<div style="float:left;width:590px;"><div style="height:22px;line-height:22px;border-bottom:1px solid #DDDDDD;">#{[xindex]} '+lang('postter')+':<a  style="font-weight:bold;">{user}</a>&nbsp;&nbsp;&nbsp;'+lang('inDepartment')+':<a style="font-weight:bold;">{dept}</a>&nbsp;&nbsp;&nbsp;'+lang('posttime2')+':{posttime}</div>',
			'				<div style="padding:10px;">{content}</div></div>',
			'				<div style="clear:both;"></div>',
			'			</li>',
			'			</tpl>',
			'		</ul>',
			'	</div>',
			'	</tpl>',
			'</div>'
		);
	},
	
	getPlanInfoTpl:  function(){
		return new Ext.XTemplate(
			'<div style="width:770px;height:40px;background-color:#FEFBDC;font-size:16px;font-weight:bold;line-height:40px; text-align: center;">[{plantypeName}]{title}',
			'</div>',
			'<div style="margin-top:5px;margin-bottom:5px;width:770px;">',
			'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('baseInfo2')+'</span></div>',
			'	<div style="background-color:#EFF5FF;padding-bottom:5px;">',
			'		<ul class="user_task_view_ul">',
			'			<li><span class="first">'+lang('planType')+': </span><span class="second" style="color:#800000;">{plantypeName}</span></li>',
			'			<li><span class="first">'+lang('planer')+': </span><span class="second">{planer}</span></li>',
			'			<li><span class="first">'+lang('planStatus')+': </span><span class="second">{statusText}</span></li>',
			'			<li><span class="first">'+lang('startTime')+': </span><span class="second">{stime}</span></li>',
			'			<li><span class="first">'+lang('endTime')+': </span><span class="second">{etime}</span></li>',
			'			<li>&nbsp;</li>',
			'		</ul>',
			'		<div class="clear"></div>',
			'	</div>',
			'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('content')+'</span></div>',
			'	<div style="padding:20px;background-color:#EFF5FF;">',
			'	<tpl for="contentList">',
			'	  <div style="border-bottom:1px dashed #969AA7;margin:10px 0;line-heigth:20px;">{c}</div>',
			'	</tpl>',
			'	</div>',
			'	<div id="user_plan_view_summary"><div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('planSummary')+'</span></div>',
			'	<div style="padding:20px;background-color:#EFF5FF;">{summary}</div></div>',
			'	<tpl if="attachCount &gt; 0">',
			'	<div style="background-color:#D5E7F3;height:30px;line-height:30px;"><span style="margin-left:5px;font-weight:bold;font-size:12px;">'+lang('attach')+'</span></div>',
			'	<div style="padding:20px;background-color:#EFF5FF;">',
			'		{attach}',
			'	</div>',
			'	</tpl>',
			'</div>'
		);
	}
}