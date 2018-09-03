var CNOA_flow_flow_settingflow_design_setNode, CNOA_flow_flow_settingflow_design_setNodeClass;
/**
* 添加/修改流程设计
*
*/
var flowDesignWinMgr = new Ext.WindowGroup();

CNOA_flow_flow_settingflow_designClass2 = CNOA.Class.create();
CNOA_flow_flow_settingflow_designClass2.prototype = {
	init: function(flowId, flowName){
		var _this = this;
		this.flowId = flowId;
		this.flowName = flowName;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=setting";
		
		this.ID_CNOA_flow_flow_setting_design_ct = Ext.id();
		this.ID_btn_addedit_save = Ext.id();
		
		this.treeRoot = new Ext.tree.TreeNode({
			expanded: true
		}); 
		this.treePanel = new Ext.tree.TreePanel({
			title: "步骤概览",
			root: this.treeRoot,
			border: false,
			id: "flow_flow_design",
			//useArrows: true,
			rootVisible: false
		});
		
		this.flowInfoPanel = new Ext.Panel({
			title: '流程信息',
			border: false
		});
		
		this.rightPanel = new Ext.TabPanel({
			border: false,
			region: "east",
			width: 180,
			minWidth: 180,
			maxWidth: 380,
			split: true,
			autoScroll:true,
			activeTab: 1,
			bodyStyle: "padding-top:4px;border-left-width: 1px;",
			style: '',
			items: [this.flowInfoPanel, this.treePanel]
		});
		
		this.centerPanel = new Ext.Panel({
			border: false,
			region: "center",
			autoScroll: true,
			bodyStyle: "background:#FFF url('./resources/images/mxgraph/grid.gif');border-right-width: 1px;",
			items: [
				{
					xtype: 'flash',
					url: './others/flowDesigner/html/flowDesigner.swf',
					border: false,
					id: 'WORKFLOWDESIGNER',
					anchor: '100% 100%'
				}
			],
			listeners: {
				afterrender: function(th){
					//_this.readFlowData();
				}
			}
		});
		
		var box = Ext.getBody().getBox();
		var w	= box.width - 60;
		var h	= box.height - 60;
		
		this.mainPanel = new Ext.Window({
			title: "设计流程 - " + this.flowName,
			width: w,
			height: h,
			layout: "border",
			modal: true,
			manager: flowDesignWinMgr,
			maximizable: true,
			resizable: true,
			items: [this.centerPanel, this.rightPanel],
			buttons: [
				{
					text: "删除节点",
					iconCls: 'icon-order-s-accept',
					handler: function(){
						document.getElementById('WORKFLOWDESIGNER').as_api_node_delete('我删除了节点');
					}
				},{
					text: "连接节点",
					iconCls: 'icon-order-s-accept',
					handler: function(){
						document.getElementById('WORKFLOWDESIGNER').as_api_node_link('我连接了节点');
					}
				},'-',{
					text: lang('save'),
					id: _this.ID_btn_addedit_save,
					iconCls: 'icon-order-s-accept',
					handler: function(){
						//_this.submitFlowData();
					}
				},   //关闭
				{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						_this.close();
					}
				}
			],
			listeners: {
				close : function(){
					
				},
				afterrender: function(th){
					th.el.on("contextmenu", function(e) {
						e.preventDefault();  
					}, this);
				}
			}
		});
		
		
	},
	
	show: function(){
		this.mainPanel.show();
	},
	
	close: function(){
		this.mainPanel.close();
	},
	
	showNodeInfo : function(nodeId){
		alert("[api_node_click][ID:"+nodeId+"]");
	},
	
	/**
	 * 单击节点时，在右边显示此节点的信息
	 * @param {Object} nodeId 步骤ID
	 */
	api_node_click : function(nodeId){
		this.showNodeInfo(nodeId);
	},
	
	/**
	 * 在设计器中，添加一个节点时，在JS也相应增加一个节点数据
	 * @param {Object} nodeId 步骤ID
	 * @param {Object} name
	 */
	api_node_add : function(nodeId, name){
		alert("[api_node_add][ID:"+nodeId+"][ID:"+name+"]");
	},
	
	/**
	 * 在设计器中，右键单击“设计步骤”，显示步骤设置界面
	 * @param {Object} nodeId 步骤ID 
	 */
	api4as_node_edit : function(FlexObject){
		CNOA_flow_flow_settingflow_design_setNode = new CNOA_flow_flow_settingflow_design_setNodeClass(FlexObject);
	},
	
	/**
	 * 在设计器中，删除一个节点，在JS中也相应删除此节点及其下级节点
	 * @param {Object} nodeId 步骤ID
	 */
	api_node_delete : function(nodeId){
		alert("[api_node_delete][ID:"+nodeId+"]");
	}
}

