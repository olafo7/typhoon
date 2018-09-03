var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		this.baseUrl = "index.php?app=user&func=customers&action=statistic";
		
		var me = this,
			desktopId = "CNOA_MAIN_DESKTOP_USER_STATISTIC",
			statisticPanel;
		if(portalsID) desktopId += portalsID;
		Ext.Ajax.request({
			url: this.baseUrl+'&task=getDegreeHeader',
			success: function(response){
				var degree = Ext.decode(response.responseText),
					columns = [],
					fields = [], 
					field, header;

				me.checkPermit(degree.noPermit);
				
				if(degree.noPermit != true){
					Ext.each(degree, function(item, index){
						field = 'degree'+item.id;
						header = {header: item.name, dataIndex: field};
						columns.push(header);
						fields.push({name:field})
					});
					var desktop = Ext.getCmp(desktopId);
					statisticPanel = me.getStatisticPanel(columns, fields);
					desktop.add(statisticPanel);
					desktop.doLayout();
				}
			}
		});
		
		var tools = [], draggable = false;
		tools.push({
			id:'refresh',
			handler: function(){
				statisticPanel.store.reload();
			}
		});
		tools.push({
			id:'maximize',
			handler: function(){
				mainPanel.closeTab('CNOA_MENU_USER_CUSTOMERS_STATISTIC');
				mainPanel.loadClass('index.php?app=user&func=customers&action=statistic', 'CNOA_MENU_USER_CUSTOMERS_STATISTIC', lang('statisticsTracking'), 'icon-performance');
			}
		});
		
		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_JOBTYPE == "superAdmin")){
			draggable = true;
			tools.push({
				id:'close',
				handler: function(e, target, panel){
					panel.ownerCt.remove(panel, true);
					CNOA_main_common_index.closeDesktopApp(desktopId);
				}
			});
		};
		
		this.mainPanel = new Ext.ux.Portlet({
			xtype: "portlet",
			id: desktopId,
			title: lang('customerStatTrack'),
			layout: "fit",
			height: 250,
			draggable: draggable,
			tools: tools
		});
	},
	
	//统计面板
	getStatisticPanel: function(columns, fields){
		var me = this,
		
		fields = [
			{name: 'name'},
			{name: 'addTotal'},
			{name: 'preSaleTotal'},
			{name: 'postSaleTotal'},
			{name: 'successTotal'},
			{name: 'moneyTotal'}
		].concat(fields),
		
		s_time = new Ext.TimeInterval({showMode: 'month'}),
		
		store = new Ext.data.Store({
			autoLoad: true,
			baseParams: {stime: s_time.getSTime(), etime: s_time.getETime()},
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=getStatistic", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})
		}),
		
		formatTotal = function(value){
			if(!value) value = 0;
			return '<label style="color:#FF3344; font-weight:bold;">' + value + '</label>';
		},
		
		formatMoney = function(value){
			return  '<label style="color:#EE88EE; font-weight:bold;">' + value + '</label>';
		},
		
		colModel = new Ext.grid.ColumnModel({
			defaults: {
				width: 100,
				sortable: false,
				menuDisabled :true,
				renderer: formatTotal
			},
			columns: [
				new Ext.grid.RowNumberer(),
				{header: lang('salesStaff'), dataIndex: 'name', renderer: null}
			].concat(columns, [
				{header: lang('newKHge'), dataIndex: 'addTotal'},			
				{header: lang('presalesTimes'), dataIndex: 'preSaleTotal'},
				{header: lang('afterReviewTimes'), dataIndex: 'postSaleTotal'},
				{header: lang('salesSuccessTimes'), dataIndex: 'successTotal'},
				{header: lang('totalSalesYuan'), dataIndex: 'moneyTotal', renderer: formatMoney}				
			])
		});
		
		return new Ext.grid.GridPanel({
			border: false,
			loadMask : {msg: lang('loading')},
			store: store,
			cm: colModel,
			viewConfig: {
				rowOverCls: 'x-grid3-row-over2'
			},
			tbar: new Ext.Toolbar({
				items:[
					lang('statisticalRange') + '：',
					s_time,
					{
						text: '统计',
						style:'margin: 3px 10px;',
						handler: function(){
							search = {
								stime: s_time.getSTime(),
								etime: s_time.getETime()
							}
							store.reload({params:search});
						}
					}
				]
			})
		});
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	}
}
