var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		//this.baseUrl = "index.php?app=news&func=notice&action=index&task=desktoplist";
		this.baseUrl = "index.php?action=commonJob&act=getOutLinkList&from=desktop";
		
		this.id = "CNOA_MAIN_DESKTOP_MAIN_OUTLINK";
		if(portalsID) this.id += portalsID;

		this.fieldsInbox = [
			{name:"id"},
			{name:"name1"},
			{name:"name2"},
			{name:"name3"},
			{name:"link1"},
			{name:"link2"},
			{name:"link3"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + '&task=getList', disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fieldsInbox})		
		});
		this.store.load({params:{start:0, rows: 5}});
		
		this.colModel = new Ext.grid.ColumnModel([
			{header:"id",dataIndex:'id',hidden:true},
			{header:lang('link'),dataIndex:'name1',width:100,sortable:false,menuDisabled:true,renderer:this.makeLink1.createDelegate(this)},
			{header:lang('link'),dataIndex:'name2',width:100,sortable:false,menuDisabled:true,renderer:this.makeLink2.createDelegate(this)},
			{header:lang('link'),dataIndex:'name3',width:100,sortable:false,menuDisabled:true,renderer:this.makeLink3.createDelegate(this)}
		])
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			bodyStyle: 'border-left-width:1px;',
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			autoWidth: true,
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				"render" : function(grid){
					grid.getEl().child(".x-grid3").setStyle("backgroundColor", "#F6F6F6");
				}
			}
		});

		var tools = [], draggable = false;
		tools.push({
			id:'refresh',
			handler: function(){
				_this.store.reload();
			}
		});
		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_JOBTYPE == "superAdmin")){
			draggable = true;
			tools.push({
				id:'close',
				handler: function(e, target, panel){
					panel.ownerCt.remove(panel, true);
					_this.closeAction();
				}
			});
		};

		this.mainPanel = {
			xtype: "portlet",
			id: this.id,
			title: lang('outLink'),
			layout: 'fit',
			height: 250,
			draggable: draggable,
			tools: tools,
			items: [this.grid]
		}
	},

	makeLink1 : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		if(value == null){
			return '';
		}
		return "<a href='"+rd.link1+"' target='_blank'><div>"+value+"</div></a>";
	},

	makeLink2 : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		if(value == null){
			return '';
		}
		return "<a href='"+rd.link2+"' target='_blank'><div>"+value+"</div></a>";
	},

	makeLink3 : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		if(value == null){
			return '';
		}
		return "<a href='"+rd.link3+"' target='_blank'><div>"+value+"</div></a>";
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}
