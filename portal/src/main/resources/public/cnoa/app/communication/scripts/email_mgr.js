//定义全局变量：
var CNOA_communication_email_mgrClass, CNOA_communication_email_mgr;

/**
* 主面板-列表
*
*/
CNOA_communication_email_mgrClass = CNOA.Class.create();
CNOA_communication_email_mgrClass.prototype = {
	init : function(){
		var _this = this;
		
		this.ID_btn_collapseExpand = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		
		this.baseUrl = "index.php?app=communication&func=email&action=mgr";
		
		this.inFields = [
			{name:"id"},
			{name:"from"},
			{name:"subject"},
			{name:"content"},
			{name:"date"},
			{name:"isread"},
			{name:"attachs"}
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
			{header: '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" /><img src="./resources/images/icons/attach2.gif" width="16" height="16" />', dataIndex: 'isread', width: 45, sortable: true, menuDisabled :true,renderer: this.statusCheck.createDelegate(this)},
			{header: lang('sender'), dataIndex: 'from', width: 230, sortable: false,menuDisabled :true,renderer: this.makeFrom.createDelegate(this)},
			{header: lang('title'), dataIndex: 'subject', width: 250, sortable: false,menuDisabled :true,renderer: this.contentCheck.createDelegate(this)},
			{header: lang('receiveTime'), dataIndex: 'date', width: 120, sortable: false,menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);

		this.ingrid = new Ext.grid.GridPanel({
			sm: this.sm,
			stripeRows : true,
			store: this.inStore,
			loadMask : {msg: lang('loading')},
			cm: this.inColModel,
			hideBorders: true,
			border: false,
			layout: 'fit',
			region : 'center',
			listeners: {
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					
					_this.rowClick(grid, record);
					if(columnIndex != 0){
						var id = record.data.smstype == "sys" ? record.data.sid : record.data.id;
						_this.view(id, 'inbox');
					}
				}
			}
		});
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			name: '',
			text: lang('allEmailList'),
			iconCls: "icon-tree-root-cnoa",
			cls : "feeds-node",
			id: this.ID_tree_treeRoot,
			expanded: true
		});

		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl + "&action=mgr&task=getAllUserListsInDeptTree",
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
				"load":function(node){
					this.treeRoot.firstChild.collapse(true);
					
					try{
						//判断折叠展开按钮的状态
						if(Ext.getCmp(this.ID_btn_collapseExpand).pressed){
							this.treeRoot.expand(true);
						}else{
							this.treeRoot.firstChild.expand();
						}
					}catch(e){}

					try{
						//判断折叠展开按钮的状态
						if(Ext.getCmp(this.ID_btn_collapseExpand2).pressed){
							this.treeRoot2.expand(true);
						}else{
							this.treeRoot2.firstChild.expand();
						}
					}catch(e){}

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
					/*var name = node.attributes.name;
					var cc = Ext.getCmp(ID_btn_text).getValue();
					if(cc == "inbox"){
						_this.inStore.load({params: {srcmail: name}});	
					}else{
						_this.outStore.load();
					}*/
					if((node == undefined) || node.disabled || (node.attributes.uid == undefined || node.attributes.uid == CNOA_USER_UID)){
						return;
					}
					
					var uid = node.attributes.uid;
					this.store.load({params:{uid: uid}});
					
				}.createDelegate(this)
			}
		});
		
		this.deptPanel = new Ext.Panel({
			title: lang('clickPersonViewEmail'),
			region: 'west',
			layout:'fit',
			split: false,
			width: 200,
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
						tooltip: lang('expand'),
						enableToggle: true,
						toggleHandler: function(th, pressed){
							if(pressed){
								//展开
								th.setIconClass("icon-collapse-all");
								th.setText(lang('collapse'));
								th.setTooltip(lang('collapse'));
								_this.treeRoot.expand(true);
							}else{
								//折叠
								th.setIconClass("icon-expand-all");
								th.setText(lang('expand'));
								th.setTooltip(lang('expand'));
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
		
		this.fields = [
			{name:"id"},
			{name:"name"},
			{name:"uid"}
		];
		
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getEmailList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('email'), dataIndex: 'name', width: 180, sortable: false,menuDisabled :true}
		]);
		
		this.grid = new Ext.grid.GridPanel({
			title: lang('clickEmailList'),
			width: 220,
			minWidth: 80,
			maxWidth: 230,
			stripeRows : true,
			store: this.store,
			loadMask : {msg: lang('loading')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			region: 'center',
			layout: 'fit',
			scriptRows: true,
			bodyStyle: 'border-right-width:1px;',
			listeners: {
				"rowclick" : function(th, rowIndex, e){
					var record = th.getStore().getAt(rowIndex);
					this.inStore.load({params:{id: record.data.id, uid: record.data.uid}});
				}.createDelegate(this)
			}
		});
		
		this.cctoCenterPanel = new Ext.Panel({
			layout: "border",
			region: 'center',
			hideBorders: true,
			//bodyStyle: 'border-left-width:1px;',
			bodyStyle: 'border-right-width:1px;border-left-width:1px;',
			items: [this.grid]
		});
		
		this.ccmainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			region: 'west',
			layout:'border',
			width: 400,
			items: [this.deptPanel, this.cctoCenterPanel]
		});
		
		this.outFields = [
			{name:"id"},
			{name:"to"},
			{name: 'from'},
			{name:"subject"},
			{name:"content"},
			{name:"date"},
			{name:"attachs"}
		];
		
		this.outStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=listOutBox", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.outFields})		
		});
		
		this.outColModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: '<img src="./resources/images/icons/attach.gif" width="13" height="13" />', dataIndex: 'attachs', width: 34, sortable: true,menuDisabled :true, resizable: false,renderer: this.attachCheck,tooltip:"附件"},
			{header: lang('receiver'), dataIndex: 'to', width: 135, sortable: false,menuDisabled :true,renderer: this.makeOutFrom.createDelegate(this)},
			{header: lang('sentFromEmail'), dataIndex: 'from', width: 135, sortable: false,menuDisabled :true},
			{header: lang('title'), dataIndex: 'subject', width: 180, sortable: false,menuDisabled :true,renderer: this.contentOutCheck.createDelegate(this)},
			{header: lang('receiveTime'), dataIndex: 'date', width: 120, sortable: false,menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.outgrid = new Ext.grid.GridPanel({
			sm: this.sm,
			stripeRows : true,
			store: this.outStore,
			loadMask : {msg: lang('loading')},
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
					if(jQuery.inArray(columnIndex, [0, 1, 2]) == -1){
						var id = record.data.smstype == "sys" ? record.data.sid : record.data.id;
						_this.view(id, "outbox");
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
						
						if(this.grid.getSelectionModel().selections.length>0){
							var record = this.grid.getSelectionModel().selections.items[0];
							this.outStore.load({params:{uid: record.data.uid, id: record.data.id}});
						}
						
						/*
						var node = _this.deptTree.getSelectionModel().getSelectedNode();
						if(Ext.isObject(node)){
							_this.inStore.load({params:{srcmail: node.attributes.name}});
						}
						Ext.getCmp(ID_btn_text).setValue("inbox");*/
					}.createDelegate(this)
				},
				{
					enableToggle: true,
				    allowDepress: false,
				    toggleGroup: "MAIL_RECORD_LIST",
					iconCls: 'icon-roduction',
					text: lang('sendBox'),
					handler : function(){
						_this.gridCt.getLayout().setActiveItem(1);
						
						if(this.grid.getSelectionModel().selections.length>0){
							var uid = this.grid.getSelectionModel().selections.items[0].data.uid;
							this.outStore.load({params:{uid: uid}});
						}
						/*
						Ext.getCmp(ID_btn_text).setValue("outbox");*/
					}.createDelegate(this)
				}
			]
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items : [this.gridCt, this.ccmainPanel]
		});
	},
	
	//行点击，取消加粗
	rowClick : function(grid, record){
		var l = grid.getEl().query("span[comm_mail_gridid="+this.actionFolder+"_"+record.data.id+"]");
		for (var i=0;i<l.length;i++){
			l[i].style.fontWeight = "normal";
		}
		
		try{
			var imgs = grid.getEl().query("img[comm_mail_gridid="+this.actionFolder+"_"+record.data.id+"]");
			imgs[0].src = "./resources/images/icons/sms-readed.gif";
		}catch (e){}
	},
	
	view : function(id, type){
		var uid = this.grid.getSelectionModel().selections.items[0].data.uid;
		mainPanel.closeTab("CNOA_MENU_COMMUNICATION_EMAIL_VIEW");
		mainPanel.loadClass("index.php?app=communication&func=email&action=index&task=loadPage&from=view&folder="+type+"&id="+id+"&uid="+uid+"&isMonitor=1", "CNOA_MENU_COMMUNICATION_EMAIL_VIEW", "查看邮件", "icon-cnoa-mail");
	},
	
	makeFrom : function(value, p, record){
		var rd = record.data;
		var h = '';
		var h2 = '';
		if(value[0].name != null){
			h  = value[0].name;
			h2 = "<br><span class='cnoa_color_gray'>("+value[0].address+")</span>";
		}else{
			h  = '<a href="javascript:void(0)" onclick="CNOA_communication_email_mgr.addNewAddress(\''+value[0].address+'\')">'+value[0].address+'</a>';
		}
		if (record.data.isread == 0 ){
			var contentStyle = "font-weight: bold;";
		}
		return String.format('<span style="{1}" comm_mail_gridid="{3}_{2}">{0}</span>{4}', h, contentStyle, record.data.id, this.actionFolder, h2);
	},

	contentCheck : function(value, p, record){
		var rd = record.data;
		var contentStyle="";
		
		if (record.data.isread == 0 ){
			contentStyle = "font-weight: bold;";
		}
		var rt = String.format('<span style="{1}" comm_mail_gridid="{3}_{2}">{0}</span>', value, contentStyle, record.data.id, this.actionFolder);
		return rt+"<br><span class='cnoa_color_gray'>"+rd.content+"&nbsp;</span>";
	},
	
	statusCheck : function(value, p, record){
		var rd = record.data;
		var h = '';
		if (value==1){
			h += '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" />';
		}else{
			h += '<img src="./resources/images/icons/sms-unread.gif" width="16" height="16" comm_mail_gridid="'+this.actionFolder+'_'+record.data.id+'" />';
		}
		if(rd.attachs == 1){
			h += '<img src="./resources/images/icons/attach2.gif" width="16" height="16" />';
		}
		return h;
	},
	
	attachCheck : function(value){
		if (value==1){
			return '<img src="./resources/images/icons/attach2.gif" width="16" height="16" />';
		}else{
			return "";
		}
	},
	
	makeOutFrom : function(value, p, record){
		var rd = record.data;
		return rd.to;
	},
	
	contentOutCheck : function(value, p, record){
		var rd = record.data;
		var contentStyle="";
		try{
			value = value.replace(new RegExp("<br />","g"),"\n");
			value = value.replace(/<\/div>(.*)$/gi,'');
		}catch (e){
			value = lang('userNotExists');
		}
		var rt = String.format('<span>{0}</span>', value);
		return rt+"<br><span class='cnoa_color_gray'>"+rd.content+"</span>";
	}
}

