//定义全局变量：
var CNOA_communication_im_mgrClass, CNOA_communication_im_mgr;

/**
* 主面板-列表
*
*/
CNOA_communication_im_mgrClass = CNOA.Class.create();
CNOA_communication_im_mgrClass.prototype = {
	init : function(){
		var _this = this;

		//按钮ID定义-刷新
		this.ID_btn_refreshList = Ext.id();
		//结构树根节点ID
		this.ID_tree_treeRoot = "CNOA_communication_im_mgr_tree_root";
		this.ID_tree_treeRoot2 = "CNOA_communication_im_mgr_tree_root2";
		//ID定义-折叠/展开树按钮
		this.ID_btn_collapseExpand = Ext.id();
		this.ID_btn_collapseExpand2 = Ext.id();
		//ID定义-与XXX的会话记录
		this.ID_chattingWith = Ext.id();
		this.ID_exportBtn = Ext.id();

		this.baseUrl = "index.php?app=communication&func=im";

		this.fields = [
			{name:"id", mapping: 'id'},
			{name:"date", mapping: 'date'},
			{name:"content", mapping: 'content'}
		];

		this.store = new Ext.data.GroupingStore({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&action=mgr"}),
			groupField: 'date',
			sortInfo: {field: 'id', direction: 'ASC'},
			applySort: function(){},
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});
		
		//this.store.load();

		this.colModel = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', width: 20, sortable: true, hidden: true},
			{header: lang('date'), dataIndex: 'date', width: 20, sortable: true},
			{header: lang('content'), dataIndex: 'content', width: 60, sortable: false,resizable: false, menuDisabled: true, renderer : _this.renderContent}
		]);

		this.pagingBar = new Ext.PagingToolbar({   
			displayInfo:true,
			emptyMsg: lang('pagingToolbarEmptyMsg'),
			displayMsg:lang('pagingToolbarDisplayMsg'),
			style: 'border-left-width:1px;',
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					try{
						var node1 = _this.deptTree.getSelectionModel().getSelectedNode();
						var node2 = _this.deptTree2.getSelectionModel().getSelectedNode();
						var fuid = node1.attributes.uid;
						var tuid = node2.attributes.uid;
						//task: "getHistory", fuid: fuid, tuid: tuid
						params.task = "getHistory";
						params.fuid = fuid;
						params.tuid = tuid;
					}catch(e){
						var groupInfo = _this.ddGroupGridPanel.getSelectionModel().selections.items[0];
						if(groupInfo){
							params.task = "getHistory";
							params.fuid = 0;
							params.tuid = 0;
							params.gid = groupInfo.data.id;
						}
					}
				}
			}
		});
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			ds: this.store,
			hideHeaders: true,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			viewConfig: {
				forceFit: true
			},
			view: new Ext.grid.GroupingView({
				forceFit: true,
				showGroupName: true,
				enableNoGroups: false,
				enableGroupingMenu: false,
				hideGroupedColumn: true
			})
		});

		this.centerPanel = new Ext.Panel({
			title: lang('sessionRecording'),
			region: 'center',
			layout: "fit",
			autoScroll: true,
			items: [this.grid],
			tbar : new Ext.Toolbar({
				style: 'height:22px;line-height:22px;',
				items: [
					new Ext.BoxComponent({
						id: this.ID_chattingWith,
						autoEl: {
							tag: 'div',
							style: 'font-weight:bold;color:#535353;',
							html: lang('clickToViewRecord')
						}
					}),"->",
					{
						xtype:'button',
						text: lang('delSelected'),
						hidden: true,
						handler:function(btn){
							var rows = _this.grid.getSelectionModel().getSelections(); // 返回值为 Record 数组 
							if (rows.length == 0) {
							    CNOA.miniMsg.alertShowAt(btn, lang('mustSelectOneRow'));
							} else {
							    CNOA.msg.cf(lang('confirmToDelete'), function(btn) {
							        if (btn == 'yes') {
							            if (rows) {
							                var ids = "";
							                for (var i = 0; i < rows.length; i++) {
							                    ids += rows[i].get("id") + ",";
							                    
							                    var r = rows[i]
							                    _this.store.remove(r);
							                }
											Ext.Ajax.request({
												url: _this.baseUrl + "&action=mgr&task=deleteRecord",
												method: 'POST',
												params:{ids:ids},
												success: function(r) {
													var result = Ext.decode(r.responseText);
													if(result.success === true){
														
													}else{
														CNOA.msg.alert(result.msg, function(){});
													}
												}
											});
							            }
							        }
							    });
							}
						}
					},
					{
						xtype: 'button',
						text: lang('export2'),
						hidden: true,
						hidden: true,
						id: this.ID_exportBtn,
						iconCls: 'icon-file-down',
						handler: function(){
							_this.exportHistory();
						}
					}
				]
			}),
			bbar: this.pagingBar
		});
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			id: this.ID_tree_treeRoot,
			expanded: true//,
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

					//如果是从会话窗口中点击过来，则默认选中此人的会话
					///_this.openHistory(_this.deptTree.getNodeById("CNOA_main_im_index_userlist_tree_node_"+CNOA.communication.im.history.viewUid));
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
			listeners:{
				"click":function(){
					setTimeout(function(){
						_this.openHistory();
					}, 100);
				}.createDelegate(this)
			}
		});

		this.deptPanel = new Ext.Panel({
			title: lang('username2'),
			headerCfg: {
				cls: 'x-panel-header', 
				style:"text-align:right"
			},
			region: 'west',
			layout:'fit',
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
							this.reloadDeptData();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					}
				]
			})
		});
		
		this.treeRoot2 = new Ext.tree.AsyncTreeNode({
			id: this.ID_tree_treeRoot2,
			expanded: true//,
		});

		this.deptTree2 = new Ext.tree.TreePanel({
			hideBorders: true,
			border: false,
			rootVisible: false,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: this.treeLoader,
			root: this.treeRoot2,
			autoScroll: true,
			listeners:{
				"click":function(){
					setTimeout(function(){
						_this.openHistory();
					}, 100);
				}.createDelegate(this)
			}
		});

		this.deptPanel2 = new Ext.Panel({
			title: lang('userSession'),
			region: 'center',
			layout:'fit',
			width: 180,
			minWidth: 80,
			maxWidth: 200,
			bodyStyle: 'border-right-width:1px;',
			items: [this.deptTree2],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					{
						text: lang('expand'),
						iconCls: 'icon-expand-all',
						id: this.ID_btn_collapseExpand2,
						tooltip: lang('expandMenuTip'),
						enableToggle: true,
						toggleHandler: function(th, pressed){
							if(pressed){
								//展开
								th.setIconClass("icon-collapse-all");
								th.setText(lang('collapse'));
								th.setTooltip(lang('collapseMenuTip'));
								_this.treeRoot2.expand(true);
							}else{
								//折叠
								th.setIconClass("icon-expand-all");
								th.setText(lang('expand'));
								th.setTooltip(lang('expandMenuTip'));
								_this.treeRoot2.collapse(true);
								_this.treeRoot2.firstChild.expand();
							}
						}
					},
					"->",
					//刷新tree
					{
						handler : function(button, event) {
							this.reloadDeptData();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					}
				]
			})
		});
		
		this.ddtoCenterPanel = new Ext.Panel({
			region: 'center',
			layout: "border",
			hideBorders: true,
			bodyStyle: 'border-left-width:1px;',
			items: [this.deptPanel2]
		});
		
		this.ddmainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: true,
			items: [this.deptPanel, this.ddtoCenterPanel]
		});
		
		this.groupFields = [
			{name: 'id'},
			{name: 'uid'},
			{name: 'uname'},
			{name: 'theme'},
			{name: 'isdelete'}
		];
		
		this.groupStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&action=mgr&task=getGroupList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.groupFields})
		});
		this.groupStore.load();
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		});
		
		this.groupColModel = new Ext.grid.ColumnModel([
			//this.sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: "", dataIndex: 'uid', sortable: false,menuDisabled :true, hidden: true},
			{header: 'uname', dataIndex: 'uname', hidden: true},
			{header: lang('discussGroup'), dataIndex: 'theme', width: 210, sortable: true, menuDisabled :true, renderer: this.makeOper.createDelegate(this)}
		]);
		
		this.ddGroupGridPanel = new Ext.grid.GridPanel({
			border: false,
			hideBorders: false,
			autoScroll: true,
			region: 'center',
			layout: 'fit',
			store:this.groupStore,
			cm: this.groupColModel,
			//sm: this.sm,
       		stripeRows : true,
			viewConfig: {
				forceFit: true
			},
			bbar: [
				{
					text:lang('refresh'),
					iconCls:'icon-system-refresh',
					handler:function(button, event){
						_this.groupStore.reload();
					}
				}
			],
			listeners: {
				'rowclick': function(grid, rowIndex, e){
					var record = _this.ddGroupGridPanel.getStore().getAt(rowIndex);
					_this.store.load({params:{task: "getHistory", gid: record.data.id}});
				}
			}
		});
		
		this.ddGroupMainPanel = new Ext.Panel({
			width: 280,
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: true,
			items: [this.ddGroupGridPanel]
		});
		
		this.ddPanel = new Ext.Panel({
			width: 380,
			minWidth: 400,
			maxWidth: 450,
			region: 'west',
			layout:'card',
			activeItem: 0,
			hideBorders: true,
			bodyStyle: 'border-left-width:1px;',
			items: [this.ddmainPanel, this.ddGroupMainPanel],
			tbar: [
				{
					enableToggle: true,
				    allowDepress: false,
					pressed: true,
				    toggleGroup: "CHAT_RECORD_VIEW",
					iconCls: 'icon-roduction',
					text: lang('linkMan'),
					handler : function(){
						_this.ddPanel.getLayout().setActiveItem(0);
						_this.store.load({params:{task: "getHistory", fuid: 0, tuid: 0}});
						var title = lang('clickToViewRecord');
						Ext.getCmp(_this.ID_chattingWith).getEl().update(title);
						
						//清除grid中的选中行
						_this.ddGroupGridPanel.getSelectionModel().clearSelections();
					}
				},
				{
					enableToggle: true,
				    allowDepress: false,
				    toggleGroup: "CHAT_RECORD_VIEW",
					iconCls: 'icon-roduction',
					text: lang('discussGroup'),
					handler : function(){
						_this.ddPanel.getLayout().setActiveItem(1);
					 	var title = lang('clickToViewGroupRecord');
						
						Ext.getCmp(_this.ID_chattingWith).getEl().update(title);
					}
				}
			]
		});

		this.toCenterPanel = new Ext.Panel({
			region: 'center',
			layout: "border",
			hideBorders: true,
			bodyStyle: 'border-left-width:1px;',
			items: [this.centerPanel]
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border', 
			autoScroll: true,
			listeners:{
				afterlayout: function(){
					//alert(11);
				}
			},
			items: [this.ddPanel, this.toCenterPanel]
		});
	},
	
	exportHistory : function(){
		var _this = this;
		//提交给后台
		Ext.Ajax.request({
			url: _this.baseUrl + "&action=mgr&task=exportHistory",
			method: 'POST',
			params: {uid: _this.newSelectUid, step: 1},
			success: function(r) {
				ajaxDownload(_this.baseUrl + "&action=mgr&task=exportHistory&uid="+_this.newSelectUid+"&file=" + r.responseText);
			}
		});
	},

	renderContent : function(value, metadata, record){ 
		metadata.attr = 'style="white-space:normal;"'; 
		return value; 
	},
	
	//重新加载部门列表数据
	reloadDeptData : function(){
		//this.deptTree.getEl().mask(lang('waiting'));
		this.treeRoot2.reload();
		this.treeRoot.reload();
	},
	
	openHistory : function(){
		var _this = this;

		var node1 = this.deptTree.getSelectionModel().getSelectedNode();
		var node2 = this.deptTree2.getSelectionModel().getSelectedNode();

		if(!node1 || !node2){
			return;
		}
		if((node1 == undefined) || node1.disabled || (node1.attributes.uid == undefined)){
			return;
		}
		if((node2 == undefined) || node2.disabled || (node2.attributes.uid == undefined)){
			return;
		}
		var fuid = node1.attributes.uid;
		var tuid = node2.attributes.uid;
		if(fuid == tuid){
			return;
		}
		
		var title = node1.text + lang('and') + node2.text + lang('sessionRecording');
		
		Ext.getCmp(this.ID_chattingWith).getEl().update(title);
		///Ext.getCmp(this.ID_exportBtn).show();

		this.store.load({params:{task: "getHistory", fuid: fuid, tuid: tuid}});
	},
	
	makeOper: function(value, metadata, record){
		var _this = this;
		var rd = record.data;
		var l = '';
		if(rd.isdelete == 1){
			l += value+'<span style="color: red">' + lang('retired') + '</span>';
		}else{
			l += value;
		}
		return l;
	}
}


