//CNOA_MAIN_DESKTOP_LINCES
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=hr&func=person&action=waiting&from=license";
		
		this.id = "CNOA_MAIN_DESKTOP_LINCES";
		if(portalsID) this.id += portalsID;

		this.fieldsInbox = [
			{name : "id"},
			{name : "truename"},
			{name : "deptment"},
			{name : "licenseName"},
			{name : "licenseNum"},
			{name : "licenseType"},
			{name : "licenseStatus"},
			{name : "licenseOrg"},
			{name : "startLicenseDate"},
			{name : "endLicenseDate"},
			{name : "operate"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + '&task=getJsonData', disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fieldsInbox}),
			listeners:{
				exception : function(th, type, action, opt, response, arg){
					_this.checkPermit(opt.reader.jsonData.noPermit);
				},
				load : function(){
					_this.mainPanel.show();
				}
			}
		});
		this.store.load({params:{start:0, rows: 5}});

		this.colModel = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('certificateName'), dataIndex: 'licenseName', width: 150, sortable: true, menuDisabled :true},
			{header: lang('truename'), dataIndex: 'truename', width: 80, sortable: true, menuDisabled :true},
			{header: lang('department'), dataIndex: 'deptment', width: 80, sortable: true, menuDisabled :true},
			{header: lang('fZdepartment'), dataIndex: 'licenseOrg', width: 100, sortable: true, menuDisabled :true},
			{header: lang('expireTime'), dataIndex: 'endLicenseDate', width: 100, sortable: true,menuDisabled :true },
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
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
					//grid.getEl().child(".x-grid3").setStyle("backgroundColor", "#F6F6F6");
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

		this.mainPanel = new Ext.ux.Portlet({
			xtype: "portlet",
			id: this.id,
			title: lang('certificate'),		
			layout: 'fit',
			height: 250,
			tools: tools,
			items: [
				this.grid
			]
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

	makeLink : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;

		return "<a href='"+rd.link+"' target='_blank'><div>"+value+"</div></a>";
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}
