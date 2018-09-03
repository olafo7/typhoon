var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var me = this;
		
		this.baseUrl = "index.php?app=user&func=customers&action=index&module=presales";
		
		this.id = "CNOA_MAIN_DESKTOP_CUSTOMER_PRESALES";
		if(portalsID) this.id += portalsID;
		
		var fields = [
			{name: 'cid'},
			{name: 'pid'},
			{name: 'cname'},
			{name: 'content'},
			{name: 'nexttime'}
		];

		this.store = new Ext.data.Store({
			autoLoad: true,
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getPreSalesNotice", disableCaching: true}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
			listeners:{
				exception : function(th, type, action, opt, response, arg){
					me.checkPermit(opt.reader.jsonData.noPermit);
				},
				load : function(){
					me.mainPanel.show();
				}
			}
		});
		
		var render_content = function(value, meta, record){
			return '<a href="javascript:void(0);">'+value+'</a>';
		},
		render_opt = function(value){
			return '<a href="javascript:void(0);" style="color:#ff0000;">跟踪</a>';
		}
		
		colModel = new Ext.grid.ColumnModel([
			{header: lang('clientName'), dataIndex: 'cname', width: 120, sortable: true, menuDisabled :true},
			{header: lang('traceContent'), dataIndex: 'content', width: 80, id:'content', sortable: true, menuDisabled :true, renderer: render_content},
			{header: lang('nextGZtime'), dataIndex: 'nexttime', width: 140, sortable: true,menuDisabled :true},
			{header: lang('opt'), dataIndex: 'pid', width:80, renderer: render_opt}
		]),
		
		grid = new Ext.grid.GridPanel({
			bodyStyle: 'border-left-width:1px;',
			store: me.store,
			loadMask : {msg: lang('waiting')},
			cm: colModel,
			autoScroll: true,
			hideBorders: true,
			border: true,
			autoWidth: true,
			autoExpandColumn: 'content',
			listeners:{
				'cellclick' : function(grid, rowIndex, columnIndex){
					var field = grid.getColumnModel().getDataIndex(columnIndex);
					if(field == 'content'){
						var record = me.store.getAt(rowIndex);
						me.viewPresalse(record.get('pid'));
					}else if(field == 'pid'){
						var record = me.store.getAt(rowIndex);
						me.changeStatus(record.get('pid'), record.get('cid'));
					}
				}
			}
		});

		var tools = [], draggable = false;
		tools.push({
			id:'refresh',
			handler: function(){
				me.store.reload();
			}
		});
		
		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_JOBTYPE == "superAdmin")){
			draggable = true;
			tools.push({
				id:'close',
				handler: function(e, target, panel){
					panel.ownerCt.remove(panel, true);
					CNOA_main_common_index.closeDesktopApp(me.id);
				}
			});
		};

		this.mainPanel = new Ext.ux.Portlet({
			id: this.id,
			title: lang('perSaleGZ'),
			hideHeaders : true,
			layout: "fit",
			height: 250,
			draggable: draggable,
			items: [grid],
			tools: tools
		});
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},
	
	viewPresalse : function(pid){
		mainPanel.closeTab("CNOA_MENU_USER_CUSTOMERS_VIEW_PRESALES");
		mainPanel.loadClass(this.baseUrl + "&task=loadPage&from=presalesView&pid="+pid, "CNOA_MENU_USER_CUSTOMERS_VIEW_PRESALES", lang('viewPreSale'), "icon-page-view");
	},
	
	changeStatus: function(pid, cid){
		var me = this;
		
		CNOA.msg.cf(lang('sFJXGZKH'), function(btn){
			if(btn=='yes'){
				loadScripts("sm_user_customers", "app/user/scripts/cnoa.customers.js", function(){
					CNOA_user_customers_presalesAddEdit = new CNOA_user_customers_presalesAddEditClass("add", 0, cid);
					CNOA_user_customers_presalesAddEdit.show();
				});
			}
		});
		
		Ext.Ajax.request({  
			url: me.baseUrl + '&task=changeStatus',
			method: 'POST',
			params: { pid: pid },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(result.msg, lang('customerMgr'));
					me.store.reload();
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	}
}