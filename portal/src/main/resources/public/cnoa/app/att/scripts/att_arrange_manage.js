//定义全局变量：
var CNOA_att_arrange_manageClass, CNOA_att_arrange_manage;
CNOA_att_arrange_manageClass = CNOA.Class.create();
CNOA_att_arrange_manageClass.prototype = {
	init: function(){
		this.baseUrl = "index.php?app=att&func=arrange&action=manage",
		
		this.arrangePanel = this.getArrangePanel();
		this.deptPanel = this.getDeptPanel();
		
		this.mainPanel = new Ext.Container({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border', 
			autoScroll: true,
			items: [this.deptPanel, this.arrangePanel]
		});
	},
	
	//排班面板
	getArrangePanel: function(){
		var me = this;
		
		var fields =[
			{name: 'sid'},
			{name: 'name'},
			{name: 'sun'},
			{name: 'mon'},
			{name: 'tue'},
			{name: 'wed'},
			{name: 'thurs'},
			{name: 'fri'},
			{name: 'sat'}
		];
		
		var store = new Ext.data.Store({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({url: me.baseUrl + "&task=getArrange", disableCaching: true}),   
			reader: new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})
		});
		
		var sm = new Ext.grid.CheckboxSelectionModel();
		var columns = [new Ext.grid.RowNumberer(), {header: '人员'}];
		
		var date = new Date(), 
			month = date.getMonth(),
			days = date.getDaysInMonth(),
			week = new Date(date.getFullYear(), month, 1).getDay();
		for (var i=1; i <= days; i++){
			columns.push({header: me.get2Number(month + 1) + '-' + me.get2Number(i) + '<br/>' + me.getWeek(week)});
			week++;
		}
		
		var colModel = new Ext.grid.ColumnModel({
			defaults:{sortable: true, menuDisabled: true, align: 'center', width: 50},
			columns: columns
		});
		
		var grid = new Ext.grid.EditorGridPanel({
			region: 'center',
			border: false,
			loadMask: {msg: lang('loading')},
			enableDragDrop: true,
			store: store,
			cm: colModel,
			sm: sm,
			tbar: new Ext.Toolbar({
				items: [
					{
						text:lang('refresh'),
						iconCls:'icon-system-refresh',
						handler:function(){
							list.store.reload();
						}
					}
				]
			})
		});
		
		return grid;
	},
	
	//部门
	getDeptPanel: function(){
		var treeRoot = new Ext.tree.AsyncTreeNode({
			id: this.ID_tree_treeRoot,
			expanded: true
		});
		
		var treeLoader = new Ext.tree.TreeLoader({
			dataUrl: "index.php?app=main&func=job&action=list&task=getStructTree",
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
//				"load":function(node){
//					this.treeRoot.firstChild.collapse(true);
//
//					//判断折叠展开按钮的状态
//					if(Ext.getCmp(this.ID_btn_collapseExpand).pressed){
//						this.treeRoot.expand(true);
//					}else{
//						this.treeRoot.firstChild.expand();
//					}
//
//					var treeState = Ext.state.Manager.get("CNOA_main_job_index_treeState");
//					if (treeState){this.deptTree.selectPath(treeState);}
//
//					this.deptTree.getEl().unmask();
//				}.createDelegate(this)
			}
		});
		
		return new Ext.tree.TreePanel({
			region: 'west',
			split: true,
			width: 180,
			rootVisible: false,
			lines: true,
			animCollapse: false,
			animate: false,
			autoScroll: true,
			bodyStyle: 'border-right-width:1px;',
			loader: treeLoader,
			root: treeRoot,
			tbar: new Ext.Toolbar({
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
					}, "->", {
						handler : function(button, event) {
							this.reloadDeptData();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					}
				]
			}),
			listeners:{
				"click": function(node){
					//如果节点不可用
					if(node.disabled){
						return;
					}

					this.newSelectDeptId = node.attributes.selfid;

					this.store.load();
					//记录选中状态
					Ext.state.Manager.set("CNOA_main_job_index_treeState", node.getPath());
				}
			}
		});
	},
	
	//获取星期数
	getWeek: function(index){
		index = index % 7;
		switch (index){
			case 0:
				return '<span style="color:#FF7F00">星期日</span>';
			case 1:
				return '星期一';
			case 2:
				return '星期二';
			case 3:
				return '星期三';
			case 4:
				return '星期四';
			case 5:
				return '星期五';
			case 6:
				return '星期六';
			default:
				return '';
		}
	},
	
	get2Number: function(number){
		return number < 10 ? '0' + number : number;
	}
	
};

CNOA_att_arrange_manage = new CNOA_att_arrange_manageClass();
Ext.getCmp(CNOA.att.arrange.manage.parentID).add(CNOA_att_arrange_manage.mainPanel);
Ext.getCmp(CNOA.att.arrange.manage.parentID).doLayout();