CNOA_flow_flow_settingflow_design_setNodeClass = CNOA.Class.create();
CNOA_flow_flow_settingflow_design_setNodeClass.prototype = {
	/*
	FlexObject:
		allNodes: Array: [{id: "task_1", label: "步骤1"}]
		childNodes: Array: [{id: "task_1", label: "步骤1"}]
		curNode: Object: {className: "StartEvent", id: "startNode", label: "发起流程", linkToEnd: true}
	*/
	init: function(FlexObject){
		var _this = this;

		//生成可勾选组件，用于连接下一级节点
		var childrenNodes = new Array();
		Ext.each(FlexObject.allNodes, function(v){
			var linked = false;
			Ext.each(FlexObject.childNodes, function(v1){
				if(v1.id == v.id){
					linked = true;
				}
			});
			alert(linked);
			childrenNodes.push({xtype: "checkbox", boxLabel: v.label, name: 'linkTo', value: v.id, checked: linked});
		});
		
		var formPanel = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 100,
			labelAlign: 'top',
			waitMsgTarget: true,
			defaults: {
				width: 300
			},
			items: [
				{
					xtype: "textfield",
					fieldLabel: "步骤ID",
					readOnly: true,
					value: FlexObject.curNode.id
				},
				{
					xtype: "textfield",
					fieldLabel: "步骤类型",
					readOnly: true,
					value: FlexObject.curNode.className
				},
				{
					xtype: "textarea",
					fieldLabel: "步骤名称",
					name: "nodeName",
					value: FlexObject.curNode.label
				},
				{
					xtype: "textarea",
					fieldLabel: "已连接的子节点",
					value: (function(){
						var str = "";
						Ext.each(FlexObject.childNodes, function(v, i){
							str += "ID:"+v.id+" | Label:" + v.label + "\n";
						});
						return str;
					})()
				},
				{
					xtype: "textarea",
					fieldLabel: "所有节点(除了自己)",
					value: (function(){
						var str = "";
						Ext.each(FlexObject.allNodes, function(v, i){
							str += "ID:"+v.id+" | Label:" + v.label + "\n";
						});
						return str;
					})()
				},
				{
					xtype: 'panel',
					fieldLabel: '连接节点',
					width : 300,
					layout: "table",
					items: childrenNodes
				},
				{
					xtype: "checkbox",
					name: "linkToEnd",
					checked: FlexObject.curNode.linkToEnd,
					fieldLabel: "是否结束"
				}
			]
		});
		
		var box = Ext.getBody().getBox();
		var w	= box.width - 100;
		var h	= box.height - 100;
		
		var win = new Ext.Window({
			title: "设置步骤",
			//width: w,
			//height: h,
			width: 350,
			height: makeWindowHeight(600),
			padding: 10,
			layout: "fit",
			modal: true,
			closeAction: "close",
			maximizable: true,
			resizable: true,
			manager: flowDesignWinMgr,
			items: [formPanel],
			buttons: [
				{
					text: lang('ok'),
					iconCls: 'icon-btn-save',
					handler: function(){
						submit();
					}
				},
				{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			],
			listeners: {
				show : function(th){
					
				}
			}
		}).show();

		var submit = function(){
			var f = formPanel.getForm();

			FlexObject.curNode.label2	 = f.findField("nodeName").getValue();
			FlexObject.curNode.linkToEnd = f.findField("linkToEnd").getValue();

			var setting = {
				currentNode: FlexObject.curNode
			};
			document.getElementById('WORKFLOWDESIGNER').JS2AS_NODE_EDIT(setting);
			win.close();
		}
	}
};


