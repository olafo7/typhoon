//定义全局变量：
var CNOA_main_setMyMenuClass, CNOA_main_setMyMenu;

/**
* 主面板-列表
*
*/
CNOA_main_setMyMenuClass = CNOA.Class.create();
CNOA_main_setMyMenuClass.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = "index.php?action=commonJob&act=myMenu";
		
		var myData = {
			data: []
		};
		      
		this.fields = [
			{name: 'name'},
			{name: 'language'},
			{name: 'id'},
			{name: 'iconCls'},
			{name: 'autoLoadUrl'},
			{name: 'clickEvent'},
			{name: 'newName'}
		]; 
    	
		this.firstGridStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getMyMenuGrid", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});
		this.firstGridStore.load();
		//this.firstGridStore = new Ext.data.JsonStore({
		//	fields: this.fields,
		//	data: myData,
		//	root: 'data'
		//}); // Column Model shortcut array      
		this.cols = [
			{header: lang('menuName'),width: 160,sortable: true,dataIndex: 'name',id: 'name',renderer:this.makeOpt.createDelegate(this)},
			{header: lang('customName'),width: 220,sortable: true,dataIndex: 'newName'},
			{header: lang('opt'),width: 100,sortable: true,dataIndex: 'id',renderer:this.makeDel.createDelegate(this)}
		];

		this.grid = new Ext.grid.GridPanel({
			ddGroup: 'secondTreeDDGroup',
			store: this.firstGridStore,
			columns: this.cols,
			//enableDragDrop   : false,          
			stripeRows: true,
			isTarget: true,
			autoExpandColumn: 'name',
			region: 'center',
			enableDragDrop: true,
			bodyStyle: 'border-left-width:1px',
			dropConfig: {
				appendOnly:true
			},
			listeners: {
				afterrender: function(grid){
					//定义从树拖到表格
					var blankRecord = Ext.data.Record.create(_this.fields); //Simple 'border layout' panel to house both grids
					var secondGridDropTargetEl = _this.grid.getView().el.dom.childNodes[0].childNodes[1]
					var destGridDropTarget = new Ext.dd.DropTarget(grid.getEl(), {
						ddGroup: 'secondTreeDDGroup',
						copy: false,
						notifyDrop: function(ddSource, e, data) {
							if (ddSource.grid) {
								// 选中了多少行
								//debugger
								var rows = data.selections;
								// 拖动到第几行
								var index = ddSource.getDragData(e).rowIndex;
								if (typeof(index) == "undefined") {
									return;
								}
								
								//修改store
								for(i = 0; i < rows.length; i++) {
									var rowData = rows[i];
									if(!this.copy) _this.firstGridStore.remove(rowData);
									
									if(index== 0){
										rowData.data.orderNum -=1 ;
									}else if(index == _this.firstGridStore.data.items.length){
										rowData.data.id = _this.firstGridStore.data.items[index-1].data.id+1;
									}else{
										rowData.data.id = (_this.firstGridStore.data.items[index-1].data.id + _this.firstGridStore.data.items[index].data.id)/2
									}
									_this.firstGridStore.insert(index, rowData);
								}
							}else{
								var inList = false;
								Ext.each(_this.firstGridStore.data.items, function(v){
									if(v.data.id == ddSource.dragData.node.attributes.id){
										inList = true;
									}
								});
								if(!inList){
									var record = new blankRecord({
										name: ddSource.dragData.node.attributes.text,
										id: ddSource.dragData.node.attributes.id,
										language: ddSource.dragData.node.attributes.language,
										iconCls: ddSource.dragData.node.attributes.iconCls,
										autoLoadUrl: ddSource.dragData.node.attributes.autoLoadUrl,
										clickEvent: ddSource.dragData.node.attributes.clickEvent
									});
									_this.firstGridStore.add(record);
								}else{
									CNOA.msg.notice("\""+ddSource.dragData.node.attributes.text+"\" " + lang('haveAdded'));
								}
							}
							return (true);
						}
					});
				}
			}
		});
		
		this.tree = new Ext.tree.TreePanel({
			autoScroll: true,
			animate: true,
			enableDD: true,
			width: 270,
			ddGroup: 'secondTreeDDGroup',
			containerScroll: true,
			region: 'west',rootVisible: false,
			root: new Ext.tree.AsyncTreeNode({
				text: 'main menu',
				draggable: false,
				id: 'source'
			}),
			loader: new Ext.tree.TreeLoader({
				dataUrl: this.baseUrl + '&task=loadMenuAll'
			})
		});
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.tree, this.grid],
			tbar: [
				{
					text : lang('save'),
					id: this.ID_btn_save,
					iconCls: 'icon-order-s-accept',
					handler : function() {
						this.saveMenu();
					}.createDelegate(this)
				},
				{
					text: lang('expand'),
					iconCls: 'icon-expand-all',
					tooltip: lang('expandMenuTip'),
					enableToggle: true,
					toggleHandler: function(th, pressed){
						if(pressed){
							//展开
							th.setIconClass("icon-collapse-all");
							th.setText(lang('collapse'));
							th.setTooltip(lang('collapseMenuTip'));
							_this.tree.getRootNode().expand(true);
						}else{
							//折叠
							th.setIconClass("icon-expand-all");
							th.setText(lang('expand'));
							th.setTooltip(lang('expandMenuTip'));
							_this.tree.getRootNode().collapse(true);
							_this.tree.getRootNode().firstChild.expand();
						}
					}
				},'&nbsp;&nbsp;&nbsp;&nbsp;操作说明：1.拖动菜单到右边的表格[我的菜单] 2.拖动右边的菜单可进行排序 3.点击左边的保存按钮'
			]
		});
	},
	
	makeOpt : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var conHTML="<span style='width:16px;height:16px;display:block;float:left;margin-right:2px;' class='"+record.data.iconCls+"'></span>"+value;
		return conHTML;
	},
	
	makeDel : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var conHTML  ="<a href='javascript:void(0)' onclick='CNOA_main_setMyMenu.deleteMenu(\""+record.id+"\")'>" + lang('del') + "</a>";
			conHTML +="&nbsp;&nbsp;";
			conHTML +="<a href='javascript:void(0)' onclick='CNOA_main_setMyMenu.renameMenu(\""+record.id+"\")'>" + lang('rename') + "</a>";
		return conHTML;
	},

	closeTab : function(){
		mainPanel.closeTab(CNOA.main.setMyMenu.parentID.replace("docs-", ""));
	},

	deleteMenu : function(recordId){
		var target = this.firstGridStore.getById(recordId);
	    this.firstGridStore.remove(target);
	},
	
	renameMenu : function(recordId){
		var operateWinow = new Ext.MessageBox.promptWindow({
			title: lang('customName'),
			multiline: false,
			border: false,
			height: 100,
			txCfg: {
				value: (function(){
					var nn = this.firstGridStore.getById(recordId).get("newName");
					var on = this.firstGridStore.getById(recordId).get("name");
					if(nn != ''){
						return nn;
					}else{
						return on;
					}
				}).createDelegate(this)()
			},
			listeners : {
				submit : function(th, value){
					this.firstGridStore.getById(recordId).set("newName", value);
					th.close();
				}.createDelegate(this)
			}
		}).show();
	},
	
	saveMenu : function(){
		var data = new Array();
		Ext.each(this.firstGridStore.data.items, function(v){
			data.push({
				iconCls: v.data.iconCls,
				id: v.data.id,
				name: v.data.name,
				language: v.data.language,
				autoLoadUrl: v.data.autoLoadUrl,
				clickEvent: v.data.clickEvent,
				newName: v.data.newName
			});
		});
		
		Ext.Ajax.request({  
			url: this.baseUrl + '&task=setMyMenu',
			method: 'POST',
			params: {task: "setMyMenu", data: Ext.encode(data)},
			success: function(r) {
				Ext.each(data, function(v, i){
					if(!Ext.isEmpty(v.newName)){
						data[i].name = v.newName
					}
				});
				mainPanel.setMenu(data);
				CNOA.msg.notice(lang('successopt'), lang('myMenu'));
			}
		});
	}
}

Ext.onReady(function(){
	CNOA_main_setMyMenu = new CNOA_main_setMyMenuClass();
	Ext.getCmp(CNOA.main.setMyMenu.parentID).add(CNOA_main_setMyMenu.mainPanel);
	Ext.getCmp(CNOA.main.setMyMenu.parentID).doLayout();
});
