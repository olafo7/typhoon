///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=communication&func=message&action=index";
		
		this.id = "CNOA_MAIN_DESKTOP_COMM_MESSAGE_INBOX";
		if(portalsID) this.id += portalsID;

		this.fieldsInbox = [
			{name:"id"},
			{name:"sid"},
			{name:"smstype"},
			{name:"isread"},
			{name:"attach"},
			{name:"sender"},
			{name:"title"},
			{name:"content"},
			{name:"posttime"}
		];
		this.storeInbox = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl, disableCaching: true}),   
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
		this.storeInbox.load({params:{start:0, task:"listInBox", folder:"inbox", rows: 10}});
		//this.smInbox.handleMouseDown = Ext.emptyFn;
		this.colModelInbox = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', hidden: true},
			{header: "sid", dataIndex: 'sid', hidden: true},
			{header: "smstype", dataIndex: 'smstype',hidden: true},
			{header: '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" />', dataIndex: 'isread', width: 34, sortable: false, menuDisabled :true,renderer: this.statusCheck.createDelegate(this)},
			{header: lang('sender'), dataIndex: 'sender', width: 100, sortable: false,menuDisabled :true,renderer: this.contentCheck.createDelegate(this)},
			{header: lang('title'), dataIndex: 'title', width: 210, sortable: false,menuDisabled :true,renderer: this.contentCheck.createDelegate(this)}
			//{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		])
		this.gridInbox = new Ext.grid.GridPanel({
			id: this.id+"_GRID",
			bodyStyle: 'border-left-width:1px;',
			store: this.storeInbox,
			loadMask : {msg: lang('waiting')},
			cm: this.colModelInbox,
			hideBorders: true,
			autoWidth: true,
			border: false,
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
				},  
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					/*
					var record = grid.getStore().getAt(rowIndex);

					_this.rowClick(grid, record);

					if(columnIndex != 0){
						_this.panelInbox.getLayout().setActiveItem(1);
						var id = record.data.smstype == "sys" ? record.data.sid : record.data.id;
						_this.previewMessage(id, record.data.smstype);
					}
					*/
				},

				"rowclick" : function(grid, rowIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					var id = record.data.smstype == "sys" ? record.data.sid : record.data.id;
					
					_this.rowClick(grid, record);
					
					try{
						mainPanel.closeTab("CNOA_MENU_COMMUNICATION_MESSAGE_VIEW");
						mainPanel.loadClass("index.php?app=communication&func=message&action=index&task=loadPage&from=view&folder=inbox&smstype="+record.data.smstype+"&id="+id, "CNOA_MENU_COMMUNICATION_MESSAGE_VIEW", "查看信息", "icon-cnoa-mail");
					}catch (e){}
				},
				"render" : function(grid){
					//grid.getEl().child(".x-grid3").setStyle("backgroundColor", "#F6F6F6");
					//grid.getEl().child("x-grid3").setStyle("backgroundColor", "red");
				}
			}/*,
			bbar: new Ext.Toolbar([
				'->',
				{
					text: "收件箱 >>",
					handler: function() {
						try{
							node = api.getNodeById("CNOA_MENU_COMMUNICATION_MESSAGE");
							mainPanel.loadClass(node.attributes.autoLoadUrl, node.id, node.text, node.attributes.iconCls);
						}catch (e){}
					}
				}
			])*/
		});
		
		var tools = [], draggable = false;
		tools.push({
			id:'refresh',
			handler: function(){
				_this.storeInbox.reload();
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
		}

		this.mainPanel = new Ext.ux.Portlet({
			xtype: "portlet",
			id: this.id,
			title: lang('inbox'),
			layout: 'fit',
			frame: true,
			height: 250,
			draggable: draggable,
			tools: tools,
			items: [this.gridInbox]
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

	contentCheck : function(value, p, record){
		var contentStyle="";
		if(typeof(value) != 'string'){
			value = "";
		}
		value = value.replace(new RegExp("<br />","g"),"\n");
		value = value.replace(/<\/div>(.*)$/gi,'');
		if (record.data.isread == 0 ){
			contentStyle = "font-weight: bold;";
		}
		return String.format('<span style="{1}"" gridid="{3}_{2}">{0}</span>', value, contentStyle, record.data.id, this.actionFolder);
	},
	
	statusCheck : function(value, p, record){
		if (value==1){
			return '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" />';
		}else{
			return '<img src="./resources/images/icons/sms-unread.gif" width="16" height="16" gridid="'+this.actionFolder+'_'+record.data.id+'" />';
		}
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}
//var DESKTOPAPP_OBJ = new DESKTOPAPP_CLASS();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
