
var CNOA_user_task_totalDetailClass, CNOA_user_task_totalDetail;

CNOA_user_task_totalDetailClass = CNOA.Class.create();
CNOA_user_task_totalDetailClass.prototype = {
	init : function(){
		var _this = this;
		
		this.ID_title_text = Ext.id();
		this.status = CNOA.user.task.totalDetail.status;
		this.execman = CNOA.user.task.totalDetail.execman;
		this.uname = CNOA.user.task.totalDetail.uname;

		this.baseUrl = "index.php?app=user&func=task&action=total";
		
		this.fields = [
			{name:"tid"},
			{name:"from"},
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
		
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getTaskDetailList&status="+this.status+"&execman="+this.execman, disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});
		this.store.load();
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "tid", dataIndex: 'tid', hidden: true},
			{header: lang('taskTitle'), dataIndex: 'title', width: 300, sortable: true, menuDisabled :true},
			{header: lang('status'), dataIndex: 'status', width: 150, sortable: false,menuDisabled :true, renderer: this.makeStatus.createDelegate(this)},
			{header: lang('principal'), dataIndex: 'execman', width: 100, sortable: false,menuDisabled :true},
			{header: lang('bzr'), dataIndex: 'postter', width: 100, sortable: false,menuDisabled :true},
			{header: lang('startTime'), dataIndex: 'stime', width: 100, sortable: false,menuDisabled :true},
			{header: lang('endTime'), dataIndex: 'etime', width: 100, sortable: false,menuDisabled :true},
			//{header: lang('opt'),dataIndex: '',width: 100},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			
			   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					
				}
			}
		});
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			region: "center",
			layout: 'fit',
			scriptRows: true,
			tbar: [
				new Ext.BoxComponent({
					id: this.ID_title_text,
				    autoEl: {
				        tag: 'div',
				        style: 'font-weight:bold;color:#535353;',
				        //html: "<span style=\"color: #FF8408;font-weight:bold;\">["+this.uname+"]</span>"+'的任务列表'
				        html: lang('whosTaskList', "<span style=\"color: #FF8408;font-weight:bold;\">["+this.uname+"]</span>")
				    }
				}),
				{
					handler : function(button, event) {
						_this.store.reload();
					}.createDelegate(this),
					iconCls: 'icon-system-refresh',
					text : lang('refresh')
				}
			],
			bbar: this.pagingBar
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items : [this.grid]
		});
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
	}
}