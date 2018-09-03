var CNOA_flow_table_viewClass, CNOA_flow_table_view;

/**
* 主面板-列表
*
*/
CNOA_flow_table_viewClass = CNOA.Class.create();
CNOA_flow_table_viewClass.prototype = {
	init : function(){
		var _this = this;

		this.ulid = CNOA.flow.table.view.ulid;
		this.ID_goto_nextstep = Ext.id();
		
		this.baseUrl = "index.php?app=flow&func=table&action=list";
		
		this.flowPanelLoaded = this.formPanelLoaded = this.progressPanelLoaded = this.eventPanelLoaded = false;

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		this.ID_printArea = Ext.id();
		
		/*
		 * 工作表单
		 */
		this.formPanel = new Ext.form.FormPanel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			bodyStyle: "background-color:#FFF",
			border: false,
			frame: true,
			waitMsgTarget: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			autoScroll: true,
			title: "工作表单",
			listeners : {
				afterrender : function(p){
					_this.formPanelLoaded = true;
					_this.view_loadFlowInfo();
				}
			}
		});

		this.centerPanel = new Ext.Panel({
			bodyStyle: "padding:10px",
			border: false,
			autoScroll: true,
			region: "center",
			items: [
				{
					xtype: 'container',
					autoEl: 'div',
					id: this.ID_printArea,
					items: [this.formPanel]
				}
			]
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel],
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-dialog-cancel',
						text : lang('close'),
						handler : function(button, event) {
							_this.closeTab();
						}.createDelegate(this)
					},'-',
					{
						//id: this.ID_btn_refreshList,
						handler : function(button, event) {
							_this.printFlow();
							//printArea(_this.ID_printArea, "流程打印");
						}.createDelegate(this),
						hidden: Ext.isAir ? true : false,
						iconCls: 'icon-print',
						tooltip: "打印",
						text : "打印"
					}
				]
			})
		});
	},
	
	view_loadFlowInfo : function(){
		var _this = this;
		
		if(!this.formPanelLoaded){
			return false;
		}

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=show_loadFlowInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					//工作表单
					_this.formPanel.body.update(result.data.formInfo);
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},

	closeTab : function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_TABLE_VIEW");
	},
	
	printFlow : function(){
		var exportArray = Ext.encode({"i":0,"f":1,"p":0,"e":0,"r":0});
		window.open("index.php?app=flow&func=flow&action=user&task=exportFlow&target=printer&ulid="+this.ulid+"&exportArray="+exportArray, '_blank', 'width=740,height='+(screen.availHeight-120)+',left='+((screen.availWidth-740)/2)+',top=60,scrollbars=yes,resizable=yes,status=no');
	}
}
