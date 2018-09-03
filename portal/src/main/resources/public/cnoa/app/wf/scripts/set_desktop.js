/**
 * 全局变量
 */
var CNOA_wf_set_desktopClass, CNOA_wf_set_desktop;
CNOA_wf_set_desktopClass = CNOA.Class.create();
CNOA_wf_set_desktopClass.prototype = {
	init : function(){
		var _this = this;
		
		var ID_SEARCH_TEXT_NAME = Ext.id();
		
		this.baseUrl = "index.php?app=wf&func=flow&action=setdesktop";
		
		this.storeBar = {
			sname : ""
		};
		
		this.sortId = 0;
		
		this.fields = [
			{name : "sortId"},
			{name : "text"},
			{name : "show"}
		];
		
		this.store = new Ext.data.GroupingStore({
			autoLoad:true,
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getJsonData"}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
			listeners: {
				'update': function(thiz, record, operation) {
					var data = record.data;
					if (operation == Ext.data.Record.EDIT) {//判断update时间的操作类型是否为 edit 该事件还有其他操作类型比如 commit,reject   
						_this.submit(data);
					}
				}
			}
		});
		
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,   
			store: this.store,
			pageSize:15,
			listeners:{
				"beforechange" : function(th, params){
					Ext.apply(params, _this.storeBar);
				}
			}
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: true
		});
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "sortId", dataIndex: 'sortId', hidden: true},
			{header: '分类名称', dataIndex: 'text', width: 250, sortable: true, menuDisabled :true},
			{header: '是否显示在桌面', dataIndex: 'show', width: 100, sortable: true, menuDisabled :true, renderer : function(value, meta, record){
				var h = "否";
				if(value == '1'){
					h = "<span class='cnoa_color_red'>是</span>";
				}
				return h;
			}},
			{header: lang('opt'), dataIndex: 'sortId', width: 60, sortable: true, menuDisabled :true, renderer : function(value, meta, record){
				var text = record.data.text;
				return "<a href='javascript:void(0)' onclick='CNOA_wf_set_desktop.edit("+value+")'>修改</a>";
			}},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.grid = new Ext.grid.GridPanel({
			region : 'center',
			layout : "fit",
			border : false,
			store : _this.store,
			loadMask : {msg: lang('waiting')},
			cm : this.colModel,
			sm : this.sm,
       		stripeRows : true,
			hideBorders : true,
			bbar : this.pagingBar,
			view: new Ext.grid.GroupingView({
				markDirty: false
			}),
			tbar : [
				{
					text : lang('refresh'),
					iconCls:"icon-system-refresh",
					handler:function(){
						_this.store.reload();
					}
				},
				'-',"设置是否生成该分类的桌面待审批列表"
			],
			listeners : {
				cellclick : function(thiz, rowIndex, columnIndex, e){
					if(columnIndex == 3 || columnIndex == 1 || columnIndex == 0){
						return false;
					}
				}
			}
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible : false,
			hideBorders : true,
			border : false,
			layout : 'border', 
			autoScroll : false,
			items : this.grid
		});
	},

	edit : function(sortId){
		var _this = this;
		
		CNOA.msg.cf("是否修改状态？", function(btn){
			if(btn == 'yes'){
				Ext.Ajax.request({
				    url: _this.baseUrl + "&task=editShow",
				    method: 'POST',
					params : {sortId : sortId},
				    success: function(r) {
				        var result = Ext.decode(r.responseText);
				        if(result.success === true){
							CNOA.msg.notice2(result.msg);
				            _this.store.reload();
				        }else{
				            CNOA.msg.alert(result.msg, function(){});
				        }
				    }
				});
			}
		});
	}
}


