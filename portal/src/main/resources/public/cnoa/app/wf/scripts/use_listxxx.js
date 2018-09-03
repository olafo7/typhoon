var CNOA_wf_use_listClass, CNOA_wf_use_list;

CNOA_wf_use_listClass = CNOA.Class.create();
CNOA_wf_use_listClass.prototype = {
	init : function(){
		var _this = this;
		
		this.ID_SEARCH_TEXT_NAME = Ext.id();
		this.ID_FAVNOTICE		 = Ext.id();
		this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new";
		
		this.storeBar = {
			flowName:"",
			sortId:0,
			from:'normal'
		};
		
		this.nowSortType = 'normal';
		
		this.fields = [
			{name:"flowId"},
			{name:"flowName"},
			{name:"sname"},
			{name:"about"},
			{name:"abouttip"},
			{name:"nameRuleId"},
			{name:"tplSort"},
			{name:"flowType"},
			{name:"childId"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
			listeners:{
				load: function(th, record, e){
					if(_this.storeBar.from == 'normal'){
						Ext.getCmp('ID_BTN_WF_NEED_FLOW_CHILD').setText("需要我发起的子流程("+th.reader.jsonData.childTotal+")");
					}
				}
			}
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();

		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "flowId", dataIndex: 'flowId', hidden: true},
			{header: lang('collect'), dataIndex: 'flowId', width: 60, sortable: false,menuDisabled :true,renderer: this.makeFav.createDelegate(this)},
			{header: lang('type'), dataIndex: 'tplSort',width: 36, sortable: true, menuDisabled :true, renderer: this.tplSortShow.createDelegate(this)},
			{header: lang('flowName'), dataIndex: 'flowName',id: 'flowName', width: 140, sortable: true, menuDisabled :true},
			{header: lang('sort2'), dataIndex: 'sname', width: 140, sortable: false,menuDisabled :true},
			{header: lang('flowDescription'), dataIndex: 'about', width: 100, sortable: false,menuDisabled :true, renderer: function(v, md, record){
				var rd = record.data;
				return "<span ext:qtip='"+rd.abouttip+"'>"+v+"</span>";
			}},
			{header: lang('view'), dataIndex: 'flowId', width: 120, sortable: false,menuDisabled :true, renderer: this.viewOperate.createDelegate(this)},
			{header: lang('opt'), dataIndex: 'flowId', width: 60, sortable: false,menuDisabled :true,renderer: this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			store: this.store,
			style: 'border-left-width:1px;',
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					Ext.apply(params, _this.storeBar);
				}
			}
		});
		
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			bodyStyle: 'border-left-width:1px;',
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			region: "center",
			autoExpandColumn: 'flowName',
			stripeRows : true,
			enableDragDrop: true,
			dropConfig: {
				appendOnly:true
			},
			ddGroup: "GridDD",
			tbar : new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : lang('refresh'),
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},
					{
						xtype:'button',
						text:lang('needIlaunch'),
						iconCls:'icon-ui-combo-box-blue',
						id:'ID_BTN_WF_NEED_FLOW_CHILD',
						enableToggle:true,
						toggleHandler:function(th,status){
							if(status){
								_this.storeBar.from = 'need';
							}else{
								_this.storeBar.from = 'normal';
							};
							_this.store.load({params:_this.storeBar});
						}
					},
					{
						xtype: 'box',
						id: this.ID_FAVNOTICE,
						autoEl: {
							tag: 'div',
							html: '',
							style: 'margin-left:20px;color:#333333'
						}
					},
					"->",
					(lang('flowName') + ":"),
					{
						xtype : "textfield",
						width : 200,
						id : this.ID_SEARCH_TEXT_NAME,
						listeners:{
							specialkey:function(field, e){
								if(e.getKey() == e.ENTER){
									_this.storeBar.flowName = Ext.getCmp(_this.ID_SEARCH_TEXT_NAME).getValue();
									_this.store.load({params : _this.storeBar});
								};
							}
						}
					},
					{
						xtype: "button",
						text: lang('search'),
						style: "margin-left:5px",
						iconCls: "icon-search",
						handler: function(){
							_this.storeBar.flowName = Ext.getCmp(_this.ID_SEARCH_TEXT_NAME).getValue();
							_this.store.load({params : _this.storeBar});
						}
					},
					{
						xtype : "button",
						text : lang('clear'),
						handler : function(){
							Ext.getCmp(_this.ID_SEARCH_TEXT_NAME).setValue("");
							
							_this.storeBar.flowName = "";
							_this.store.load({params : _this.storeBar});
						}
					}
				]
			}),
			bbar: this.pagingBar,
			listeners: {
				afterrender : function(grid){
					//定义拖动
					var ddrow = new Ext.dd.DropTarget(_this.grid.getEl(), {
						ddGroup : 'GridDD',
						copy    : false,
						notifyDrop : function(dd, e, data) {
							if(_this.nowSortType == 'wffav'){
								// 选中了多少行
								//debugger
								var rows = data.selections;
								// 拖动到第几行
								var index = dd.getDragData(e).rowIndex;
								if (typeof(index) == "undefined") {
									return;
								}
								
								// 修改store
								for(i = 0; i < rows.length; i++) {
									var rowData = rows[i];
									if(!this.copy) _this.store.remove(rowData);
									
									if(index== 0){
										rowData.data.orderNum -=1 ;
									}else if(index == _this.store.data.items.length){
										rowData.data.id = _this.store.data.items[index-1].data.id+1;
									}else{
										rowData.data.id = (_this.store.data.items[index-1].data.id + _this.store.data.items[index].data.id)/2
									}
									_this.store.insert(index, rowData);
								}
								
								_this.orderFavFlow();
							}
						}
					});
				}
			}
		});
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			text: lang('processCategoryList'),
			type: '',
			sortId: '0',
			iconCls: "icon-tree-root-cnoa",
			id: this.ID_tree_treeRoot,
			expanded: true
		});
		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl+"&task=getSortList",
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
				"load" : function(node){
					_this.sortTree.selectPath(_this.ID_tree_treeRoot);
				}.createDelegate(this)
			}
		});
		
		this.sortTree = new Ext.tree.TreePanel({
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
				"click":function(node){
					if(node.attributes.from == 'wffav'){
						_this.store.load({params:{sort: "wffav"}});
						_this.grid.getBottomToolbar().hide();
						_this.grid.doLayout();
						
						_this.nowSortType = 'wffav';
						
						Ext.getCmp(_this.ID_FAVNOTICE).getEl().update(lang('operationTip'));
						
						return;
					}
					_this.grid.getBottomToolbar().show();
					_this.grid.doLayout();
					Ext.getCmp(_this.ID_FAVNOTICE).getEl().update('');
					
					_this.storeBar.sortId = node.attributes.sortId;
					_this.store.load({params:_this.storeBar});
					
					_this.nowSortType = 'normal';
				}
			}
		});
		this.sortPanel = new Ext.Panel({
			region: 'west',
			layout:'fit',
			split: true,
			width: 180,
			minWidth: 80,
			maxWidth: 380,
			bodyStyle: 'border-right-width:1px;',
			items: [this.sortTree],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					lang('launchFlow'),
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
			
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			activeItem:0,
			autoScroll: false,
			items: [this.grid, this.sortPanel]
		});
	},
	
	tplSortShow : function(value, c, record){
		var _this = this;
		var rd = record.data;
		var html = "";
		if(rd.tplSort == 0){
			html += '<img src="./resources/images/icons/document-html.png" width="16" height="16" ext:qtip="' + lang('appFlowWebForm') + '" />';
		}else if(rd.tplSort == 1){
			html += '<img src="./resources/images/icons/document-word.png" width="16" height="16" ext:qtip="' + lang('baseWordDoc') + '" />';
		}else if(rd.tplSort == 2){
			html += '<img src="./resources/images/icons/document-excel.png" width="16" height="16" ext:qtip="' + lang('baseOnExcelDoc') + '" />';
		}else{
			html += '<img src="./resources/images/icons/document-html-word.png" width="16" height="16" ext:qtip="' + lang('jieheWordForm') + '" />';
		}
		
		return html;
	},
	
	//查看
	viewOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		var h = "";
		if(rd.flowType == 0){
			h += "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_wf_use_list.viewFlow("+value+", \""+rd.flowName+"\");'><img src='"+src+"ico-flow.gif' style='margin:0 2px 4px 0' align='absmiddle' />" + lang('flowChart') + "</a>";
			h += "&nbsp;&nbsp;&nbsp;";
			if(rd.tplSort == 0 || rd.tplSort == 3){
				h += "<a href='javascript:void(0);' onclick='CNOA_wf_use_list.viewForm("+value+");'><img src='"+src+"ico-worksheet.gif' style='margin:0 2px 4px 0' align='absmiddle' />" + lang('form') + "</a>";
			}
		}
		return h;
	},
	
	//操作
	makeFav : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		
		var h = "";
			
		if(this.nowSortType == 'normal'){
			h += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick='CNOA_wf_use_list.addFavFlow("+value+");' ext:qtip='" + lang('collectProcess') + "'><img src='"+src+"heart.png' style='margin:2px 2px 4px 2px' align='absmiddle' />" + lang('collect') + "</a>";
		}else if(this.nowSortType == 'wffav'){
			h += "&nbsp;&nbsp;<a href='javascript:void(0);' onclick='CNOA_wf_use_list.delFavFlow("+value+");' ext:qtip='" + lang('delCollectionProcess') + "'><img src='"+src+"icon-attac-delete.gif' style='margin:2px 2px 4px 2px' align='absmiddle' />" + lang('del') + "</a>";
		}
		
		return h;
	},
	
	//操作
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		var childId = rd.childId;
		if(childId == undefined || childId == ''){
			childId = 0;
		}
		var h = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_wf_use_list.newFlow("+value+","+rd.nameRuleId+", "+rd.tplSort+", "+rd.flowType+", "+childId+");'><img src='"+src+"flow-new.png' style='margin:2px 2px 4px 2px' align='absmiddle' />" + lang('launch') + "</a>";
		
			h += "</div>";
		
		return h;
	},
	
	//发起自由流程
	/*newfreeflow : function(flowType){
		if(flowType == 0){
			mainPanel.closeTab("CNOA_MENU_WF_USE_NEWFREEFLOW");
			mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=newfree&task=loadPage&from=new", "CNOA_MENU_WF_USE_NEWFREEFLOW", "设置表单和流程", "icon-flow-new");
		}else{
			mainPanel.closeTab("CNOA_MENU_WF_SET_FLOW_MSOFFICE");
			mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=newfree&task=loadPage&from=msoffice&flowType="+flowType, "CNOA_MENU_WF_SET_FLOW_MSOFFICE", "编辑模板", "icon-flow-new");
		}
		
	},*/
	
	//发起固定流程
	newFlow : function(flowId, nameRuleId, tplSort, flowType, childId){
		var _this = this;
		
		if(flowType == 0){
			mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
			mainPanel.loadClass(_this.baseUrl+"&task=loadPage&from=newflow&flowId="+flowId+"&nameRuleId="+nameRuleId+"&flowType="+flowType+"&tplSort="+tplSort+"&childId="+childId, "CNOA_MENU_WF_USE_OPENFLOW", lang('FqNewFixedFlow'), "icon-flow-new");
		}else{
			mainPanel.closeTab("CNOA_USE_FLOW_NEWFREE_FLOWDESIGN");
			mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=newfree&task=loadPage&from=flowdesign&flowId="+flowId+"&flowType="+flowType+"&tplSort="+tplSort+"&childId="+childId, "CNOA_USE_FLOW_NEWFREE_FLOWDESIGN", lang('designFlow1'), "icon-flow-new");
		}
	},
	
	//查看流程图
	viewFlow : function(flowId){
		var _this = this;
		CNOA_wf_use_flowpreview = new CNOA_wf_use_flowpreviewClass(flowId);
	},
	
	//下载查看工作表单
	viewForm : function(flowId){
		var _this = this;
		
		var box = Ext.getBody().getBox();
		var w	= box.width - 100;
		var h	= box.height - 100;
		
		var load = function(){
			Ext.Ajax.request({
				url: _this.baseUrl + "&task=show_loadFormInfo",
				method: 'POST',
				params: {flowId: flowId},
				success: function(r){
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						panel.getEl().update("<center>"+result.data.formHtml+"</center>");
					}else{
						CNOA.msg.alert(result.msg, function(){});
					}
					win.getEl().unmask();
				}
			});
		}
		
		var panel = new Ext.Panel({
			border: false,
			html: lang('workFormLoad') + "...",
			listeners: {
				afterrender : function(p){
					load();
				}
			}
		});
		
		var win = new Ext.Window({
			title: lang('viewJobForm'),
			width: w,
			height: h,
			layout: "fit",
			bodyStyle: "background-color:#FFFFFF",
			modal: true,
			autoScroll: true,
			maximizable: false,
			resizable: false,
			items: [panel],
			buttons: [
				{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			],
			listeners: {
				show : function(win){
					win.getEl().mask(lang('waiting'));
				}
			}
		}).show();
	},
	
	addFavFlow : function(flowId){
		var _this = this;
		
		Ext.Ajax.request({
			url: this.baseUrl + "&task=addFavFlow",
			method: 'POST',
			params: {flowId: flowId},
			success: function(r){
				var result = Ext.decode(r.responseText);
				CNOA.msg.notice(result.msg, lang('collectionFlow'));
				if(result.success === true){
					_this.treeRoot.reload();
				}
			}
		});
	},
	
	delFavFlow : function(flowId){
		var _this = this;
		
		Ext.Ajax.request({
			url: this.baseUrl + "&task=delFavFlow",
			method: 'POST',
			params: {flowId: flowId},
			success: function(r){
				var result = Ext.decode(r.responseText);
				CNOA.msg.notice(result.msg, lang('delCollectionProcess'));
				
				if(result.success === true){
					_this.store.reload();
					_this.treeRoot.reload();
				}
			}
		});
	},
	
	orderFavFlow : function(flowId){
		var _this = this;
		
		var ids = [];
		this.store.each(function(a, b){
			ids.push(a.get('flowId'));
		});
		
		Ext.Ajax.request({
			url: this.baseUrl + "&task=orderFavFlow",
			method: 'POST',
			params: {ids: ids.join(',')},
			success: function(r){
				var result = Ext.decode(r.responseText);
				CNOA.msg.notice(result.msg, lang('sortCollectionFlow'));
			}
		});
	}
}
