//定义全局变量：
var CNOA_communication_message_mgrClass, CNOA_communication_message_mgr;

/**
* 主面板-列表
*
*/
CNOA_communication_message_mgrClass = CNOA.Class.create();
CNOA_communication_message_mgrClass.prototype = {
	init : function(){
		var _this = this;
		
		this.actionFolder = "inbox";
		this.ID_btn_collapseExpand = Ext.id();
		
		this.baseUrl = "index.php?app=communication&func=message&action=mgr";
		
		this.inFields = [
			{name : "id"},
			{name : "sid"},
			{name : "smstype"},
			{name : "isread"},
			{name : "attach"},
			{name : "sender"},
			{name : "title"},
			{name : "content"},
			{name : "posttime"},
			{name : "uid"}
		];
		
		this.inStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=listInBox", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.inFields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		
		this.inColModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: "sid", dataIndex: 'sid', hidden: true},
			{header: "smstype", dataIndex: 'smstype',hidden: true},
			{header: '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" />', dataIndex: 'isread', width: 34, sortable: true, menuDisabled :true,renderer: this.statusCheck.createDelegate(this)},
			{header: '<img src="./resources/images/icons/attach.gif" width="13" height="13" />', dataIndex: 'attach', width: 34, sortable: true,menuDisabled :true, resizable: false,renderer: this.attachCheck,tooltip:lang('attach')},
			{header: lang('sender'), dataIndex: 'sender', width: 200, sortable: false,menuDisabled :true,renderer: this.contentCheck.createDelegate(this)},
			{header: lang('title'), dataIndex: 'title', width: 180, sortable: false,menuDisabled :true,renderer: this.contentCheck.createDelegate(this)},
			{header: lang('content'), dataIndex: 'content', width: 290, sortable: false,menuDisabled :true,renderer: this.contentCheck.createDelegate(this)},
			{header: lang('posttime'), dataIndex: 'posttime', width: 120, sortable: false,menuDisabled :true,renderer: this.contentCheck.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.pagingBarInbox = new Ext.PagingToolbar({
			displayInfo:true,
			
			   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					var node = _this.deptTree.getSelectionModel().getSelectedNode();
					var uid = node.attributes.uid;
					params.uid	= uid;
				}
			}
		});

		this.ingrid = new Ext.grid.GridPanel({
			sm: this.sm,
			stripeRows : true,
			store: this.inStore,
			loadMask : {msg: lang('waiting')},
			cm: this.inColModel,
			hideBorders: true,
			border: false,
			listeners: {
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					
					_this.rowClick(grid, record);
					if(columnIndex != 0){
						var id = record.data.smstype == "sys" ? record.data.sid : record.data.id;
						_this.view(id, record.data.smstype, record.data.uid, "inbox");
					}
				}
			}
		});
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			id: this.ID_tree_treeRoot,
			expanded: true,
			uid: 0
		});

		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl + "&task=getAllUserListsInDeptTree",
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
				"load":function(node){
					this.treeRoot.firstChild.collapse(true);
					
					//判断折叠展开按钮的状态
					if(Ext.getCmp(this.ID_btn_collapseExpand).pressed){
						this.treeRoot.expand(true);
					}else{
						this.treeRoot.firstChild.expand();
					}

					this.deptTree.getEl().unmask();
				}.createDelegate(this)
			}
		});

		this.deptTree = new Ext.tree.TreePanel({
			hideBorders: true,
			border: false,
			rootVisible: false,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: this.treeLoader,
			root: this.treeRoot,
			autoScroll: true,
			listeners: {
				"click":function(node){
					//如果节点不可用 || 不是"人"节点 || 节点是自己 || 已经点击过
					if((node == undefined) || node.disabled || (node.attributes.uid == undefined) || (node.attributes.uid == CNOA_USER_UID)){
						return;
					}
					
					var cc = Ext.getCmp(ID_btn_text).getValue();
					if(cc == "inbox"){
						_this.inStore.load({params: {uid: node.attributes.uid}});	
					}else{
						_this.outStore.load({params: {uid: node.attributes.uid}});
					}
					
				}.createDelegate(this)
			}
		});
		
		this.deptPanel = new Ext.Panel({
			title: lang('clickPersonViewEmail'),
			region: 'west',
			layout:'fit',
			split: true,
			width: 180,
			minWidth: 80,
			maxWidth: 380,
			bodyStyle: 'border-right-width:1px;',
			items: [this.deptTree],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					{
						text: lang('expand'),
						iconCls: 'icon-expand-all',
						id: this.ID_btn_collapseExpand,
						tooltip: lang('expandMenuTip'),
						enableToggle: true,
						toggleHandler: function(th, pressed){
							if(pressed){
								//展开
								th.setIconClass("icon-collapse-all");
								th.setText(lang('collapse'));
								th.setTooltip(lang('collapseMenuTip'));
								_this.treeRoot.expand(true);
							}else{
								//折叠
								th.setIconClass("icon-expand-all");
								th.setText(lang('expand'));
								th.setTooltip(lang('expandMenuTip'));
								_this.treeRoot.collapse(true);
								_this.treeRoot.firstChild.expand();
							}
						}
					},
					"->",
					//刷新tree
					{
						handler : function(button, event) {
							_this.treeRoot.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					}
				]
			})
		});
		
		this.outFields = [
			{name:"id"},
			{name:"attach"},
			{name:"receiver"},
			{name:"title"},
			{name:"content"},
			{name:"posttime"},
			{name:"readed"},
			{name:"uid"}
		];
		
		this.outStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=listOutBox", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.outFields})		
		});
		
		this.outColModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', width: 20, sortable: true, hidden: true},
			{header: '<img src="./resources/images/icons/attach.gif" width="13" height="13" />', dataIndex: 'attach', width: 34, sortable: true,menuDisabled :true, resizable: false,renderer: this.attachCheck,tooltip:lang('attach')},
			{header: lang('receiver'), dataIndex: 'receiver', width: 200, sortable: false,menuDisabled :true},
			{header: lang('title'), dataIndex: 'title', width: 180, sortable: false,menuDisabled :true},
			{header: lang('content'), dataIndex: 'content', width: 340, sortable: false,menuDisabled :true},
			{header: lang('receiverReadNum'), dataIndex: 'readed', width: 120, sortable: false,menuDisabled :true, renderer : this.reader.createDelegate(this)},
			{header: lang('posttime'), dataIndex: 'posttime', width: 120, sortable: false,menuDisabled :true},
			{header: lang('opt'), dataIndex: 'id', width: 80, sortable: false,menuDisabled :true, renderer:function(v){
				return '<a href="javascript:void(0)" onclick="CNOA_communication_message_mgr.operate(' + v + ')">' + lang('recell') + '</a>';
			}},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);

		this.pagingBarOutbox = new Ext.PagingToolbar({
			displayInfo:true,
			
			   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					var node = _this.deptTree.getSelectionModel().getSelectedNode();
					var uid = node.attributes.uid;
					params.uid	= uid;
				}
			}
		});
		
		this.outgrid = new Ext.grid.GridPanel({
			sm: this.sm,
			stripeRows : true,
			store: this.outStore,
			loadMask : {msg: lang('waiting')},
			cm: this.outColModel,
			hideBorders: true,
			border: false,
			region: 'center',
			viewConfig: {
				forceFit: true
			},
			scriptRows: true,
			listeners: { 
				'cellclick' : function(grid, rowIndex, columnIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					if(jQuery.inArray(columnIndex, [0, 1, 3, 7, 9])==-1){
						var id = record.data.smstype == "sys" ? record.data.sid : record.data.id;
						_this.view(id, record.data.smstype, record.data.uid, "outbox");
					}
				}
			}
		});
		
		var ID_btn_text = Ext.id();
		this.gridCt = new Ext.Panel({
			border: false,
			layout: "card",
			region: "center",
			activeItem: 0,
			items: [this.ingrid, this.outgrid],
			tbar: [
				{
					xtype: 'hidden',
					value: 'inbox',
					id: ID_btn_text 
				},
				{
					enableToggle: true,
				    allowDepress: false,
					pressed: true,
				    toggleGroup: "MAIL_RECORD_LIST",
					iconCls: 'icon-roduction',
					text: lang('inbox'),
					handler : function(){
						_this.gridCt.getLayout().setActiveItem(0);
						
						var node = _this.deptTree.getSelectionModel().getSelectedNode();
						if(Ext.isObject(node)){
							_this.inStore.load({params:{uid: node.attributes.uid}});
						}
						
						Ext.getCmp(ID_btn_text).setValue("inbox");
					}
				},
				{
					enableToggle: true,
				    allowDepress: false,
				    toggleGroup: "MAIL_RECORD_LIST",
					iconCls: 'icon-roduction',
					text: lang('sendBox'),
					handler : function(){
						_this.gridCt.getLayout().setActiveItem(1);
						
						var node = _this.deptTree.getSelectionModel().getSelectedNode();
						if(Ext.isObject(node)){
							_this.outStore.load({params:{uid: node.attributes.uid}});
						}
						
						Ext.getCmp(ID_btn_text).setValue("outbox");
					}
				}
			]
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items : [this.gridCt, this.deptPanel]
		});
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
		try{
			value = value.replace(new RegExp("<br />","g"),"\n");
			value = value.replace(/<\/div>(.*)$/gi,'');
		}catch (e){
			value = lang('userNotExists');
		}
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
	
	attachCheck : function(value){
		if (value==1){
			return '<img src="./resources/images/icons/attach2.gif" width="16" height="16" />';
		}else{
			return "";
		}
	},
	
	view : function(id, smstype, uid, actionFolder){
		mainPanel.closeTab("CNOA_MENU_COMMUNICATION_MESSAGE_VIEW");
		mainPanel.loadClass("index.php?app=communication&func=message&action=index&task=loadPage&from=view&folder="+actionFolder+"&smstype="+smstype+"&id="+id+"&uid="+uid+"&isMonitor=1", "CNOA_MENU_COMMUNICATION_MESSAGE_VIEW", "查看信息", "icon-cnoa-mail");
	},
	
	operate:function(v){
		var _this = this;
		CNOA.msg.cf(lang('recellMSgNotice'),function(btn){
			if(btn == 'yes'){
				Ext.Ajax.request({
					url: _this.baseUrl + "&task=cancelmail",
					method: 'POST',
					params:{fromId:v},
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.notice(lang('recellSucess'));
						}else{
							CNOA.msg.alert(result.msg, function(){});
						}
					}
				});
			}
		});
	},
	
	reader : function(value, p, record){
		return "<a href='javascript:void()', onclick='CNOA_communication_message_mgr.readView("+record.data.id+")'>"+value+"</a>";
	},
	
	readView : function(id){
		var _this = this;

		var fields = [
			{name : "id"},
			{name : "truename"},
			{name : "isread"},
			{name : "readtime"}
		];
		
		var store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getReaderList"}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})		
		});
		
		store.load({params : {id : id}});
		
		var smOutbox = new Ext.grid.CheckboxSelectionModel({singleSelect:false}); 

		var colModelOutbox = new Ext.grid.ColumnModel([
			smOutbox,
			{header: "id", dataIndex: 'id', width: 20, sortable: true, hidden: true},
			{header: "readed", dataIndex: 'isread', width: 20, sortable: true, hidden: true},
			{header: lang('reader'), dataIndex: 'truename', width: 100, sortable: false,menuDisabled :true, renderer : _this.truenameColor},
			{header: lang('firstReadTime'), dataIndex: 'readtime', width: 180, sortable: false,menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		var grid = new Ext.grid.GridPanel({
			stripeRows : true,
			region : "center",
			layout : "fit",
			store: store,
			loadMask : {msg: lang('waiting')},
			cm: colModelOutbox,
			sm: smOutbox,
			hideBorders: true,
			border: false,
			tbar : new Ext.Toolbar({
				items: [
					{
						handler : function(button, event) {
							store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},lang('ReaderMarkNotice')
				]
			})
		});
		
		var win = new Ext.Window({
			title : lang('readerStatus'),
			width : 500,
			height : makeWindowHeight(500),
			modal : true,
			layout : "border",
			items : [grid],
			buttons : [
				{
					text : lang('close'),
					handler : function(){
						win.close();
					}
				}
			]
		}).show();
	}
}

