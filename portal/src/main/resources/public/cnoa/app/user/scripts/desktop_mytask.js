///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;
		
		this.baseUrl = "index.php?app=user&func=task&action=default";
		
		this.id 	 = "CNOA_MAIN_DESKTOP_USER_TASK";
		if(portalsID) this.id += portalsID;
		
		var ID_TITLE	= Ext.id();
		var ID_UNACCEPT_BUTTON = Ext.id();
		var ID_DOING_BUTTON = Ext.id();
		this.unaccept        = 0;
		
		this.search = {
			status: ""
		};
		
		
		this.fields = [
			{name:"tid"},
			{name:"title"},
			{name:"statusText"},
			{name:"progress"},
			{name:"statusColor"}
			
		];
		
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getList", disableCaching: true}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
			listeners:{
				exception : function(th, type, action, opt, response, arg){
					_this.checkPermit(opt.reader.jsonData.noPermit);
				},
				load:function(th, record, e){
					_this.mainPanel.show();
					var unaccept = 0;
					var doing    = 0;
					//if(th.reader.jsonData.unaccept > 0){
						unaccept = "<span class='cnoa_color_red'>"+th.reader.jsonData.unaccept+"</span>";
					//}
					//if(th.reader.jsonData.doing > 0){
						doing = "<span class='cnoa_color_red'>"+th.reader.jsonData.doing+"</span>";
					//}
					Ext.getCmp(ID_UNACCEPT_BUTTON).setText(lang('unReceive') + "("+unaccept+")");
					Ext.getCmp(ID_DOING_BUTTON).setText(lang('ongoing') + "("+doing+")");
				}
			}
		});
		this.store.load({params:{start:0, task:"getList"}});
		
		this.colModel = new Ext.grid.ColumnModel([
			{header: lang('taskTitle'), dataIndex: 'title', width: 80,sortable: true, menuDisabled :true},
			{header: lang('status'), dataIndex: 'status', width: 80, sortable: true,menuDisabled :true, renderer: this.makeStatus.createDelegate(this)}]);
		this.grid = new Ext.grid.GridPanel({
			id: _this.id+"_GRID",
			bodyStyle: 'border-left-width:1px;',
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: _this.colModel,
			autoScroll: true,
			hideBorders: true,
			border: true,autoWidth: true,
			viewConfig: {
				forceFit: true
			},
			listeners:{
				"rowclick" : function(grid, rowIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					var tid = record.data.tid;
					try{
						mainPanel.closeTab("CNOA_MENU_USER_TASK_VIEW");
						mainPanel.loadClass("index.php?app=user&func=task&action=default&task=loadPage&from=view&tid="+tid, "CNOA_MENU_USER_TASK_VIEW", lang('viewTask'), "icon-page-view");
					}catch (e){}
				},
				"render" : function(grid){
					//grid.getEl().child(".x-grid3").setStyle("backgroundColor", "#F6F6F6");
				}
			},
			tbar : new Ext.Toolbar({
				style: 'border-width:0px;',
				items: [
					{
						text: lang('ongoing'),
						iconCls: 'icon-system-menu-user',
						tooltip:lang('showJXTASK'),
						id:ID_DOING_BUTTON,
						allowDepress: false,
						handler:function(){
							_this.search.status = "doing";
							_this.store.load({
								params: _this.search
							});
						}
					},'-',
					{
						text:lang('notReceive'),
						iconCls: 'icon-system-menu-user',
						tooltip: lang('displayTaskNotBeen'),
						id:ID_UNACCEPT_BUTTON,
						allowDepress: false,
						handler:function(){
							_this.search.status = "unaccept";
							_this.store.load({
								params: _this.search
							});
						}
					}
				]
			})
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
			id: this.id,
			title: lang('taskBoard'),
			hideHeaders : true,
			layout: "fit",
			height: 250,
			draggable: draggable,
			items: [this.grid],
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
	
	//行点击，取消加粗
	rowClick : function(grid, record){
		var l = grid.getEl().query("span[gridid="+this.actionFolder+"_"+record.data.id+"]");
		for (var i=0;i<l.length;i++){
			l[i].style.fontWeight = "normal";
		}
		
		try{
			var imgs = grid.getEl().query("img[gridid="+this.actionFolder+"_"+record.data.id+"]");
			imgs[0].src = "./resources/images/icons/sms-readed.gif";
		}catch (e){}
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
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
//var DESKTOPAPP_OBJ = new DESKTOPAPP_CLASS();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
