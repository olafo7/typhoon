//定义全局变量：
var CNOA_monitor_mgr_planClass, CNOA_monitor_mgr_plan;
CNOA_monitor_mgr_planClass = CNOA.Class.create();
CNOA_monitor_mgr_planClass.prototype = {
	init : function(){
		var _this = this;

		this.baseUrl = "index.php?app=monitor&func=mgr&action=plan";
		
		this.mode = 2;

		this.searchStore = {
			planer: 0,
			title: "",
			stime: 0,
			etime: 0,
			status: "",
			dateMode: ''
		};
		
		this.ID_Search_title	= Ext.id();
		this.ID_Search_stime	= Ext.id();
		this.ID_Search_etime	= Ext.id();
		this.ID_Search_status	= Ext.id();
		this.ID_Search_dateMode	= Ext.id();
		this.ID_Search_readed	= Ext.id();
		
		this.ID_ruler	= Ext.id();
		
		this.fields = [
			{name:'id'},
			{name:'title'},
			{name:'plantime'},
			{name:'plantype'},
			{name:'statusText'},
			{name:'dateMode'},
			{name:'updatetime'},
			{name:'readed'},
			{name:'planer'}
		];
		
		this.store = new Ext.data.Store({
			autoLoad: true,
			baseParams: this.searchStore,
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getPlanList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});

		var colModel = new Ext.grid.ColumnModel({
			defaults: {sortable: false, menuDisabled :true},
			columns: [
				new Ext.grid.RowNumberer(),
				{header: "id", dataIndex: 'id', hidden: true},
				{header: lang('planTitle'), dataIndex: 'title', width: 300, sortable: true},
				{header: lang('planTime'), dataIndex: 'plantime', width: 190},
				{header: lang('srcComeFrom'), dataIndex: 'plantype', width: 80},
				{header: lang('planStatus'), dataIndex: 'statusText', width: 70},
				{header: lang('planer'), dataIndex: 'planer', width: 80},
				{header: lang('reportTime'), dataIndex: 'updatetime', width: 111},
				{header: lang('opt'), dataIndex: 'id',width: 80,renderer: this.makeOperate},
				{header: "", dataIndex: 'noIndex', width: 1}
			]
		});
		
		var search_title = new Ext.form.TextField({width: 100}),
			search_screateTime = new Ext.form.DateField({format: "Y-m-d", width: 100}),
			search_ecreateTime = new Ext.form.DateField({format: "Y-m-d", width: 100}),
			search_staCom = new Ext.form.ComboBox({
				store: new Ext.data.SimpleStore({
					fields: ['value', 'status'],
					data: [['0', lang('inProgress')], ['1', lang('finished')], ['2', lang('delayeFinish')]]
				}),
				width: 80,
				hiddenName: 'status',
				valueField: 'value',
				displayField: 'status',
				mode: 'local',
				triggerAction: 'all',
				forceSelection: true,
				editable: false
			}),
			search_typeCom = new Ext.form.ComboBox({
				store: new Ext.data.SimpleStore({
					fields: ['value', 'display'],
					data: [['1', lang('weekPlan')], ['2', lang('monthPlan')], ['3', lang('yearReport')]]
				}),
				width: 80,
				hiddenName: 'dateMode',
				name: 'dateMode',
				valueField: 'value',
				displayField: 'display',
				mode: 'local',
				triggerAction: 'all',
				forceSelection: true,
				editable: false			
			});		
									
		
		this.grid = new Ext.grid.PageGridPanel({
			store: this.store,
			cm: colModel,
			stripeRows: true,
			viewConfig: {
				forceFit: true
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : lang('refresh'),
						handler : function(button, event) {
							_this.store.reload();
						}
					},
					'-',
					lang('reportTitle'),
					search_title,
					lang('createTime'),
					search_screateTime,
					lang('to'),
					search_ecreateTime,
					lang('status'),
					search_staCom,
					lang('planType'),
					search_typeCom,
					{
						xtype: "button",
						text: lang('search'),
						style: "margin-left:5px",
						cls: "x-btn-over",
						iconCls: 'icon-order-s-search',
						listeners: {
							"mouseout" : function(btn){
								btn.addClass("x-btn-over");
							}
						},
						handler: function(){
							_this.searchStore.title = search_title.getValue();
							_this.searchStore.stime = search_screateTime.getRawValue();
							_this.searchStore.etime = search_ecreateTime.getRawValue();
							_this.searchStore.status = search_staCom.getValue();
							_this.searchStore.dateMode = search_typeCom.getValue();
							
							_this.store.load();
						}
					},{
						xtype: "button",
						text: lang('clear'),
						style: "margin-left:5px",
						handler: function(){
							search_title.setValue();
							search_screateTime.setValue();
							search_ecreateTime.setValue();
							search_staCom.setValue();
							search_typeCom.setValue();
							
							_this.searchStore.title = '';
							_this.searchStore.stime = '';
							_this.searchStore.etime = '';
							_this.searchStore.status = '';
							_this.searchStore.readed = '';
							_this.searchStore.dateMode = '';
							_this.store.load();
						}
					}
				]
			})
		});
		
		function PostRuler(type){
			var ruler = Ext.getCmp(_this.ID_ruler).getRawValue();
			if(type == 0){
				ruler = '';
			}
			
			_this.store.setBaseParam('dateType', type);
			_this.store.setBaseParam('date', ruler);
			_this.store.load();
		}
		
		this.menuPanel = new Ext.Panel({
			items: [this.grid],
			layout: "fit",
			hideBorders: true,
			border: false,
			tbar : new Ext.Toolbar({
				items: [
					{
						enableToggle: true,
						pressed: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_dateMode",
						text: lang('allPlan'),
						handler : function(button, event) {
							PostRuler(0);
						}
					},'-',
					
					lang('date')+'：',
					{
						xtype: 'datefield',
						name: 'ruler',
						format: 'Y-m-d',
						editable: false,
						id: _this.ID_ruler,
						value: new Date()
					},
					{
						handler : function(button, event) {
							PostRuler(1);
							
						},
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_dateMode",
						text : lang('thisMonth')
					},
					{
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_dateMode",
						text : lang('thisWeek'),
						handler : function(button, event) {
							PostRuler(2);
						}
					},
					{
						handler : function(button, event) {
							PostRuler(3);
						},
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_dateMode",
						text : lang('prevMonth')
					},
					{
						handler : function(button, event) {
							PostRuler(4);
						},
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_dateMode",
						text : lang('prevWeek')
					},
					{
						handler : function(button, event) {
							PostRuler(5);
							
						},
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_dateMode",
						text : lang('daysUnread', 15)
					}
				]
				
			})
		});
		
		/**
		 * 主面板
		 */
		this.mainPanel = new Ext.Panel({
			autoScroll: true,
			layout: "fit",
			border: false,
			items: [this.menuPanel],
			tbar: new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-roduction',
						enableToggle: true,
						allowDepress: false,
						pressed: true,
						toggleGroup: "user_plan_report_plantype",
						text : lang('All'),
						handler : function(button, event) {
							_this.store.setBaseParam('plantype', '');
							_this.store.load();
							
						}
					},'-',
					{
						iconCls: 'icon-roduction',
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_plantype",
						text : lang('personPlan'),
						handler : function(button, event) {
							_this.store.setBaseParam('plantype', 1);
							_this.store.load();
						}
					},
					{
						iconCls: 'icon-roduction',
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_plantype",
						text : lang('deptPlan'),
						handler : function(button, event) {
							_this.store.setBaseParam('plantype', 2);
							_this.store.load();
							
						}
					},
					{
						iconCls: 'icon-roduction',
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_plantype",
						text : lang('workReport'),
						handler : function(button, event) {
							_this.store.setBaseParam('plantype', 3);
							_this.store.load();
							
						}
					},
					{
						iconCls: 'icon-roduction',
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "user_plan_report_plantype",
						text : lang('workSummary'),
						handler : function(button, event) {
							_this.store.setBaseParam('plantype', 4);
							_this.store.load();
							
						}
					}
				]
			})
		});
	},
	
	makeOperate : function(value){
		return "<a onclick='CNOA_monitor_mgr_plan.viewPlan("
				+ value +")'>" + lang('view') + "</a>" ;
	},

	showList : function(type){
		var _this = this;
		if (type == "share"){
			_this.mode = 1;
		} else if (type == "report"){
			_this.mode = 2;
		}
		_this.store.removeAll();
		_this.store.load({params:{type: _this.mode}});
	},
	
	//新标签中查看计划
	viewPlan : function(id){
		mainPanel.closeTab('CNOA_MENU_USER_PLAN_VIEW');
		var url = "index.php?app=monitor&func=mgr&action=plan&task=loadPage&from=view&pid="+id+"&comefrom=share";
		mainPanel.loadClass(url, 'CNOA_MENU_USER_PLAN_VIEW', lang('viewPlan'), "icon-page-view");
	}
	
}