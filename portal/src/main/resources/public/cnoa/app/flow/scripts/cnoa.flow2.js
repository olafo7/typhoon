//定义全局变量：
//主列表
var CNOA_flow_flow_settingflowClass, CNOA_flow_flow_settingflow;
//添加/修改
var CNOA_flow_flow_settingflow_addeditClass, CNOA_flow_flow_settingflow_addedit;
//设计
var CNOA_flow_flow_settingflow_designClass, CNOA_flow_flow_settingflow_design;
//管理
var CNOA_flow_flow_settingflow_mgrClass, CNOA_flow_flow_settingflow_mgr;
/**
* 主面板-添加/修改
*
*/
CNOA_flow_flow_settingflow_addeditClass = CNOA.Class.create();
CNOA_flow_flow_settingflow_addeditClass.prototype = {
	init: function(ac){
		var _this = this;
		
		this.ID_window_numberTip = Ext.id();
		this.ID_btn_addedit_save = Ext.id();
		this.ID_window_formSelect = Ext.id();
		
		this.edit_id = 0;
		this.title = ac == "edit" ? "流程信息" : "新建流程";
		this.action = ac;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=setting";
		
		//分类
		this.sortStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getSortList&type=combo", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: [{name:"sid"},{name:"name"}]}),
			listeners: {
				load : function(){
					if(_this.sort != 0){
						_this.sortSelector.setValue(_this.sort);
					}
				}
			}
		});
		this.sortStore.load();
		this.sortSelector = new Ext.form.ComboBox({
			//style: "padding-top:1px",
			store: this.sortStore,
			valueField: 'sid',
			displayField: 'name',
			hiddenName: 'sid',
			mode: 'local',
			allowBlank: false,
			width: 290,
			triggerAction: 'all',
			forceSelection: true,
			editable: false,
			fieldLabel: "所属分类",
			listeners: {
				select : function(combo, record, index){
					//var rd = record.data;
					//alert(rd.sid);
				}
			}
		});
		
		//工作表单
		/*
		this.formStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=getFormList&type=tree&sort="+sid, disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: [{name:"sid"},{name:"name"}]}),
			listeners: {
				
			}
		});
		this.formStore.load();
		this.formSelector = new Ext.form.ComboBox({
			//style: "padding-top:1px",
			store: this.formStore,
			valueField: 'sid',
			displayField: 'name',
			hiddenName: 'sid',
			mode: 'local',
			allowBlank: false,
			width: 290,
			triggerAction: 'all',
			forceSelection: true,
			editable: false,
			fieldLabel: "所属分类",
			listeners: {
				select : function(combo, record, index){
					//var rd = record.data;
					//alert(rd.sid);
				}
			}
		});
		*/
		
		this.formPanel = new Ext.form.FormPanel({
			labelAlign: "right",
			labelWidth: 70,
			waitMsgTarget: true,
			border: false,
			bodyStyle: "padding: 10px;",
			items: [
				{
					xtype: "textfield",
					width: 290,
					fieldLabel: "流程名称",
					allowBlank: false,
					name: "name"
				},
				this.sortSelector,
				{
					xtype: "panel",
					hideBorders: true,
					border: false,
					layout: "table",
					autoWidth: true,
					layoutConfig: {
						columns: 2
					},
					items: [
						{
							xtype: "panel",
							layout: "form",
							width: 370,
							items: [
								{
										xtype: "textfield",
										allowBlank: false,
										name: "formname",
										readOnly: true,
										fieldLabel: '工作表单',
										width: 290
								},
								{
										xtype: "hidden",
										allowBlank: false,
										name: "formid"
								}
							]
						},
						{
							xtype: "panel",
							layout: "form",
							//width: 218,
							items: [
								{
									xtype: "button",
									text: '选择',
									autoWidth: true,
									handler: function(){
										_this.showFormSelectWidnow(_this.sortSelector.getValue(), _this.sortSelector.getRawValue());
									}
								}
							]
						}
					]
				},
				{
					xtype: "panel",
					hideBorders: true,
					border: false,
					layout: "table",
					autoWidth: true,
					layoutConfig: {
						columns: 2
					},
					items: [
						{
							xtype: "panel",
							layout: "form",
							width: 370,
							items: [
								{
										xtype: "textfield",
										allowBlank: false,
										name: "number",
										fieldLabel: '编号格式',
										width: 290
								}
							]
						},
						{
							xtype: "panel",
							layout: "form",
							//width: 218,
							items: [
								{
									xtype: "button",
									text: '格式说明',
									autoWidth: true,
									handler: function(){
										try{
											Ext.getCmp(_this.ID_window_numberTip).show();
										}catch(e){
											_this.showNumberTipWidnow();
										}
									}
								}
							]
						}
					]
				},
				{
					xtype: "textarea",
					fieldLabel: "备注",
					name: "about",
					width: 387,
					height: 137
				}
			],
			listeners: {
				afterrender : function(th){
					if(ac == "sortEdit"){
						//loadForm();
					}
				}
			}
		});
		
		this.mainPanel = new Ext.Window({
			title: this.title,
			width: 500,
			height: 328,
			layout: "fit",
			modal: true,
			maximizable: true,
			resizable: false,
			items: [this.formPanel],
			buttons: [{
				text: "保存",
				id: _this.ID_btn_addedit_save,
				iconCls: 'icon-order-s-accept',
				handler: function(){
					_this.submitForm();
				}
			},   //关闭
			{
				text: CNOA.lang.btnClose,
				iconCls: 'icon-dialog-cancel',
				handler: function(){
					_this.close();
				}
			}],
			listeners: {
				close : function(){
					try{
						var w = Ext.getCmp(_this.ID_window_numberTip);
						w.close();
						Ext.destroy(w);
					}catch(e){}
					//try{
					//	var w2 = Ext.getCmp(_this.ID_window_formSelect);
					//	w2.close();
					//	Ext.destroy(w2);
					//}catch(e){}
				}
			}
		});
	},
	
	show: function(){
		this.mainPanel.show();
		
		if(this.action == "edit"){
			this.loadForm();
		}
	},
	
	close: function(){
		this.mainPanel.close();
	},
	
	submitForm : function(){
		var _this = this;
		
		var f = this.formPanel.getForm();
		if (f.isValid()) {
			f.submit({
				url: _this.baseUrl+"&task=flow"+_this.action,
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				method: 'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				params: {lid : _this.edit_id},
				success: function(form, action) {
					CNOA_flow_flow_settingflow.store.reload();
					CNOA.msg.alert(action.result.msg, function(){
						_this.close();
					}.createDelegate(this));
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						
					}.createDelegate(this));
				}.createDelegate(this)
			});
		}else{
			var position = Ext.getCmp(_this.ID_btn_addedit_save).getEl().getBox();
				position = [position['x']+35, position['y']+26];

			CNOA.miniMsg.alert("部分表单未完成,请检查", position);
		}
	},
	
	loadForm : function(){
		var _this = this;
		
		var f = this.formPanel.getForm();
		
		f.load({
			url: _this.baseUrl+"&task=floweditLoadForm",
			params: {lid: _this.edit_id},
			method:'POST',
			waitMsg: CNOA.lang.msgLoadMask,
			success: function(form, action){
				
			}.createDelegate(this),
			failure: function(form, action) {
				_this.close();
				CNOA.msg.alert(action.result.msg, function(){
					
				});
			}.createDelegate(this)
		});
	},
	
	showNumberTipWidnow : function(){
		var _this = this;
		
		var insert = function(str){
			var n = _this.formPanel.getForm().findField("number");
			n.setValue(n.getValue()+""+str);
		}
		
		var form = new Ext.form.FormPanel({
			labelAlign: "right",
			labelWidth: 50,
			waitMsgTarget: true,
			border: false,
			bodyStyle: "padding: 10px;",
			defaults: {
				xtype: "displayfield",
				width: 290,
				style: "cursor: pointer;"
			},
			items: [
				{
					fieldLabel: "{F}",
					value: "此变量表示当前的流程名称",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{F}")});}}
				},
				{
					fieldLabel: "{U}",
					value: "此变量表示当前的流程发起者",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{U}")});}}
				},
				{
					fieldLabel: "{Y}",
					value: "此变量表示当前时间的四位年份值，如2008",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{Y}")});}}
				},
				{
					fieldLabel: "{M}",
					value: "此变量表示当前时间的两位月份值，如12",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{M}")});}}
				},
				{
					fieldLabel: "{D}",
					value: "此变量表示当前时间的两位日期值，如31",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{D}")});}}
				},
				{
					fieldLabel: "{H}",
					value: "此变量表示当前时间的两位小时值，如08",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{H}")});}}
				},
				{
					fieldLabel: "{I}",
					value: "此变量表示当前时间的两位分钟值，如59",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{I}")});}}
				},
				{
					fieldLabel: "{S}",
					value: "此变量表示当前时间的两位秒钟值，如59",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{S}")});}}
				},
				{
					fieldLabel: "{N}",
					value: "此变量表示当天的系统流水号，{N}表示一位流水号，{NNNNN}表示五位流水号，最多五位，如00001",
					listeners: {render : function(th){th.getEl().on("click", function(){insert("{N}")});}}
				},
				{
					fieldLabel: "示例",
					value: "如：{F}{Y}{M}{D}-{NNNN}，将会被转化为：流程名称20080808-0008"
				}
			]
		});
		
		var win = new Ext.Window({
			title: "流程编号规则说明",
			id: this.ID_window_numberTip,
			width: 420,
			height: 345,
			layout: "fit",
			closeAction: "hide",
			maximizable: true,
			resizable: false,
			items: [form],
			buttons: [
			{
				text: CNOA.lang.btnClose,
				iconCls: 'icon-dialog-cancel',
				handler: function(){
					win.hide();
				}
			}],
			listeners: {
				deactivate : function(th){
					th.hide();
				},
				show : function(th){
					var p = th.getPosition();
					th.setPosition(p[0]+24, p[1]+136);
				}
			}
		}).show();
	},
	
	showFormSelectWidnow : function(sid, title){
		var _this = this;
		
		var insert = function(str){
			var node = deptTree.getSelectionModel().getSelectedNode();
			var form = _this.formPanel.getForm();
			if(node == null){
				form.findField("formname").setValue("");
				form.findField("formid").setValue("");
			}else{
				form.findField("formname").setValue(node.text);
				form.findField("formid").setValue(node.attributes.fid);
			}
			win.close();
		}
		
		var treeRoot = new Ext.tree.AsyncTreeNode({
			expanded: false
		});

		var treeLoader = new Ext.tree.TreeLoader({
			dataUrl: _this.baseUrl + "&task=getFormList&type=tree&sort="+sid,
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
				"load":function(node){
					//deptTree.expandAll();
				}.createDelegate(this)
			}
		});

		var deptTree = new Ext.tree.TreePanel({
			hideBorders: true,
			border: false,
			rootVisible: false,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: treeLoader,
			root: treeRoot,
			autoScroll: true,
			listeners:{
				"click":function(node){
					
				}.createDelegate(this)
			}
		});
		
		deptTree.getSelectionModel().on('beforeselect', function(sm, node) {
			return node.isLeaf();
		});
		
		var win = new Ext.Window({
			title: "选择工作表单 - " + title,
			id: _this.ID_window_formSelect,
			width: 420,
			height: 345,
			layout: "fit",
			closeAction: "close",
			maximizable: true,
			resizable: false,
			modal: true,
			items: [deptTree],
			buttons: [
			{
				text: "保存",
				iconCls: 'icon-order-s-accept',
				handler: function(){
					insert();
				}
			},
			{
				text: CNOA.lang.btnClose,
				iconCls: 'icon-dialog-cancel',
				handler: function(){
					win.close();
				}
			}],
			listeners: {
				deactivate : function(th){
					th.close();
				},
				show : function(th){
					
				}
			}
		}).show();
	}
}

/**
* 添加/修改流程设计
*
*/
CNOA_flow_flow_settingflow_designClass = CNOA.Class.create();
CNOA_flow_flow_settingflow_designClass.prototype = {
	init: function(flowId, flowName){
		var _this = this;

		this.ID_window_numberTip = Ext.id();
		this.ID_btn_addedit_save = Ext.id();
		this.ID_CNOA_flow_flow_setting_design_ct = Ext.id();
		
		this.ID_DESIGN_BUTTON = "flow_flow_setting_design_button_";
		this.ID_DESIGN_LINE = "flow_flow_setting_design_line_";
		
		this.flowId = flowId;
		this.flowName = flowName;
		this.data = null;
		this.flowFormItems = null;
		this.newStepCount = 1;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=setting";
		
		this.treeRoot = new Ext.tree.TreeNode({
			expanded: true
		}); 
		this.treePanel = new Ext.tree.TreePanel({
			root: this.treeRoot,
			border: false,
			id: "flow_flow_design",
			//useArrows: true,
			rootVisible: false
		});
		this.rightPanel = new Ext.Panel({
			border: false,
			region: "east",
			width: 180,
			minWidth: 180,
			maxWidth: 180,
			split: true,
			autoScroll:true,
			bodyStyle: "padding-top:4px;",
			items: [this.treePanel],
			tbar: ['步骤概预：']
		});
		
		this.centerPanel = new Ext.Panel({
			border: false,
			region: "center",
			autoScroll: true,
			bodyStyle: "background:#FFF url('./resources/images/mxgraph/grid.gif');",
			html: "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center' valign='top' style='padding-top:20px;'><div id='"+this.ID_CNOA_flow_flow_setting_design_ct+"'></div></td></tr></table>",
			listeners: {
				afterrender: function(th){
					_this.readFlowData();
				}
			}
		});
		
		this.mainPanel = new Ext.Window({
			title: "设计流程 - " + this.flowName,
			width: 700,
			height: 600,
			layout: "border",
			modal: true,
			maximizable: true,
			resizable: true,
			items: [this.centerPanel, this.rightPanel],
			buttons: [{
				text: "保存",
				id: _this.ID_btn_addedit_save,
				iconCls: 'icon-order-s-accept',
				handler: function(){
					_this.submitFlowData();
				}
			},   //关闭
			{
				text: CNOA.lang.btnClose,
				iconCls: 'icon-dialog-cancel',
				handler: function(){
					_this.close();
				}
			}],
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
	
	readFlowData : function(){
		var _this = this;
		
		_this.mainPanel.getEl().mask(CNOA.lang.msgLoadMask);

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=flowdesignLoadData",
			method: 'POST',
			params: { lid: _this.flowId },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					if(result.data.length < 1){
						_this.data = [
							{
								name: "开始",
								id: "0",
								type: "start"
							}
						];
					}else{
						_this.data = result.data;
					}
					
					_this.flowFormItems = result.flowFormItems;
				}else{
					CNOA.msg.alert(result.msg);
				}
				
				_this.makeFlowViewport();
				
				_this.mainPanel.getEl().unmask();
			}
		});
	},
	
	//提交流程设计数据
	submitFlowData : function(){
		var _this = this;
		
		_this.mainPanel.getEl().mask(CNOA.lang.msgLoadMask);
		
		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=flowdesignSubmitData",
			method: 'POST',
			params: { lid: _this.flowId, data: Ext.encode(_this.data) },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.alert(result.msg, function(){
						_this.close();
					});
				}else{
					CNOA.msg.alert(result.msg);
				}
				_this.mainPanel.getEl().unmask();
			}
		});
	},
	
	//生成流程图
	makeFlowViewport : function(){
		var _this = this;
		
		document.getElementById(_this.ID_CNOA_flow_flow_setting_design_ct).innerHTML = "";
		
		var lastLineId = 0;
		Ext.each(_this.data, function(v, i, c){
			_this.createButton(v.id, v.name, v.type);
			_this.createLine(v.id);
			lastLineId = v.id;
		});
		
		//删除最后一个连线
		try{
			var p = document.getElementById(_this.ID_CNOA_flow_flow_setting_design_ct);
			var s = document.getElementById(_this.ID_DESIGN_LINE+lastLineId);
			p.removeChild(s);
		}catch(e){}
	},
	
	//删除流程节点
	deleteNode : function(nodeId){
		var _this = this;
		
		var tmp = new Array();
		var id = 0;
		
		Ext.each(_this.data, function(v, i, c){
			if(v.id != nodeId){
				v.id = id;
				tmp.push(v);
				id++;
			}
		});
		
		_this.data = tmp;
		
		_this.makeFlowViewport();
	},
	
	//添加流程节点
	addNode : function(preNodeId){
		var _this = this;
		
		var tmp = new Array();
		
		var node = {
			name : "新步骤"+this.newStepCount++,
			type : "node"
		}
		
		var id = 0;
		Ext.each(_this.data, function(v, i, c){
			v.id = id;
			tmp.push(v);
			id++;
			if(v.id == preNodeId){
				node.id = id;
				tmp.push(node);
				id++;
			}
		});
		
		_this.data = tmp;
		
		_this.makeFlowViewport();
	},
	
	//生成流程节点按钮
	createButton : function(id, name, type){
		var _this = this;
		
		var menu = new Ext.menu.Menu({
	        items: [  
	            {
					text: "设置步骤",
					handler: function(){
						_this.settingNode(id);
					}
				},  
	            {
					text: "添加下一步",
					handler: function(){
						_this.addNode(id);
					}
				},  
	            {
					text: "删除本步骤",
					disabled: type=="start" ? true : false,
					handler: function(){
						_this.deleteNode(id);
					}
				}
	        ]  
	    });
		
		new Ext.Button({
			text: name,
			scale: 'large',
			btype: type,
			id: this.ID_DESIGN_BUTTON+id,
			enableToggle: true,
			toggleGroup: "flow_flow_setting_design_btn",
			renderTo: _this.ID_CNOA_flow_flow_setting_design_ct,
			autoWidth: true,
			handler: function(th){
				th.toggle(true);
			},
			listeners: {
				render : function(btn){
					btn.el.on("contextmenu", function(e) {
						btn.toggle(true);
						e.preventDefault();  
						if(type != "end"){
							menu.showAt(e.getXY());
						}
					}, this);
				},
				toggle : function(btn, ck){
					if(ck){
						_this.makeViewTreeData(id);
					}
				}
			}
		});
	},
	
	//生成右边预览tree数据(节点属性查看器)
	makeViewTreeData : function(id){
		var _this = this;
		
		var makeNode = function(text, leaf){
			return new Ext.tree.TreeNode({
				iconCls: leaf ? "icon-none" : "icon-fileview-column2",
				text: text,
				expanded: true
			});
		}
		
		var nowNode = _this.getDataById(id);
		
		if((nowNode.operator === null) || (nowNode.operator === undefined)){
			nowNode.operator = {user: [], station:[]}
		}

		//生成节点-步骤名称
		var a1 = makeNode("<b>步骤名称</b>", false);
		var a2 = makeNode("<span style='color:#616161'>"+nowNode.name+"</span>", true);
		a1.appendChild(a2);
		
		//生成节点-上一步骤名称
		var b1 = makeNode("<b>上一步骤名称</b>", false);
		var b2 = makeNode("<span style='color:#616161'>"+_this.getDataById(parseInt(id)-1).name+"</span>", true);
		b1.appendChild(b2);
		
		//生成节点-下一步骤名称
		var c1 = makeNode("<b>下一步骤名称</b>", false);
		var c2 = makeNode("<span style='color:#616161'>"+_this.getDataById(parseInt(id)+1).name+"</span>", true);
		c1.appendChild(c2);
		
		//生成节点-经办角色
		var d1 = makeNode("<b>经办角色</b>", false);
		var d2 = makeNode("<b>人员</b>", false);
		var d3 = makeNode("<b>岗位</b>", false);
		try{
			Ext.each(nowNode.operator.user, function(v, i){
				var d = makeNode("<span style='color:#616161'>"+v.name+"</span>", true);
				d2.appendChild(d);
			});
			if(nowNode.operator.user.length == 0){
				//d2.appendChild(makeNode("无", true));
			}
		}catch(e){
			//d2.appendChild(makeNode("无", true));
		}
		try{
			Ext.each(nowNode.operator.station, function(v, i){
				var d = makeNode("<span style='color:#616161'>"+v.name+"</span>", true);
				d3.appendChild(d);
			});
			if(nowNode.operator.station.length == 0){
				//d3.appendChild(makeNode("无", true));
			}
		}catch(e){
			//d3.appendChild(makeNode("无", true));
		}
		d1.appendChild(d2);
		d1.appendChild(d3);
		/* 如果是第一步骤并且经办角色是任意人时 */
		if(nowNode.id == 0){
			if((nowNode.operator.station.length == 0) && (nowNode.operator.user.length == 0)){
				d1.childNodes = [];
				d1.appendChild(makeNode("<span style='color:#616161'>任意人</span>", true));
			}
		}
		
		//表单字段
		//node.formitems
		var g1 = makeNode("<b>表单字段</b>", false);
		var g2 = makeNode("<b>使用字段</b>", true);
		//var g3 = makeNode("<b>必填字段</b>", true);
		var g4 = makeNode("<b>使用宏控件</b>", true);
		Ext.each(nowNode.formitems, function(v, i){
			if(v.need){
				var muststr = "";
				if(v.must){
					muststr = "(<span style='color:red'>必填</span>)";
				}else{
					muststr = "(<span style='color:#008200'>选填</span>)";
				}
				g2.appendChild(makeNode("<span style='color:#616161'>" + v.name + muststr + "</span>", true));
			}
			
			if(v.used){
				g4.appendChild(makeNode("<span style='color:#616161'>"+v.name+"</span>", true));
			}
		});
		g1.appendChild(g2);
		//g1.appendChild(g3);
		g1.appendChild(g4);
		
		//经办方式operatortype
		var operatorperson = nowNode.operatorperson == "2" ? "<span style='color:#616161'>多人办理</span>" : "<span style='color:#616161'>单人办理</span>";
		var operatortype = nowNode.operatortype == "1" ? "<span style='color:#616161'>任意一人同意</span>" : "<span style='color:#616161'>所有人同意</span>";
		var e1 = makeNode("<b>经办方式</b>", false);
		var e2 = makeNode("<span style='color:#616161'>"+operatorperson+"</span>", true);
		e1.appendChild(e2);
		if(nowNode.operatorperson == "2"){
			var e3 = makeNode("<b>转下一步条件: </b>", false);
			var e4 = makeNode("<span style='color:#616161'>"+operatortype+"</span>", true);
			e3.appendChild(e4);
			e1.appendChild(e3);
		}
		
		//生成节点-允许操作
		var f1 = makeNode("<b>允许操作</b>", false);
		if(parseInt(nowNode.upAttach) == 1){
			var f2 = makeNode("<span style='color:#616161'>允许上传附件</span>", true);
			f1.appendChild(f2);
		}
		if(parseInt(nowNode.downAttach) == 1){
			var f3 = makeNode("<span style='color:#616161'>允许下载附件</span>", true);
			f1.appendChild(f3);
		}
		//if(!f1.hasChildNodes()){
		//	var f4 = makeNode("无", true);
		//	f1.appendChild(f4);
		//}
		
		//添加到树列表
		_this.treeRoot = new Ext.tree.TreeNode({
			expanded: true
		}); 
		if(nowNode.type == "end"){
			_this.treeRoot.appendChild([a1, b1]);
		}else{
			_this.treeRoot.appendChild([a1, b1, c1, d1, g1, e1, f1]);
		}
		_this.treePanel.setRootNode(_this.treeRoot);
	},
	
	//根据ID获取节点数据
	getDataById : function(id){
		var _this = this;
		id = parseInt(id);

		if((id < 0) || (id > _this.data.length-1)){
			return {name: "无", id: "-1"}
		}
		
		var value;
		Ext.each(_this.data, function(v, i, c){
			if(v.id == id){
				value = v;
			}
		});
		return value;
	},
	
	//生成箭头线
	createLine : function(source){
		var _this = this;
		
		new Ext.BoxComponent({
			id: this.ID_DESIGN_LINE+source,
			renderTo: _this.ID_CNOA_flow_flow_setting_design_ct,
			autoEl: {
				tag: 'div',
				html: '<img src="./resources/images/cnoa/arrow.gif" />'
			}
		})
	},
	
	//节点设置
	settingNode : function(nodeId){
		var _this = this;
		
		var ID_panel_operatortype = Ext.id();
		var formitemsObject = null; //用于存放节点表单字段数据
		
		//初始化变量
		var nowNode = _this.getDataById(nodeId);
		var nowNodeOpeartorData = {user:[], station:[]};
		try{
			nowNodeOpeartorData.user = nowNode.operator.user;
		}catch(e){
			nowNodeOpeartorData.user = [];
		}
		try{
			nowNodeOpeartorData.station = nowNode.operator.station;
		}catch(e){
			nowNodeOpeartorData.station = [];
		}
		
		var submit = function(){
			var node = {};
			var f = formPanel.getForm();
			
			//节点属性
			node.id				= nodeId;
			node.name			= f.findField("name").getValue();
			node.upAttach 		= f.findField("upAttach").getValue() ? "1" : "0";
			node.downAttach 	= f.findField("downAttach").getValue() ? "1" : "0";
			node.operatortype 	= f.findRadioCheckBoxGroupField("operatortype").getValue();
			node.operatorperson	= f.findRadioCheckBoxGroupField("operatorperson").getValue();
			node.operator		= nowNodeOpeartorData;
			
			//获取表单字段
			var flowFormItemsList = new Array();
			var formCtList = Ext.query("div[id^=FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_CT_]");
			Ext.each(formCtList, function(v, i){
				var av = new Object();
					av.name = v.title;
				Ext.each(v.children, function(vv, ii){
					if(vv.id.indexOf("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_MUST_") != -1){
						av.itemid = vv.id.replace("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_MUST_", "");
						av.must = vv.checked;
					}
					if(vv.id.indexOf("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_NEED_") != -1){
						av.need = vv.checked;
					}
					if(vv.id.indexOf("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_USED_") != -1){
						av.itemid = vv.id.replace("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_USED_", "");
						av.used = vv.checked;
					}
				});
				flowFormItemsList.push(av);
			});			
			node.formitems		= flowFormItemsList;

			var n = 0;
			Ext.each(_this.data, function(v, i, c){
				if(v.id == nodeId){
					_this.data[i] = node;
					n = i+1;
				}
			});
			
			//后续节点处理
			var stepOver	= f.findField("stepOver").getValue();
			if(stepOver){
				if(n != _this.data.length){
					CNOA.msg.alert("本步骤不是最后一个步骤，不能结束");
				}else{
					_this.data.push({
						name: "结束",
						id: n,
						type: "end"
					});
				}
			}
			
			//关闭窗口
			win.close();
			
			//重新生成流程图
			_this.makeFlowViewport();
			
			//重新选中节点(选中后会自动更新右边属性查看器)
			Ext.getCmp(_this.ID_DESIGN_BUTTON+nodeId).toggle(true);
		}
		
		
		/* Tab A 基本属性 ****************************************************************/
		var baseField = [
			{
				xtype: "fieldset",
				title: "步骤属性",
				anchor: "99%",
				border: true,
				autoHeight: true,
				defaults: {
					//width: 325,
					xtype: "textfield"
				},
				items: [
					{
						xtype: "textfield",
						width: 290,
						fieldLabel: "步骤名称",
						allowBlank: false,
						name: "name"
					},
					{
						xtype: "panel",
						layout: "table",
						autoWidth: true,
						border: false,
						layoutConfig: {
							columns: 2
						},
						items: [
							{
								xtype: "panel",
								layout: "form",
								border: false,
								width: 174,
								items: [
									{
											xtype: "checkbox",
											fieldLabel: "允许操作",
											boxLabel: "允许上传附件",
											name: 'upAttach'
									}
								]
							},
							{
								xtype: "checkbox",
								boxLabel: "允许下载附件",
								name: 'downAttach'
							}
						]
					},
					{
						xtype: 'checkbox',
						fieldLabel: "后续步骤",
						name: "stepOver",
						boxLabel: "结束"
					}
				]
			},
			{
				xtype: "fieldset",
				title: "经办方式",
				anchor: "99%",
				border: true,
				autoHeight: true,
				defaults: {
					width: 200,
					xtype: "textfield"
				},
				items: [
					{
						xtype: 'radiogroup',
						fieldLabel: '经办方式',
						//allowBlank: false,
						name: "operatorpersonGroup",
						items: [
							{boxLabel: '单人办理', name: 'operatorperson', inputValue: "1", checked: true},
							{boxLabel: '多人办理', name: 'operatorperson', inputValue: "2"}
						],
						listeners:{
							"change":function(th, checked){
								var p = Ext.getCmp(ID_panel_operatortype);
								if(checked.inputValue == "2"){
									p.show();
								}else{
									p.hide();
								}
							}.createDelegate(this),

							"render" : function(th){
								
							}
						}
					},
					{
						xtype: "panel",
						border: false,
						hidden: false,
						width: 560,
						layout: "form",
						id: ID_panel_operatortype,
						defaults: {
							width: 200,
							xtype: "textfield"
						},
						items: [
							{
								xtype: 'radiogroup',
								fieldLabel: '处理方式',
								//allowBlank: false,
								name: "operatortypeGroup",
								items: [
									{boxLabel: '任意一人同意', name: 'operatortype', inputValue: "1"},
									{boxLabel: '所有人同意', name: 'operatortype', inputValue: "2", checked: true}
								],
								listeners:{
									"change":function(th, checked){
										
									}.createDelegate(this),
		
									"render" : function(th){
										
									}
								}
							},
							{
								xtype: "displayfield",
								width: 560,
								value: "<span style='color:#666'>任意一人同意：只要本步骤中有一人同意，则流程进入下一步骤。<br />　所有人同意：所有人同意后流程才会进入下一步骤。</span>"
							}
						]
					}
					
				]
			}
		];
		
		/* Tab B 表单字段 ****************************************************************/
		var readerFormType = function(v, cellmeta, record){
			var h = "";
			if(v == "textfield"){h = "单行文本框";}
			else if(v == "textarea"){h = "多行文本框";}
			else if(v == "select"){h = "下拉选择框";}
			else{h = "宏控件";}
			return h;
		}
		var readerFormOpera = function(v, cellmeta, record){
			var idA = "FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_NEED_"+v;
			var idB = "FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_MUST_"+v;
			var idC = "FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_USED_"+v;
			var h = "";

			if((record.data.type == "textfield") || (record.data.type == "textarea") || (record.data.type == "select")){
				var clickStr = 'onclick="if(this.checked){Ext.getDom(\''+idB+'\').disabled=false;}else{Ext.getDom(\''+idB+'\').checked=false;Ext.getDom(\''+idB+'\').disabled=true;}"';
			
				h += '<div id="FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_CT_'+v+'" title="'+record.data.title+'">';
				h += '<label for="'+idA+'">可填</label><input type="checkbox" id="'+idA+'" '+clickStr+' />';
				h += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				h += '<label for="'+idB+'">必填</label><input type="checkbox" id="'+idB+'" disabled="true" />';
				h += '</div>';
			}else{
				h += '<div id="FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_CT_'+v+'" title="'+record.data.title+'">';
				h += '<label for="'+idC+'">使用</label><input type="checkbox" id="'+idC+'" />';
				h += '</div>';
			}
			
			return h;
		};
		var dataTabB = {
			data : _this.flowFormItems
		}
		var storeTabB = new Ext.data.Store({
			proxy: new Ext.data.MemoryProxy(dataTabB),
			reader: new Ext.data.JsonReader({root: 'data'},[{name: 'itemid'},{name: 'title'},{name: 'htmltag'},{name:'type'}])
		});
		storeTabB.load();
		var smTabB = new Ext.grid.RowSelectionModel({singleSelect: true});
		var colModelTabB = new Ext.grid.ColumnModel([
			{header: "itemid", dataIndex: 'itemid', hidden: true},
			{header: "表单名称", dataIndex: 'title', width: 408},
			{header: "表单类型", dataIndex: 'type', width: 125, renderer: readerFormType},
			{header: "操作", dataIndex: 'itemid', width: 132, sortable: true, renderer: readerFormOpera}
		]);
		var gridTabB = new Ext.grid.GridPanel({
			store: storeTabB,
			cm: colModelTabB,
			sm: smTabB,
			viewConfig: {
				//forceFit: true
			},
			listeners: {
				afterrender : function(){
					//dump(formitemsObject);
					var setIV = setInterval(function(){
						try{
							//Ext.getDom("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_USED_6").checked = true;
							Ext.each(formitemsObject, function(v, i){
								if(v.need){
									Ext.getDom("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_NEED_"+v.itemid).checked = true;
									Ext.getDom("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_MUST_"+v.itemid).disabled = false;
								}
								if(v.must){
									Ext.getDom("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_MUST_"+v.itemid).checked = true;
								}
								if(v.used){
									Ext.getDom("FLOW_FLOWDESIGN_WINDOW_TABB_CHECKBOX_USED_"+v.itemid).checked = true;
								}
								clearInterval(setIV);
							});
						}catch(e){}
					}, 300);
				}
			}
		});
		
		/* Tab C 经办角色 ****************************************************************/
		
		var selectorForStationStoreData;
		try{selectorForStationStoreData=nowNode.operator.station;}catch(e){selectorForStationStoreData=[]}
		this.selectorForStationStore =  new Ext.data.ArrayStore({
			proxy: new Ext.data.MemoryProxy(selectorForStationStoreData),
            fields: [{name:"sid"},{name:"text", mapping: "name"}]
        });
		this.selectorForStationStore.load();
		this.selectorForStation = new Ext.ux.form.MultiSelect({
			name: 'multiselect',
			width: 334,
			height: 343,
			valueField: 'sid',
			displayField: 'text',
			hiddenName: 'sid',
			store:	this.selectorForStationStore,
			tbar:[
				"选择岗位&nbsp;&nbsp;",
				"->",
				{
					xtype: "stationSelector",
					text: '选择',
					iconCls: "icon-order-s-spAdd",
					listeners:{  
						//双击  
						"selected" : function(th, dt){
							_this.selectorForStationStore.removeAll();
							nowNodeOpeartorData.station = new Array();
							for(var i=0;i<dt.length;i++){
								var records = new Ext.data.Record(dt[i]);
								_this.selectorForStationStore.add(records);
								//添加到临时变量里
								nowNodeOpeartorData.station.push({sid: dt[i].sid, name:dt[i].text});
							}
						}
					}
				},"-",
				{
					text: '清空',
					iconCls: "icon-clear",
					handler: function(){
						_this.selectorForStationStore.removeAll();
						nowNodeOpeartorData.station = new Array();
					}
				}
			],
			ddReorder: true
		});
		
		var selectorForPeopleStoreData;
		try{selectorForPeopleStoreData=nowNode.operator.user;}catch(e){selectorForPeopleStoreData=[]}
		this.selectorForPeopleStore =  new Ext.data.ArrayStore({
			proxy: new Ext.data.MemoryProxy(selectorForPeopleStoreData),
            fields: [{name:"uid"},{name:"uname", mapping: "name"}]
        });
		this.selectorForPeopleStore.load();
		this.selectorForPeople = new Ext.ux.form.MultiSelect({
			name: 'multiselect',
			width: 334,
			height: 343,
			valueField: 'uid',
			displayField: 'uname',
			hiddenName: 'uid',
			store: this.selectorForPeopleStore,
			tbar:[
				"选择人员&nbsp;&nbsp;",
				"->",
				{
					xtype: "btnForPoepleSelector",
					text: '选择',
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					iconCls: "icon-order-s-spAdd",
					listeners: {
						"selected" : function(th, data){
							if (data.length>0){
								_this.selectorForPeopleStore.removeAll();
								nowNodeOpeartorData.user = new Array();
								for (var i=0;i<data.length;i++){
									var records = new Ext.data.Record(data[i]);
									_this.selectorForPeople.store.add(records);
									//添加到临时变量里
									nowNodeOpeartorData.user.push({uid: data[i].uid, name:data[i].uname});
								}
							}
						},
						"onrender" : function(th){
							
						}
					}
				}/*,"-",
				{
					text: '删除',
					iconCls: "icon-attac-delete",
					handler: function(){
						_this.selectorForPeople.reset();
					}
				}*/,"-",
				{
					text: '清空',
					iconCls: "icon-clear",
					handler: function(){
						_this.selectorForPeopleStore.removeAll();
						nowNodeOpeartorData.user = new Array();
					}
				}
			],
			ddReorder: true
		});
			
		this.selectorPanel = new Ext.Panel({
			border: false,
			items: [{
                margins:'5 5 5 5',
                layout:'column',
                autoScroll:true,
				border: false,
                items:[{
                    columnWidth:.50,
                    baseCls:'x-plain',
					bodyStyle:'padding:5px 0 5px 5px',
					layout: "fit",
                    items:[this.selectorForPeople]
                },{
                    columnWidth:.50,
                    baseCls:'x-plain',
                    bodyStyle:'padding:5px',
                    items:[this.selectorForStation]
                }]
            }]
		});
		
		var tabPanel = new Ext.TabPanel({
			activeTab: 0,
			hideBorders: true,
			deferredRender: false,
			border: false,
			
			defaults: {
				hideBorders: true,
				border: false
			},
			items: [
				{
					title: "基本信息",
					layout: "form",
					labelAlign: "right",
					labelWidth: 70,
					height:352,
					items: baseField,
					bodyStyle: "padding: 10px;",
					defaults: {
						width: 250
					},
					listeners : {
						activate : function(){
							
						}.createDelegate(this)
					}
				},
				{
					title: "表单字段",
					layout: "fit",
					autoScroll: true,
					height:352,
					items: [gridTabB],
					listeners : {
						activate : function(){
							
						}.createDelegate(this)
					}
				},
				{
					title: "经办角色",
					//layout: "fit",
					items: [this.selectorPanel],
					listeners : {
						activate : function(){
							
						}.createDelegate(this)
					}
				}
			]
		});
		
		var formPanel = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			items: [tabPanel]
		});
		
		var win = new Ext.Window({
			title: "设置步骤",
			width: 700,
			height: 450,
			layout: "fit",
			modal: true,
			closeAction: "close",
			resizable: false,
			items: [formPanel],
			buttons: [
				{
					text: "确定",
					iconCls: 'icon-order-s-accept',
					handler: function(){
						submit();
					}
				},
				{
					text: CNOA.lang.btnClose,
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
		
		//给表单赋值
		(function(){
			var rec = {};
			Ext.each(_this.data, function(v, i, c){
				if(v.id == nodeId){
					rec.data = v;
					formitemsObject = v.formitems;
				}
			});
				
			formPanel.getForm().loadRecord(rec);
			
			Ext.getCmp(ID_panel_operatortype).hide();
			try{
				if(rec.data.operatorperson == "2"){
					Ext.getCmp(ID_panel_operatortype).show();
				}
			}catch(e){}
		}())
		
	}
}




/**
* 主面板-列表
*
*/
CNOA_flow_flow_settingflowClass = CNOA.Class.create();
CNOA_flow_flow_settingflowClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;
		this.sort = 0;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=setting";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		
		this.ID_find_name	=	Ext.id();
		this.ID_find_flowFrom = Ext.id();
		
		this.renderPublish = function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			var h = "";

			function createGridButton(){
				return new Ext.Button({
					text: value=='1'?"禁用":"启用",
					//autoWidth: true,
					width: 40,
					handler: function(){
						_this.setFlowPublish(record.get("lid"));
					}
				}).render(document.body, btnId);
			}
			
			var btnId = Ext.id();
			if(value == "1"){
				var btn = createGridButton.defer(1, this, [btnId, value]);
				h = "<span class='cnoa_color_green' style='float:left;width:44px;'>可用</span><span id='"+btnId+"'></span>";
			}else{
				var btn = createGridButton.defer(1, this, [btnId, value]);
				h = "<span class='cnoa_color_gray' style='float:left;width:44px;'>不可用</span><span id='"+btnId+"'></span>";
			}
			return h;
		}
		
		this.storeBar = {
			flowFrom: '',
			name: ''
		}
		
		this.fields = [
			{name:"lid"},
			{name:"name"},
			{name:"sname"},
			{name:"publish"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getFlowJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		
		this.flowFrom = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl + "&task=getFlowFrom", disableCaching: true}),   
			reader:new Ext.data.JsonReader({
				root:"data", 
				fields: [
					{name: 'sid'}, 
					{name: 'name'}
				]
			}),
			listeners:{
				'load': function(th, record){
					
				}
			},
			autoLoad: true
		})
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "lid", dataIndex: 'lid', hidden: true},
			{header: '流程名称', dataIndex: 'name', width: 120, sortable: true, menuDisabled :true},
			{header: "所属分类", dataIndex: 'sname', width: 70, sortable: false,menuDisabled :true},
			{header: "状态 / 操作", dataIndex: 'publish', width: 70, sortable: false,menuDisabled :true,renderer : this.renderPublish},
			{header: "操作", dataIndex: 'lid', width: 120, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			emptyMsg:"没有数据显示",
			displayMsg:"显示第{0}--{1}条数据，共{2}条",   
			store: this.store,
			style: 'border-left-width:1px;',
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					params.sort = _this.sort;
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			bodyStyle: 'border-left-width:1px;',
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},'-',
					{
						handler : function(button, event) {
							CNOA_flow_flow_settingflow_addedit = new CNOA_flow_flow_settingflow_addeditClass("add");
							CNOA_flow_flow_settingflow_addedit.show();
						}.createDelegate(this),
						iconCls: 'icon-utils-s-add',
						text : '新建流程'
					},'-',
					'流程名称：',
					{
						xtype: 'textfield',
						id: _this.ID_find_name,
						name: 'name',
						width: 110
					},'所属流程分类',
					{
						xtype: 'combo',
						id:_this.ID_find_flowFrom,
						width: 110,		
						name: 'flowFrom',
						valueField: 'sid',
						hiddenName: 'worktimeId',
						displayField: 'name',
						triggerAction: 'all',
						mode: 'local',
						editable: false,
						store: _this.flowFrom,
						listeners:{
							'select' : function(combo, record, index){
								
							}
						}
					},
					'-',
					{
						text: '查询',
						cls: 'x-btn-over',
						listeners: {
							'mouseout': function(btn){
								btn.addClass('x-btn-over');
								
							}
						},
						handler: function(){
							_this.storeBar = {
								name: Ext.getCmp(_this.ID_find_name).getValue(),
								flowFrom: Ext.getCmp(_this.ID_find_flowFrom).getValue()
							};
							_this.store.load({params:_this.storeBar});
						}
					},'-',
					{
						text: '清空',
						handler: function(){
							Ext.getCmp(_this.ID_find_name).setValue('');
							Ext.getCmp(_this.ID_find_flowFrom).setValue('');
							
							_this.storeBar = {
								name: '',
								flowFrom: ''
							}
							_this.store.load();
						}
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 10}
				]
			}),
			bbar: this.pagingBar
		});
		
		
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			text: "所有分类",
			sid: '0',
			iconCls: "icon-tree-root-cnoa",
			id: this.ID_tree_treeRoot,
			expanded: true//,
		});
		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl+"&task=getSortList&type=tree",
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
				"load" : function(node){
					_this.sortTree.selectPath(_this.ID_tree_treeRoot);
				}.createDelegate(this)
			}
		})
		this.sortTree = new Ext.tree.TreePanel({
			hideBorders: true,
			border: false,
			rootVisible: true,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: this.treeLoader,
			root: this.treeRoot,
			autoScroll: true,
			listeners:{
				"click":function(node){
					_this.sort = node.attributes.sid;
					_this.store.load({params:{sort: _this.sort}});
				}
			}
		});
		this.sortPanel = new Ext.Panel({
			region: 'west',
			layout:'fit',
			split: true,
			width: 140,
			minWidth: 80,
			maxWidth: 380,
			bodyStyle: 'border-right-width:1px;',
			items: [this.sortTree],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					"流程分类",
					"->",
					//刷新tree
					{
						handler : function(button, event) {
							_this.treeRoot.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: CNOA.lang.mainUser.refreshList,
						text : CNOA.lang.mainUser.refreshList
					}
				]
			})
		});



		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid, this.sortPanel]//, this.bottomPanel
		});
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		var h  = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_flow_flow_settingflow.editPanel("+value+");'><img src='"+src+"page_addedit.png' style='margin:0 2px 4px 0' align='absmiddle' />编辑</a>";
			h += "&nbsp;&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_settingflow.deleteList("+value+");'><img src='"+src+"cross.gif' style='margin:0 2px 4px 0' align='absmiddle' />删除</a>";
			h += "&nbsp;&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_settingflow.designPanel("+value+", \""+rd.name+"\");'><img src='"+src+"frame-edit.png' style='margin:0 2px 4px 0' align='absmiddle' />设计流程</a>";
			h += "&nbsp;&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_settingflow.designPanel2("+value+", \""+rd.name+"\");'><img src='"+src+"frame-edit.png' style='margin:0 2px 4px 0' align='absmiddle' />设计</a>";
			//h += "&nbsp;&nbsp;&nbsp;";
			//h += "<a href='javascript:void(0);' onclick='("+value+");'><img src='"+src+"icon-flow.png' style='margin:0 2px 4px 0' align='absmiddle' />流程图</a>";
			h += "</div>";
		return h;
	},
	
	deleteList : function(lid){
		var _this = this;
		
		CNOA.msg.cf("确认删除吗？", function(btn){
			if(btn == "yes"){
				Ext.Ajax.request({  
					url: _this.baseUrl + "&task=flowdelete",
					method: 'POST',
					params: { lid: lid },
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.alert(result.msg, function(){
								_this.store.reload();
							});
						}else{
							CNOA.msg.alert(result.msg);
						}
					}
				});
			}
		});
	},
	
	editPanel : function(sid){
		CNOA_flow_flow_settingflow_addedit = new CNOA_flow_flow_settingflow_addeditClass("edit");
		CNOA_flow_flow_settingflow_addedit.edit_id = sid;
		CNOA_flow_flow_settingflow_addedit.show();
	},
	
	designPanel : function(id, name){
		CNOA_flow_flow_settingflow_design = new CNOA_flow_flow_settingflow_designClass(id, name);
		CNOA_flow_flow_settingflow_design.show();
	},
	
	designPanel2 : function(id, name){
		CNOA_flow_flow_settingflow_design2 = new CNOA_flow_flow_settingflow_designClass2(id, name);
		CNOA_flow_flow_settingflow_design2.show();
	},
	
	setFlowPublish : function(lid){
		var _this = this;
		
		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=setFlowPublish",
			method: 'POST',
			params: { lid: lid },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					_this.store.reload();
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	}
}



var CNOA_flow_flow_settingformClass, CNOA_flow_flow_settingform;
var CNOA_flow_flow_settingform_addeditClass, CNOA_flow_flow_settingform_addedit;

CNOA_flow_flow_settingform_addeditClass = CNOA.Class.create();
CNOA_flow_flow_settingform_addeditClass.prototype = {
	init: function(ac){
		var _this = this;
		
		this.ID_window_numberTip = Ext.id();
		this.ID_btn_addedit_save = Ext.id();
		
		this.edit_id = 0;
		this.title = ac == "Edit" ? "表单信息" : "新建表单";
		this.action = ac;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=setting";
		
		this.sortStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getSortList&type=combo", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: [{name:"sid"},{name:"name"}]}),
			listeners: {
				load : function(){
					if(_this.sort != 0){
						_this.sortSelector.setValue(_this.sort);
					}
				}
			}
		});
		this.sortStore.load();
		this.sortSelector = new Ext.form.ComboBox({
			//style: "padding-top:1px",
			store: this.sortStore,
			valueField: 'sid',
			displayField: 'name',
			hiddenName: 'sid',
			mode: 'local',
			allowBlank: false,
			width: 290,
			triggerAction: 'all',
			forceSelection: true,
			editable: false,
			fieldLabel: "所属分类",
			listeners: {
				select : function(combo, record, index){
					//var rd = record.data;
				//	alert(rd.sid);
				}
			}
		});
		
		this.formPanel = new Ext.form.FormPanel({
			fileUpload: true,
			labelAlign: "right",
			labelWidth: 70,
			waitMsgTarget: true,
			border: false,
			bodyStyle: "padding: 10px;",
			items: [
				{
					xtype: "textfield",
					width: 290,
					fieldLabel: "表单名称",
					allowBlank: false,
					name: "name"
				},
				this.sortSelector,
				/*
				{
					xtype: "textfield",
					width: 342,
					readOnly: true,
					hideLabel: this.action == "Edit" ? false : true,
					hidden: this.action == "Edit" ? false : true,
					fieldLabel: "已传文件",
					name: "file"
				},
				{
					xtype: 'fileuploadfield',
					name: 'fileup',
					fieldLabel: "上传文件",
					blankText: "请选择要上传的表单文件",
					buttonCfg: {
						text: "浏览"
						//iconCls: 'upload-icon'
					},
					width: 387,
					listeners: {
						'fileselected': function(fb, v){
							
						}
					}
				},
				*/
				{
					xtype: "textarea",
					fieldLabel: "备注",
					name: "about",
					width: 387,
					height: 137
				}
			],
			listeners: {
				afterrender : function(th){
					if(ac == "sortEdit"){
						//loadForm();
					}
				}
			}
		});
		
		this.mainPanel = new Ext.Window({
			title: this.title,
			width: 500,
			height: 280,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [this.formPanel],
			buttons: [{
				text: "保存",
				id: _this.ID_btn_addedit_save,
				iconCls: 'icon-order-s-accept',
				handler: function(){
					_this.submitForm();
				}
			},   //关闭
			{
				text: CNOA.lang.btnClose,
				iconCls: 'icon-dialog-cancel',
				handler: function(){
					_this.close();
				}
			}],
			listeners: {
				close : function(){
					try{
						var w = Ext.getCmp(_this.ID_window_numberTip);
						w.close();
						Ext.destroy(w);
					}catch(e){}
				}
			}
		});
	},
	
	show: function(){
		this.mainPanel.show();
		
		if(this.action == "Edit"){
			this.loadForm();
		}
	},
	
	close: function(){
		this.mainPanel.close();
	},
	
	submitForm : function(){
		var _this = this;
		
		var f = this.formPanel.getForm();
		if (f.isValid()) {
			f.submit({
				url: _this.baseUrl+"&task=form"+_this.action,
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				method: 'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				params: {fid : _this.edit_id},
				success: function(form, action) {
					CNOA_flow_flow_settingform.store.reload();
					CNOA.msg.alert(action.result.msg, function(){
						_this.close();
					}.createDelegate(this));
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						
					}.createDelegate(this));
				}.createDelegate(this)
			});
		}else{
			var position = Ext.getCmp(_this.ID_btn_addedit_save).getEl().getBox();
				position = [position['x']+35, position['y']+26];

			CNOA.miniMsg.alert("部分表单未完成,请检查", position);
		}
	},
	
	loadForm : function(){
		var _this = this;
		
		var f = this.formPanel.getForm();
		
		f.load({
			url: _this.baseUrl+"&task=formeditLoadForm",
			params: {fid: _this.edit_id},
			method:'POST',
			waitMsg: CNOA.lang.msgLoadMask,
			success: function(form, action){
				
			}.createDelegate(this),
			failure: function(form, action) {
				_this.close();
				CNOA.msg.alert(action.result.msg, function(){
					
				});
			}.createDelegate(this)
		});
	}
}

/**
* 主面板-列表
*
*/
CNOA_flow_flow_settingformClass = CNOA.Class.create();
CNOA_flow_flow_settingformClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;
		this.sort = 0;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=setting";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		
		this.ID_find_name	=	Ext.id();
		this.ID_find_flowFrom	=	Ext.id();
		
		this.storeBar = {
			flowFrom : '',
			name : ''
		}
		
		this.fields = [
			{name:"fid"},
			{name:"name"},
			{name:"about"},
			{name:"sname"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getFormJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		
		this.flowFrom = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl + "&task=getFlowFrom", disableCaching: true}),   
			reader:new Ext.data.JsonReader({
				root:"data", 
				fields: [
					{name: 'sid'}, 
					{name: 'name'}
				]
			}),
			listeners:{
				'load': function(th, record){
					
				}
			},
			autoLoad: true
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "fid", dataIndex: 'fid', hidden: true},
			{header: '表单名称', dataIndex: 'name', width: 100, sortable: true, menuDisabled :true},
			{header: "备注", dataIndex: 'about', width: 120, sortable: false,menuDisabled :true},
			{header: "所属分类", dataIndex: 'sname', width: 70, sortable: false,menuDisabled :true},
			{header: "操作", dataIndex: 'fid', width: 100, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			emptyMsg:"没有数据显示",
			displayMsg:"显示第{0}--{1}条数据，共{2}条",   
			store: this.store,
			style: 'border-left-width:1px;',
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					params.sort = _this.sort;
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			bodyStyle: 'border-left-width:1px;',
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},'-',
					{
						handler : function(button, event) {
							CNOA_flow_flow_settingform_addedit = new CNOA_flow_flow_settingform_addeditClass("Add");
							CNOA_flow_flow_settingform_addedit.show();
						}.createDelegate(this),
						iconCls: 'icon-utils-s-add',
						text : '新建表单'
					},'-',
					'表单名称：',
					{
						xtype: 'textfield',
						id: _this.ID_find_name,
						name: 'name',
						width: 110
					},'所属流程分类',
					{
						xtype: 'combo',
						id:_this.ID_find_flowFrom,
						width: 110,		
						name: 'flowFrom',
						valueField: 'sid',
						hiddenName: 'worktimeId',
						displayField: 'name',
						triggerAction: 'all',
						mode: 'local',
						editable: false,
						store: _this.flowFrom,
						listeners:{
							'select' : function(combo, record, index){
								
							}
						}
					},
					'-',
					{
						text: '查询',
						cls: 'x-btn-over',
						listeners: {
							'mouseout': function(btn){
								btn.addClass('x-btn-over');
								
							}
						},
						handler: function(){
							_this.storeBar = {
								name: Ext.getCmp(_this.ID_find_name).getValue(),
								flowFrom: Ext.getCmp(_this.ID_find_flowFrom).getValue()
							};
							_this.store.load({params:_this.storeBar});
						}
					},'-',
					{
						text: '清空',
						handler: function(){
							Ext.getCmp(_this.ID_find_name).setValue('');
							Ext.getCmp(_this.ID_find_flowFrom).setValue('');
							
							_this.storeBar = {
								name: '',
								flowFrom: ''
							}
							_this.store.load();
						}
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 12}
				]
			}),
			bbar: this.pagingBar
		});
		
		
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			text: "所有分类",
			sid: '0',
			iconCls: "icon-tree-root-cnoa",
			id: this.ID_tree_treeRoot,
			expanded: true//,
		});
		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl+"&task=getSortList&type=tree",
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
				"load" : function(node){
					_this.sortTree.selectPath(_this.ID_tree_treeRoot);
				}.createDelegate(this)
			}
		})
		this.sortTree = new Ext.tree.TreePanel({
			hideBorders: true,
			border: false,
			rootVisible: true,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: this.treeLoader,
			root: this.treeRoot,
			autoScroll: true,
			listeners:{
				"click":function(node){
					_this.sort = node.attributes.sid;
					_this.store.load({params:{sort: _this.sort}});
				}
			}
		});
		this.sortPanel = new Ext.Panel({
			region: 'west',
			layout:'fit',
			split: true,
			width: 140,
			minWidth: 80,
			maxWidth: 380,
			bodyStyle: 'border-right-width:1px;',
			items: [this.sortTree],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					"流程分类",
					"->",
					//刷新tree
					{
						handler : function(button, event) {
							_this.treeRoot.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: CNOA.lang.mainUser.refreshList,
						text : CNOA.lang.mainUser.refreshList
					}
				]
			})
		});



		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid, this.sortPanel]//, this.bottomPanel
		});
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		var h  = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_flow_flow_settingform.editPanel("+value+");'><img src='"+src+"page_addedit.png' style='margin:0 2px 4px 0' align='absmiddle' />编辑</a>";
			h += "&nbsp;&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_settingform.designForm("+value+");'><img src='"+src+"frame-edit.png' style='margin:0 2px 4px 0' align='absmiddle' />设计表单</a>";
			h += "&nbsp;&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_settingform.deleteList("+value+");'><img src='"+src+"cross.gif' style='margin:0 2px 4px 0' align='absmiddle' />删除</a>";
			h += "</div>";
		return h;
	},
	
	designForm : function(fid){
		var _this = this;
		
		var iframeUrl = this.baseUrl + "&task=formdesign&fid="+fid;
		
		var box = Ext.getBody().getBox();
		var w	= box.width - 60;
		var h	= box.height - 60;
		var ID_iframe = Ext.id();
		
		var submit = function(content, config){
			if(content === false){
				return;
			}
			win.getEl().mask(CNOA.lang.msgLoadMask);
			
			Ext.Ajax.request({  
				url: _this.baseUrl + "&task=saveFormDesignData",
				method: 'POST',
				params: {content: content, fid: fid},
				success: function(r) {
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						CNOA.msg.alert(result.msg, function(){
							if(config.close){
								win.close();
							}
						});
					}else{
						CNOA.msg.alert(result.msg, function(){});
					}
					win.getEl().unmask();
				}
			});
		};
		
		this.fckLoaded = function(){
			win.getEl().unmask();
		};
		
		var win = new Ext.Window({
			title: "智能表单设计器",
			width: w,
			height: h,
			layout: "fit",
			modal: true,
			maximizable: true,
			resizable: true,
			html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" id="'+ID_iframe+'"></iframe>',
			buttons: [
				{
					text: "保存",
					iconCls: 'icon-order-s-accept',
					handler: function(){
						//Ext.getDom(ID_iframe).contentWindow.getContentText();
						submit(Ext.getDom(ID_iframe).contentWindow.getContentText(), {close: false});
					}
				},
				{
					text: "保存并关闭",
					iconCls: 'icon-order-s-accept',
					handler: function(){
						//Ext.getDom(ID_iframe).contentWindow.getContentText();
						submit(Ext.getDom(ID_iframe).contentWindow.getContentText(), {close: true});
					}
				},   //关闭
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			],
			listeners: {
				show : function(win){
					Ext.getDom(ID_iframe).contentWindow.location.href = iframeUrl+'&r='+Math.random();
					win.getEl().mask(CNOA.lang.msgLoadMask);
				},
				
				close : function(){
					try{Ext.getDom(ID_iframe).src = "";}catch(e){}
				}
			}
		}).show();
	},
	
	deleteList : function(fid){
		var _this = this;
		
		CNOA.msg.cf("确认删除吗？", function(btn){
			if(btn == "yes"){
				Ext.Ajax.request({  
					url: _this.baseUrl + "&task=formdelete",
					method: 'POST',
					params: { fid: fid },
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.alert(result.msg, function(){
								_this.store.reload();
							});
						}else{
							CNOA.msg.alert(result.msg);
						}
					}
				});
			}
		});
	},
	
	editPanel : function(fid){
		CNOA_flow_flow_settingform_addedit = new CNOA_flow_flow_settingform_addeditClass("Edit");
		CNOA_flow_flow_settingform_addedit.edit_id = fid;
		CNOA_flow_flow_settingform_addedit.show();
	}
}

//定义全局变量：
var CNOA_flow_flow_settingsortClass, CNOA_flow_flow_settingsort;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_settingsortClass = CNOA.Class.create();
CNOA_flow_flow_settingsortClass.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=setting";
		
		this.edit_id = 0;
		//var b = this;
		

		this.ID_btn_add = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_addedit_save = Ext.id();
		
		this.ID_textarea_deptNames=Ext.id();
		this.ID_button_deptNames=Ext.id();
		this.ID_allUserButton=Ext.id();

		this.fields = [
			{name:"sid"},
			{name:"name"},
			{name:"type"},
			{name:"about"}
		];

		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+'&task=sortGetJsonData'}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});

		this.store.load({params:{start:0}});

		this.sm = new Ext.grid.CheckboxSelectionModel({singleSelect:true}); 

		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "sid", dataIndex: 'sid', width: 20, sortable: true, hidden: true},
			{header: "分类名称", dataIndex: 'name', width: 80, sortable: true},
			{header: "备注", dataIndex: 'about', width: 120, sortable: true},
			{header: "类型", dataIndex: 'type', width: 60, sortable: true, renderer:this.makeType.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);

		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			sm: this.sm,
			hideBorders: true,
			autoWidth: true,
			hideBorders: true,
			border: false,
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				//双击  
				rowdblclick : function(grid, rowIndex){
					var sid = grid.getStore().getAt(rowIndex).get("sid");
					_this.editPanel(sid);
				},  
				//单击  
				rowclick:function(grid, row){  
				}
			}
		});
		
		this.centerPanel = new Ext.Panel({
			region: 'center',
			layout: "fit",
			autoScroll: true,
			items: [this.grid],
			tbar : new Ext.Toolbar({
				items: [
					{
						id: this.ID_btn_refreshList,
						handler : function(button, event) {
							CNOA_flow_flow_settingsort.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: CNOA.lang.mainJob.refreshList,
						text : CNOA.lang.mainJob.refreshList
					},'-',{
						id: this.ID_btn_add,
						handler : function(button, event) {
							_this.showAddEditWindow("sortAdd");
						},
						iconCls: 'icon-utils-s-add',
						text : CNOA.lang.mainJob.newJob
					},'-',{
						text : CNOA.lang.mainJob.editJob,
						iconCls: 'icon-utils-s-edit',
						handler : function(button, event) {
							var rows = _this.grid.getSelectionModel().getSelections();
							if (rows.length == 0) {
								var position = button.getEl().getBox();
									position = [position['x']+12, position['y']+26];
					
								CNOA.miniMsg.alert(CNOA.lang.mainJob.oneMoreDataToEdit, position);
							} else {
								_this.editPanel(rows[0].get("sid"));
							}
						}.createDelegate(this)
					},'-',{
						text : CNOA.lang.mainJob.delJob,
						iconCls: 'icon-utils-s-delete',
						handler : function(button, event) {
							var rows = this.grid.getSelectionModel().getSelections();
							if (rows.length == 0) {
								var position = button.getEl().getBox();
									position = [position['x']+12, position['y']+26];

								CNOA.miniMsg.alert(CNOA.lang.mainJob.oneMoreDataToDel, position);
							} else {
								CNOA.msg.cf("确定要删除该分类吗？", function(btn) {
									if (btn == 'yes') {
										if (rows) {
											var ids = "";
											ids += rows[0].get("sid");
											_this.deleteRecord(ids);
										}
									}
								});
							} // 弹出对话框警告 
							//this.onButtonClick(button, event);
						}.createDelegate(this)
					},
					'-',
					//如果没有修改权限:
					"<span style='color:#999'>"+CNOA.lang.mainJob.toolBarInfo+"</span>",
					"->",{xtype: 'cnoa_helpBtn', helpid: 13}
				]
			})
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border', 
			autoScroll: true,
			items: [this.centerPanel]
		});
	},
	
	makeType : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var h  = value == "user" ? "自定义" : "系统分类";
		
		return h;
	},
	
	editPanel : function(sid){
		var _this = this;

		_this.edit_id = sid;

		this.showAddEditWindow("sortEdit");
	},
	
	showAddEditWindow : function(ac){
		var _this = this;
		
		var title = ac == "sortEdit" ? "修改分类" : "添加分类";
		
		var loadForm = function(){
			var f = form.getForm();
			f.load({
				url: _this.baseUrl+"&task=sortLoadFormData",
				params: {sid: _this.edit_id},
				method:'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				success: function(form, action){
					if(action.result.data.type == 'sys'){
						//Ext.getCmp(_this.ID_btn_addedit_save).disable();
					}
				}.createDelegate(this),
				failure: function(form, action) {
					win.close();
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});
		};
		
		var form = new Ext.form.FormPanel({
			
			labelAlign: "right",
			//labelWidth: 80,
			waitMsgTarget: true,
			border: false,
			bodyStyle: "padding: 10px;",
			items: [
				{
					xtype: "textfield",
					width: 320,
					fieldLabel: "分类名称",
					allowBlank: false,
					name: "name"
				},
				{
					xtype: "textarea",
					fieldLabel: "备注",
					name: "about",
					width: 320,
					height: 100
				},
				{
					xtype: "textarea",
					id:this.ID_textarea_deptNames,
					height:60,
					width:320,
					readOnly: true,
					allowBlank:false,
					fieldLabel: '流程查看部门',
					name: "deptNames"
				},
				{
					xtype: "hidden",
					name: "deptIds"
				},
				{
					xtype: "deptMultipleSelector",
					autoWidth: true,
					style: "margin-left:105px;",
					id: this.ID_button_deptNames,
					deptListUrl : this.baseUrl + "&task=getStructTree",
					listeners:{
						"selected" : function(th, textString, idString){
							form.getForm().findField("deptNames").setValue(textString);
							form.getForm().findField("deptIds").setValue(idString);
						},
						"load" : function(th){
							th.setSelectedIds(form.getForm().findField("deptIds").getValue());
						}
					}
				},
				{
					xtype: "textarea",
					height:60,
					width:320,
					allowBlank:false,
					readOnly: true,
					fieldLabel: '流程删除权限(选择人)',
					name: "allUserNames"
				},
				{
					xtype: "hidden",
					name: "allUids"
				},
				{
					xtype: "buttonForSMS",
					autoWidth: true,
					id:this.ID_allUserButton,
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					style: "margin-left:105px;",
					text: "选择人员",
					listeners: {
						"selected" : function(th, data){
							var names = new Array();
							var uids = new Array();
							if (data.length>0){
								for (var i=0;i<data.length;i++){
									names.push(data[i].uname);
									uids.push(data[i].uid);
								}
							}
							form.getForm().findField("allUserNames").setValue(names.join(","));
							form.getForm().findField("allUids").setValue(uids.join(","));
						},
						"onrender" : function(th){
							th.setSelectedUids(form.getForm().findField("allUids").getValue().split(","));
						}
					}
				}
			],
			listeners: {
				afterrender : function(th){
					if(ac == "sortEdit"){
						loadForm();
					}
				}
			}
		});
		var win = new Ext.Window({
			title: title,
			modal: true,
			layout: "fit",
			width: 500,
			height: 400,
			resizable: false,
			items: [form],
			buttons: [
				{
					text : "保存",
					id: _this.ID_btn_addedit_save,
					iconCls: 'icon-order-s-accept',
					handler : function() {
						submit();
					}
				},
				//关闭
				{
					text : CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}
				}
			]
		}).show();
		
		var submit = function(){
			var f = form.getForm();
			if (f.isValid()) {
				f.submit({
					url: _this.baseUrl+"&task="+ac,
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {sid : _this.edit_id},
					success: function(form, action) {
						_this.store.reload();
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
						}.createDelegate(this));
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							
						}.createDelegate(this));
					}.createDelegate(this)
				});
			}else{
				var position = Ext.getCmp(_this.ID_btn_addedit_save).getEl().getBox();
					position = [position['x']+35, position['y']+26];

				CNOA.miniMsg.alert("部分表单未完成,请检查", position);
			}
		}
	},

	deleteRecord: function(sid){
		var _this = this;
		
		Ext.Ajax.request({
			url: _this.baseUrl+"&task=sortDelete",
			method: 'POST',
			params: { sid: sid },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.alert(result.msg, function(){
						_this.store.reload();
					});
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	}
}


var CNOA_flow_flow_manage_flowlistClass, CNOA_flow_flow_manage_flowlist;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_manage_flowlistClass = CNOA.Class.create();
CNOA_flow_flow_manage_flowlistClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;
		this.sort = 0;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=manage";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		this.fields = [
			{name:"ulid"},
			{name:"lid"},
			{name:"name"},
			{name:"flowname"},
			{name:"title"},
			{name:"level"},
			{name:"stepText"},
			{name:"uname"},
			{name:"posttime"},
			{name:"status"},
			{name:"allowOperate"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getdealflowJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			//this.sm,
			{header: "ulid", dataIndex: 'ulid', hidden: true},
			{header: '流程编号 / 标题', dataIndex: 'name', width: 240, sortable: true, menuDisabled :true, renderer:this.makeTitle.createDelegate(this)},
			{header: '所属流程', dataIndex: 'flowname', width: 120, sortable: true, menuDisabled :true},
			{header: "重要等级", dataIndex: 'level', width: 60, sortable: false,menuDisabled :true},
			{header: "当前步骤", dataIndex: 'stepText', width: 150, sortable: false,menuDisabled :true},
			{header: "状态", dataIndex: 'status', width: 50, sortable: false,menuDisabled :true, renderer:this.makeStatus.createDelegate(this)},
			{header: "发起人 / 发起日期", dataIndex: 'uname', width: 150, sortable: false,menuDisabled :true, renderer:this.makeUname.createDelegate(this)},
			{header: "查看 / 操作", dataIndex: 'ulid', width: 150, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			emptyMsg:"没有数据显示",
			displayMsg:"显示第{0}--{1}条数据，共{2}条",   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					params.sort = _this.sort;
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				//forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 9}
				]
			}),
			bbar: this.pagingBar
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid]//, this.bottomPanel
		});
	},
	
	makeTitle : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		return value + "<br /><span class='cnoa_color_gray'>标题: " + rd.title + "</span>";
	},
	
	makeUname : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		return value + "<br /><span class='cnoa_color_gray'>" + rd.posttime + "</span>";
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>未发送</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>办理中</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>已办理</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>已退件</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_gray'>已撤销</span>";}
		return h;
	},

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;
		var ro = rd.allowOperate;

		var h  = "";
		
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_manage_flowlist.showFlow("+value+");' ext:qtip='查看详细内容'>查看</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_manage_flowlist.viewFlowEvent("+value+", \""+rd.name+"\");' ext:qtip='查看流程事件日志'>事件</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_manage_flowlist.viewFlowProgress("+value+", \""+rd.name+"\");' ext:qtip='查看流程办理进度'>进度</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_manage_flowlist.viewFlow2("+rd.lid+", \""+rd.flowname+"\");' ext:qtip='查看流程图'>[流程图]</a>";
			h += "<br />";
			
			h += ro == 1 ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_manage_flowlist.viewFlow("+value+");' ext:qtip='办理流程'><span class='cnoa_color_red'>办理</span></a>" : "<span class='cnoa_color_gray2'>办理</span>";
			//h += "&nbsp;&nbsp;";
			//h += ro == 1 ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_manage_flowlist.deleteFlow("+value+");' ext:qtip='把流程委托给他人办理'>委托</a>" : "";

		return h;
	},
	
	//查看流程(操作)
	showFlow : function(ulid){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_VIEWFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=showflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_SHOWFLOW", "查看工作流程", "icon-flow");
	},
	
	//查看流程(操作)
	viewFlow : function(ulid){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_VIEWFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=viewflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_VIEWFLOW", "查看工作流程", "icon-flow");
	},
	
	//查看流程图
	viewFlow2 : function(lid, name){
		CNOA_flow_flow_flowpreview = new CNOA_flow_flow_flowpreviewClass(lid, name);
		CNOA_flow_flow_flowpreview.show();
	},
	
	viewFlowEvent : function(ulid, title){
		var gridEvent = new CNOA_flow_flow_flowViewEventClass(ulid, false);
		
		var win = new Ext.Window({
			title: "查看流程详细事件 - " + title,
			layout: "fit",
			width: 650,
			height: 300,
			modal: true,
			maximizable: true,
			items: [gridEvent.grid],
			buttons : [
				{
					text : "关闭",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	viewFlowProgress : function(ulid, title){
		var gridEvent = new CNOA_flow_flow_flowViewProgressClass(ulid, false);
		
		var win = new Ext.Window({
			title: "查看流程详细进度 - " + title,
			layout: "fit",
			width: 650,
			height: 300,
			modal: true,
			maximizable: true,
			items: [gridEvent.grid],
			buttons : [
				{
					text : "关闭",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	}
}


var cnoa_flow_user_flow_attach_btn = "";
function makeFlowAttacBtn(user_flow_atach_id, url, name, size){
	var getDownLink = function(name, rt){
		var url,tp='none';
		var ext = name.substring(name.lastIndexOf(".")+1).toLowerCase();
		switch (ext){
			case "jpg":
			case "bmp":
			case "png":	 url = "jpg.gif";tp='pic';break;
			case "rar":
			case "zip":
			case "7z":	 url = "rar.gif";break;
			case "txt":	 url = "txt.gif";tp='txt';break;
			case "xls":
			case "xlsx": url = "excel.gif";tp='xls';break;
			case "doc":
			case "docx": url = "word.gif";tp='doc';break;
			//case "ppt":
			//case "pptx": url = "word.gif";break;
			default:     url = "file.gif";break;
		}
		if(rt == true){
			return tp;
		}else{
			return '<img src="./resources/images/icons_file/'+url+'" width=16 height=16 align="absmiddle" >'+name+' ('+size+')';
		}
	};
	var a = Ext.fly(user_flow_atach_id);
	a.dom.innerHTML = getDownLink(name);
	var treeMenu = new Ext.menu.Menu({
		id: "menu_"+user_flow_atach_id,
		items: [
			{
				text: name,
				disabled: true
			},"-",
			{
				xtype: "swfdownloadmenuitem",
				text: "下载",
				iconCls: 'icon-file-down',
				dlurl: url,
				dlname: name
			},
			{
				text: "查看 / 预览",
				hidden: (getDownLink(name, true)=="pic" || getDownLink(name, true)=="txt" || getDownLink(name, true)=="xls") ? false : true,
				handler: function(){
					window.open("./resources/preview.php?url="+encodeURIComponent(url));
				}
			},
			//{ text: "下载", pressed: true },
			{
				text: "编辑",
				pressed: false,
				hidden: true,
				//hidden: (!Ext.isAir && (getDownLink(name, true)=="doc" || getDownLink(name, true)=="xls")) ? false : true,
				handler: function(){
					window.open("./index.php?action=commonJob&act=viewDocForActivex&url="+encodeURIComponent(url));
				}
			}
		]
	});
	a.dom.onmouseover = function(position){
		try{
			Ext.getCmp(cnoa_flow_user_flow_attach_btn).hide();
			Ext.fly(cnoa_flow_user_flow_attach_btn.replace("menu_", "")).dom.style.backgroundColor = "#F2E6E6";
		}catch(e){}
		Ext.fly(user_flow_atach_id).dom.style.backgroundColor = "#CEC1FF";
		if(Ext.isIE){
			xy = [window.event.clientX, window.event.clientY];
		}else{
			var xy = [position.clientX, position.clientY];
		}
		
		treeMenu.showAt(xy);
		cnoa_flow_user_flow_attach_btn = "menu_"+user_flow_atach_id;
	};
}

/*
 * 阅读者列表
 * 
 */
/*
CNOA_flow_flow_view_readerClass = CNOA.Class.create();
CNOA_flow_flow_view_readerClass.prototype = {
	
	init:function(ulid){
		var _this = this;
		
		this.ulid = ulid;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.fields = [
			{name:"jid"},
			{name:"name"},
			{name:"viewtime"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getReaderList&ulid="+this.ulid, disableCaching: true}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});
		this.store.load();
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "所属部门", dataIndex: 'jid', width: 100, sortable: true, menuDisabled :true},
			{header: "名字", dataIndex: 'name', width: 100, sortable: false,menuDisabled :true},
			{header: "日期", dataIndex: 'viewtime', width: 150, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		
		
		this.readerListFrom = new Ext.grid.GridPanel({
			store: this.store,
			bodyStyle: 'border-left-width:1px;',
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			sm:this.sm,
			hideBorders: true,
			border: false,
			viewConfig: {
				forceFit: true
			}
		});
		
		this.window = new Ext.Window({
			layout:"fit",
			width:600,
			height:500,
			title:"阅读情况",
			resizable:false,
			modal:true,
			items:[this.readerListFrom],
			tbar:new Ext.Toolbar({
				items: ["红色人名表示已阅读用户","-","灰色表示未阅读用户"]
			}),
			buttons:[
				//关闭
				{
					text : CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						this.close();
					}.createDelegate(this)
				}
			]
		});
		
	},
	
	show:function(){
		this.window.show();
	},
	
	close:function(){
		this.window.close();
	}
}
*/
var CNOA_flow_flow_flowViewReaderClass = CNOA.Class.create();
CNOA_flow_flow_flowViewReaderClass.prototype = {
	init : function(ulid){
		var _this = this;

		this.ulid = ulid;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.fields = [
			{name:"jid"},
			{name:"name"},
			{name:"viewtime"},
			{name:"say"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getReaderList&ulid="+this.ulid, disableCaching: true}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});
		this.store.load();
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "名字", dataIndex: 'name', width: 100, sortable: false,menuDisabled :true},
			{header: "所属部门", dataIndex: 'jid', width: 124, sortable: true, menuDisabled :true},
			{header: "评阅内容", dataIndex: 'say', width: 267, sortable: true, menuDisabled :true},
			{header: "日期", dataIndex: 'viewtime', width: 100, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			bodyStyle: 'border-left-width:1px;',
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			sm:this.sm,
			hideBorders: true,
			border: false,
			autoHeight: true,
			viewConfig: {
				//forceFit: true
			},
			listeners:{ 
				//单击  
				"rowclick" : function(grid, rowIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					var win = new Ext.Window({
						title: "查看流程评阅意见",
						layout: "fit",
						width: 400,
						height: 260,
						modal: true,
						maximizable: true,
						items: [
							{
								xtype: "panel",
								border: false,
								autoScroll: true,
								bodyStyle: "padding:10px;",
								html: record.data.say.replace(/\r\n/ig,"<br />").replace(/\n/ig,"<br />")
							}
						],
						buttons : [
							{
								text : "关闭",
								iconCls: 'icon-dialog-cancel',
								handler : function() {
									win.close();
								}.createDelegate(this)
							}
						]
					}).show();
				}
			}
		});
	},
	
	showSay : function(){
		var _this = this;

		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			autoScroll: true,
			bodyStyle: "padding-top:10px;",
			listeners: {
				"render" : function(form){
					
				}
			},
			items: [
				{
					xtype: "textarea",
					anchor: "-25 -25",
					name: "say",
					allowBlank: false,
					fieldLabel: "评阅意见"
				}
			]
		});
		
		var win = new Ext.Window({
			width: 440,
			height: 310,
			title: "发表评阅意见",
			resizable: true,
			modal: true,
			layout: "fit",
			items: [formPanel],
			tbar: new Ext.Toolbar({
				items: "如果已经发表过意见，则本次意见会替换掉原来的意见"
			}),
			buttons : [
				//汇报
				{
					text : "确定",
					iconCls: 'icon-order-s-accept',
					handler : function() {
						submit();
					}.createDelegate(this)
				},
				//关闭
				{
					text : "取消",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
		
		var submit = function(){
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: _this.baseUrl+"&task=addDispenseSay",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							
							_this.store.load();
							
							win.close();
						}.createDelegate(this));
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
						}.createDelegate(this));
					}.createDelegate(this)
				});
			}
		}
	}
}


/*
查看流程进度
*/
var CNOA_flow_flow_flowViewProgressClass = CNOA.Class.create();
CNOA_flow_flow_flowViewProgressClass.prototype = {
	init : function(ulid, autoHeight, autoGroup){
		var _this = this;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		this.ulid = ulid;
		if(autoHeight == undefined){
			this.autoHeight = true;
		}else{
			this.autoHeight = autoHeight;
		}
		if(autoGroup == undefined){
			this.autoGroup = true;
		}else{
			this.autoGroup = autoGroup;
		}

		this.renderProgressStatusText = function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			var st = record.data.status;
			
			var co = "#00000";
	
			if(st == 1){
				co = "#FF0000";
			}else if(st == 2){
				co = "#008000";
			}else if(st == 3){
				co = "#FF00FF";
			}else if(st == 4){
				co = "#808080";
			}
			var h = "<span style='color:"+co+";'>"+value+"</span>";
			return h;
		};
		this.renderNodenameText = function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			return "<span ext:qtip='"+value+"'>"+value+"</span>";
		};
		this.renderProgressOperatText = function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			var rd = record.data;
			
			var h = value + "<br />" + "<span style='color:#808080;'>"+rd.stime+"</span>";
			return h;
		};
		this.fields = [
			{name: 'unid'},
			{name: 'status'},
			{name: 'statusText'},
			{name: 'stepid'},
			{name: 'uname'},
			{name: 'stime'},
			{name: 'utime'},
			{name: 'say'},
			{name: 'nodename'},
			{name: 'nodetype'}
		];
		if(this.autoGroup){
			this.store = new Ext.data.GroupingStore({   
				proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=getProgressList&ulid="+this.ulid}),
				reader:new Ext.data.JsonReader({totalProperty:"totalPorperty",root:"data", fields: this.fields}),
				sortInfo:{field: 'unid', direction: "ASC"},
				groupOnSort: false,
				groupField:'nodename',
				//相当诡异
				sortData: function() {}
			});
		}else{
			this.store = new Ext.data.GroupingStore({   
				proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=getProgressList&ulid="+this.ulid}),
				reader:new Ext.data.JsonReader({totalProperty:"totalPorperty",root:"data", fields: this.fields})
			});
		}
		
		this.store.load({params:{start:0,limit:15}});
		//this.store.clearGrouping();
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			autoScroll: true,
			hideBorders: true,
			border: false,
			columns: [
				new Ext.grid.RowNumberer(),
				{header: "unid", width: 10, sortable: true, dataIndex: 'unid', hidden: true},
				{header: "步骤名称", width: 160, sortable: true, dataIndex: 'nodename',renderer : this.renderNodenameText},
				{header: "状态", width: 60, sortable: true, dataIndex: 'statusText',renderer : this.renderProgressStatusText},
				{header: "经办人 / 开始时间", width: 250, sortable: true, dataIndex: 'uname',renderer : this.renderProgressOperatText},
				{header: "持续时间", width: 60, sortable: true, dataIndex: 'utime'},
				{header: "办理理由", width: 160, sortable: true, dataIndex: 'say', id: 'say'}
			],
			autoExpandColumn: 'say',
			//view: new Ext.grid.GroupingView({
			//	forceFit: true
			//}),
			autoHeight: this.autoHeight,
			listeners: {
				rowclick: function(grid, rowIndex){
					var record = grid.getStore().getAt(rowIndex);
					var win = new Ext.Window({
						title: "查看流程事件办理理由",
						layout: "fit",
						width: 400,
						height: 260,
						modal: true,
						maximizable: true,
						items: [
							{
								xtype: "panel",
								border: false,
								autoScroll: true,
								bodyStyle: "padding:10px;",
								html: record.data.say.replace(/\r\n/ig,"<br />").replace(/\n/ig,"<br />")
							}
						],
						buttons : [
							{
								text : "关闭",
								iconCls: 'icon-dialog-cancel',
								handler : function() {
									win.close();
								}.createDelegate(this)
							}
						]
					}).show();
				}
			}
		});
	}
}

/*
查看流程事件(日志)
*/
var CNOA_flow_flow_flowViewEventClass = CNOA.Class.create();
CNOA_flow_flow_flowViewEventClass.prototype = {
	init : function(ulid, autoHeight){
		var _this = this;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		this.ulid = ulid;
		this.autoHeight = autoHeight == undefined ? true : false;

		this.renderEventType = function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			var rd = record.data;
			var co = "#00000";
	
			if(rd.type == 1){
				co = "#FF0000";
			}else if(rd.type == 2){
				co = "#008000";
			}else if(rd.type == 3){
				co = "#FF8040";
			}else if(rd.type == 4){
				co = "#FF00FF";
			}else if(rd.type == 5){
				co = "#008000";
			}else if(rd.type == 6){
				co = "#408080";
			}else if(rd.type == 7){
				co = "#0080FF";
			}
			var h = "<span style='color:#FFF;display:block;height:16px;background-color:"+co+";text-indent:3px;'>"+rd.typename+"</span>";
			return h;
		};
		this.renderStepnameText = function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			return "<span ext:qtip='"+value+"'>"+value+"</span>";
		};
		this.renderEventOther = function(value, cellmeta, record){
			var rd = record.data;
			var h  = rd.uname+"<br />";
				h += "<span style='color:#8C8C8C'>"+rd.posttime+"</span>";
			return h; 
		};
		this.fields = [
			{name:"eid"},
			{name:"type"},
			{name:"typename"},
			{name:"title"},
			{name:"say"},
			{name:"uname"},
			{name:"stepname"},
			{name:"posttime"}
		];
		this.store = new Ext.data.GroupingStore({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getEventList&ulid="+_this.ulid}),
			//groupField: 'date',
			//sortInfo: {field: 'id', direction: 'ASC'},
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});
		this.store.load();
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "eid", dataIndex: 'eid', width: 20, sortable: true, menuDisabled: true, hidden: true},
			{header: "类型", dataIndex: 'type', width: 80, menuDisabled: true, sortable: true, renderer : this.renderEventType.createDelegate(this)},
			{header: "步骤", dataIndex: 'stepname', width: 100, menuDisabled: true, sortable: true, renderer : this.renderStepnameText.createDelegate(this)},
			{header: "经办人 / 时间", dataIndex: 'other', width: 150, sortable: false,resizable: false, menuDisabled: true, renderer : this.renderEventOther.createDelegate(this)},
			{header: "办理理由(点击查看)", dataIndex: 'say', id: 'say', width: 260, menuDisabled: true, sortable: false}
		]);
		this.grid = new Ext.grid.GridPanel({
			ds: this.store,
			//hideHeaders: true,
			autoScroll: true,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			autoHeight: this.autoHeight,
			//viewConfig: {
			//	forceFit: false
			//},
			autoExpandColumn: 'say',
			listeners:{  
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var rd = grid.getStore().getAt(rowIndex).data;
					if(rd.say!=""){
						var win = new Ext.Window({
							title: "查看流程事件办理理由",
							layout: "fit",
							width: 400,
							height: 260,
							modal: true,
							maximizable: true,
							items: [
								{
									xtype: "panel",
									border: false,
									autoScroll: true,
									bodyStyle: "padding:10px;",
									html: rd.say.replace(/\r\n/ig,"<br />").replace(/\n/ig,"<br />")
								}
							],
							buttons : [
								{
									text : "关闭",
									iconCls: 'icon-dialog-cancel',
									handler : function() {
										win.close();
									}.createDelegate(this)
								}
							]
						}).show();
					}
				}
			}
		});
	}
}

/*
查看流程图
*/
var CNOA_flow_flow_flowpreviewClass, CNOA_flow_flow_flowpreview;
CNOA_flow_flow_flowpreviewClass = CNOA.Class.create();
CNOA_flow_flow_flowpreviewClass.prototype = {
	init: function(flowId, flowName){
		var _this = this;

		this.ID_window_numberTip = Ext.id();
		this.ID_btn_addedit_save = Ext.id();
		this.ID_CNOA_flow_flow_setting_design_ct = Ext.id();
		
		this.ID_DESIGN_BUTTON = "flow_flow_user_flowpreview_button_";
		this.ID_DESIGN_LINE = "flow_flow_user_flowpreview_line_";
		
		this.flowId = flowId;
		this.flowName = flowName;
		this.data = null;
		this.newStepCount = 1;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.treeRoot = new Ext.tree.TreeNode({
			expanded: true
		}); 
		this.treePanel = new Ext.tree.TreePanel({
			root: this.treeRoot,
			border: false,
			id: "flow_flow_preview",
			//useArrows: true,
			rootVisible: false
		});
		this.rightPanel = new Ext.Panel({
			border: false,
			region: "east",
			width: 180,
			minWidth: 180,
			maxWidth: 180,
			split: true,
			autoScroll:true,
			bodyStyle: "padding-top:4px;",
			items: [this.treePanel]
		});
		
		this.centerPanel = new Ext.Panel({
			border: false,
			region: "center",
			autoScroll: true,
			bodyStyle: "background:#FFF url('./resources/images/mxgraph/grid.gif');",
			html: "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center' valign='top' style='padding-top:20px;'><div id='"+this.ID_CNOA_flow_flow_setting_design_ct+"'></div></td></tr></table>",
			listeners: {
				afterrender: function(th){
					_this.readFlowData();
				}
			}
		});
		
		this.mainPanel = new Ext.Window({
			title: "查看流程 - " + this.flowName,
			width: 700,
			height: 600,
			layout: "border",
			modal: true,
			maximizable: true,
			resizable: true,
			items: [this.centerPanel, this.rightPanel],
			buttons: [
				{
					text: CNOA.lang.btnClose,
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
	
	readFlowData : function(){
		var _this = this;
		
		_this.mainPanel.getEl().mask(CNOA.lang.msgLoadMask);

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=flowpreviewLoadData",
			method: 'POST',
			params: { lid: _this.flowId },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					if(result.data.length < 1){
						_this.data = [
							{
								name: "开始",
								id: "0",
								type: "start"
							}
						];
					}else{
						_this.data = result.data;
					}
				}else{
					CNOA.msg.alert(result.msg);
				}
				
				_this.makeFlowViewport();
				
				_this.mainPanel.getEl().unmask();
			}
		});
	},
	
	//生成流程图
	makeFlowViewport : function(){
		var _this = this;
		
		document.getElementById(_this.ID_CNOA_flow_flow_setting_design_ct).innerHTML = "";
		
		var lastLineId = 0;
		Ext.each(_this.data, function(v, i, c){
			_this.createButton(v.id, v.name, v.type);
			_this.createLine(v.id);
			lastLineId = v.id;
		});
		
		//删除最后一个连线
		try{
			var p = document.getElementById(_this.ID_CNOA_flow_flow_setting_design_ct);
			var s = document.getElementById(_this.ID_DESIGN_LINE+lastLineId);
			p.removeChild(s);
		}catch(e){}
	},
	
	//删除流程节点
	deleteNode : function(nodeId){
		var _this = this;
		
		var tmp = new Array();
		var id = 0;
		
		Ext.each(_this.data, function(v, i, c){
			if(v.id != nodeId){
				v.id = id;
				tmp.push(v);
				id++;
			}
		});
		
		_this.data = tmp;
		
		_this.makeFlowViewport();
	},
	
	//添加流程节点
	addNode : function(preNodeId){
		var _this = this;
		
		var tmp = new Array();
		
		var node = {
			name : "新步骤"+this.newStepCount++,
			type : "node"
		}
		
		var id = 0;
		Ext.each(_this.data, function(v, i, c){
			v.id = id;
			tmp.push(v);
			id++;
			if(v.id == preNodeId){
				node.id = id;
				tmp.push(node);
				id++;
			}
		});
		
		_this.data = tmp;
		
		_this.makeFlowViewport();
	},
	
	//生成流程节点按钮
	createButton : function(id, name, type){
		var _this = this;
			
		new Ext.Button({
			text: name,
			scale: 'large',
			btype: type,
			id: this.ID_DESIGN_BUTTON+id,
			enableToggle: true,
			toggleGroup: "flow_flow_setting_design_btn",
			renderTo: _this.ID_CNOA_flow_flow_setting_design_ct,
			autoWidth: true,
			handler: function(th){
				th.toggle(true);
			},
			listeners: {
				render : function(btn){
					
				},
				toggle : function(btn, ck){
					if(ck){
						_this.makeViewTreeData(id);
					}
				}
			}
		});
	},
	
	//生成右边预览tree数据(节点属性查看器)
	makeViewTreeData : function(id){
		var _this = this;
		
		var makeNode = function(text, leaf){
			return new Ext.tree.TreeNode({
				iconCls: leaf ? "icon-none" : "icon-fileview-column2",
				text: text,
				expanded: true
			});
		}
		
		var nowNode = _this.getDataById(id);
		
		if((nowNode.operator === null) || (nowNode.operator === undefined)){
			nowNode.operator = {user: [], station:[]}
		}

		//生成节点-步骤名称
		var a1 = makeNode("<b>步骤名称</b>", false);
		var a2 = makeNode("<span style='color:#616161'>"+nowNode.name+"</span>", true);
		a1.appendChild(a2);
		
		//生成节点-上一步骤名称
		var b1 = makeNode("<b>上一步骤名称</b>", false);
		var b2 = makeNode("<span style='color:#616161'>"+_this.getDataById(parseInt(id)-1).name+"</span>", true);
		b1.appendChild(b2);
		
		//生成节点-下一步骤名称
		var c1 = makeNode("<b>下一步骤名称</b>", false);
		var c2 = makeNode("<span style='color:#616161'>"+_this.getDataById(parseInt(id)+1).name+"</span>", true);
		c1.appendChild(c2);
		
		//生成节点-经办角色
		var d1 = makeNode("<b>经办角色</b>", false);
		var d2 = makeNode("<b>人员</b>", false);
		var d3 = makeNode("<b>岗位</b>", false);
		try{
			Ext.each(nowNode.operator.user, function(v, i){
				var d = makeNode("<span style='color:#616161'>"+v.name+"</span>", true);
				d2.appendChild(d);
			});
			if(nowNode.operator.user.length == 0){
				//d2.appendChild(makeNode("无", true));
			}
		}catch(e){
			//d2.appendChild(makeNode("无", true));
		}
		try{
			Ext.each(nowNode.operator.station, function(v, i){
				var d = makeNode("<span style='color:#616161'>"+v.name+"</span>", true);
				d3.appendChild(d);
			});
			if(nowNode.operator.station.length == 0){
				//d3.appendChild(makeNode("无", true));
			}
		}catch(e){
			//d3.appendChild(makeNode("无", true));
		}
		d1.appendChild(d2);
		d1.appendChild(d3);
		/* 如果是第一步骤并且经办角色是任意人时 */
		if(nowNode.id == 0){
			if((nowNode.operator.station.length == 0) && (nowNode.operator.user.length == 0)){
				d1.childNodes = [];
				d1.appendChild(makeNode("<span style='color:#616161'>任意人</span>", true));
			}
		}
		
		//表单字段
		//node.formitems
		var g1 = makeNode("<b>表单字段</b>", false);
		var g2 = makeNode("<b>可填字段</b>", true);
		var g3 = makeNode("<b>必填字段</b>", true);
		Ext.each(nowNode.formitems, function(v, i){
			if(v.need){
				g2.appendChild(makeNode("<span style='color:#616161'>"+v.name+"</span>", true));
			}
			if(v.must){
				g3.appendChild(makeNode("<span style='color:#616161'>"+v.name+"</span>", true));
			}
		});
		g1.appendChild(g2);
		g1.appendChild(g3);
		
		//经办方式operatortype
		var operatorperson = nowNode.operatorperson == "2" ? "<span style='color:#616161'>多人办理</span>" : "<span style='color:#616161'>单人办理</span>";
		var operatortype = nowNode.operatortype == "1" ? "<span style='color:#616161'>任意一人同意</span>" : "<span style='color:#616161'>所有人同意</span>";
		var e1 = makeNode("<b>经办方式</b>", false);
		var e2 = makeNode("<span style='color:#616161'>"+operatorperson+"</span>", true);
		e1.appendChild(e2);
		if(nowNode.operatorperson == "2"){
			var e3 = makeNode("<b>转下一步条件: </b>", false);
			var e4 = makeNode("<span style='color:#616161'>"+operatortype+"</span>", true);
			e3.appendChild(e4);
			e1.appendChild(e3);
		}
		
		//生成节点-允许操作
		var f1 = makeNode("<b>允许操作</b>", false);
		if(parseInt(nowNode.upAttach) == 1){
			var f2 = makeNode("<span style='color:#616161'>允许上传附件</span>", true);
			f1.appendChild(f2);
		}
		if(parseInt(nowNode.downAttach) == 1){
			var f3 = makeNode("<span style='color:#616161'>允许下载附件</span>", true);
			f1.appendChild(f3);
		}
		//if(!f1.hasChildNodes()){
		//	var f4 = makeNode("无", true);
		//	f1.appendChild(f4);
		//}
		
		//添加到树列表
		_this.treeRoot = new Ext.tree.TreeNode({
			expanded: true
		}); 
		if(nowNode.type == "end"){
			_this.treeRoot.appendChild([a1, b1]);
		}else{
			_this.treeRoot.appendChild([a1, b1, c1, d1, g1, e1, f1]);
		}
		_this.treePanel.setRootNode(_this.treeRoot);
	},
	
	//根据ID获取节点数据
	getDataById : function(id){
		var _this = this;
		id = parseInt(id);

		if((id < 0) || (id > _this.data.length-1)){
			return {name: "无", id: "-1"}
		}
		
		var value;
		Ext.each(_this.data, function(v, i, c){
			if(v.id == id){
				value = v;
			}
		});
		return value;
	},
	
	//生成箭头线
	createLine : function(source){
		var _this = this;
		
		new Ext.BoxComponent({
			id: this.ID_DESIGN_LINE+source,
			renderTo: _this.ID_CNOA_flow_flow_setting_design_ct,
			autoEl: {
				tag: 'div',
				html: '<img src="./resources/images/cnoa/arrow.gif" />'
			}
		})
	}
}

var CNOA_flow_flow_user_dealflowClass, CNOA_flow_flow_user_dealflow;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_user_dealflowClass = CNOA.Class.create();
CNOA_flow_flow_user_dealflowClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;
		this.sort = 0;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		
		
		
		//查询
		this.ID_find_name		= Ext.id();
		this.ID_find_title		= Ext.id();
		this.ID_find_beginTime	= Ext.id();
		this.ID_find_endTime	= Ext.id(); 
		this.ID_find_status		= Ext.id();
		this.ID_find_buildUser	= Ext.id();
		this.ID_find_getUser	= Ext.id();
		this.ID_find_flowFrom	= Ext.id();
		
		//查询参数
		this.searchParams = {
			name: '',
			title: '',
			beginTime: '',
			endTime: '',
			status: 0,
			buildUser: '',
			flowFrom: ''
		};



		this.fields = [
			{name:"ulid"},
			{name:"lid"},
			{name:"name"},
			{name:"flowname"},
			{name:"title"},
			{name:"level"},
			{name:"stepText"},
			{name:"uname"},
			{name:"posttime"},
			{name:"status"},
			{name:"allowOperate"},
			{name:"flowtype"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getdealflowJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			//this.sm,
			{header: "ulid", dataIndex: 'ulid', hidden: true},
			{header: '流程编号 / 标题', dataIndex: 'name', width: 240, sortable: true, menuDisabled :true, renderer:this.makeTitle.createDelegate(this)},
			{header: '所属流程', dataIndex: 'flowname', width: 120, sortable: true, menuDisabled :true},
			{header: "重要等级", dataIndex: 'level', width: 60, sortable: false,menuDisabled :true},
			{header: "当前步骤", dataIndex: 'stepText', width: 150, sortable: false,menuDisabled :true},
			{header: "状态", dataIndex: 'status', width: 50, sortable: false,menuDisabled :true, renderer:this.makeStatus.createDelegate(this)},
			{header: "发起人 / 发起日期", dataIndex: 'uname', width: 150, sortable: false,menuDisabled :true, renderer:this.makeUname.createDelegate(this)},
			{header: "查看 / 操作", dataIndex: 'ulid', width: 150, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			emptyMsg:"没有数据显示",
			displayMsg:"显示第{0}--{1}条数据，共{2}条",   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					/*params.sort = _this.sort;
					
					
					//查询参数
					if(_this.searchParams.name != ''){
						params.name = _this.searchParams.name;
					}
					if(_this.searchParams.title != ''){
						params.title = _this.searchParams.title;
					}
					if(_this.searchParams.beginTime != ''){
						params.beginTime = _this.searchParams.beginTime;
					}
					if(_this.searchParams.endTime != ''){
						params.endTime = _this.searchParams.endTime;
					}
					if(_this.searchParams.status != 0){
						params.status = _this.searchParams.status;
					}
					if(_this.searchParams.buildUser != ''){
						params.buildUser = _this.searchParams.buildUser;
					}
					if(_this.searchParams.flowFrom != ''){
						params.flowFrom = _this.searchParams.flowFrom;
					}*/
					Ext.apply(params,_this.searchParams);
					
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				//forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 14}
				]
			}),
			bbar: this.pagingBar
		});
		
		
		
		
		
		this.flowFrom = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl + "&task=getFlowFrom", disableCaching: true}),   
			reader:new Ext.data.JsonReader({
				root:"data", 
				fields: [
					{name: 'sid'}, 
					{name: 'name'}
				]
			}),
			listeners:{
				'load': function(th, record){
					//var wt = Ext.getCmp(_this.ID_worktime);
					//wt.setValue(record[0].data.id);
					//cdump(record);
				}
			},
			autoLoad: true
		});
		
		
		
	//查询工具条
		this.gridCt = new Ext.Panel({
			border: false,
			layout: 'fit',
			items: [this.grid],
			region: 'center',
			listeners: {
				afterrender: function(){
					new Ext.Toolbar({
						style: 'border-left-width:1px;',
						items: [
							'发起开始时间：',
							{
								xtype: "datefield",
								width: 100,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_beginTime
							},
							' 发起最后时间：',
							{
								xtype: "datefield",
								width: 100,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_endTime
							},
							{
								xtype: 'hidden',
								id: _this.ID_find_buildUser
							},
							'发起人',
							{
								xtype: "triggerForPeople",
								dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
								width: 100,
								id: _this.ID_find_getUser,								
								listeners: {
									"selected":function(th, uid, uname){
										Ext.getCmp(_this.ID_find_buildUser).setValue(uid);
									}
								}
							},
							'所属流程分类',
							{
								xtype: 'combo',
								id:_this.ID_find_flowFrom,
								width: 130,		
								name: 'flowFrom',
								valueField: 'sid',
								hiddenName: 'worktimeId',
								displayField: 'name',
								triggerAction: 'all',
								mode: 'local',
								editable: false,
								store: _this.flowFrom,
								//fieldLabel: '所属流程',
								listeners:{
									'select' : function(combo, record, index){
										//cdump(record);
									}
								}
							},
							'-',
							{
								text: '查询',
								cls: 'x-btn-over',
								listeners: {
									'mouseout': function(btn){
										btn.addClass('x-btn-over');
										
									}
								},
								handler: function(){
									_this.searchParams = {
										name: Ext.getCmp(_this.ID_find_name).getValue(),
										title: Ext.getCmp(_this.ID_find_title).getValue(),
										beginTime: Ext.getCmp(_this.ID_find_beginTime).getRawValue(),
										endTime: Ext.getCmp(_this.ID_find_endTime).getRawValue(),
										status: Ext.getCmp(_this.ID_find_status).getValue(),
										buildUser: Ext.getCmp(_this.ID_find_buildUser).getValue(),
										flowFrom: Ext.getCmp(_this.ID_find_flowFrom).getValue()
									};
									_this.store.load({params:_this.searchParams});
								}
							},'-',
							{
								text: '清空',
								handler: function(){
									Ext.getCmp(_this.ID_find_name).setValue('');
									Ext.getCmp(_this.ID_find_title).setValue('');
									Ext.getCmp(_this.ID_find_beginTime).setRawValue('');
									Ext.getCmp(_this.ID_find_endTime).setRawValue('');
									Ext.getCmp(_this.ID_find_status).setValue(-99);
									Ext.getCmp(_this.ID_find_buildUser).setValue('');
									Ext.getCmp(_this.ID_find_getUser).setValue('');
									Ext.getCmp(_this.ID_find_flowFrom).setValue('');
									
									_this.searchParams = {
										name: '',
										title: '',
										beginTime: '',
										endTime: '',
										status: 0,
										buildUser: '',
										flowFrom: ''
									}
									_this.store.load();
								}
							}

						]
					}).render(this.tbar);
				}
			},
			tbar: new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [					
					'标题：',
					{
						xtype: 'textfield',
						width: 200,
						id: _this.ID_find_title
					},
					' 编号名称',
					{
						xtype: 'textfield',
						width: 150,
						id: _this.ID_find_name
					},
					'当前状态：',
					{
						xtype: 'combo',
						id: _this.ID_find_status,
						editable: false,
						mode: 'local',
						triggerAction: 'all',
						valueField: 'value',
						displayField: 'display',
						store: new Ext.data.ArrayStore({
							fields: ['value', 'display'],
							data: [[-99, ''], [-1, '已删除'], [0, '未发布'], [1, '办理中'], [2, '已办理'], [3, '已退件'], [4, '已撤销']]
						}),
						value: -99
					}
				]				
			})		
		});

		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.gridCt]//, this.bottomPanel
		});
	},
	
	makeTitle : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		return value + "<br /><span class='cnoa_color_gray'>标题: " + rd.title + "</span>";
	},
	
	makeUname : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		return value + "<br /><span class='cnoa_color_gray'>" + rd.posttime + "</span>";
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>未发送</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>办理中</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>已办理</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>已退件</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_gray'>已撤销</span>";}
		return h;
	},

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;
		var ro = rd.allowOperate;

		var h  = "";
		
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_dealflow.showFlow("+value+", "+rd.flowtype+");' ext:qtip='查看详细内容'>查看</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_dealflow.viewFlowEvent("+value+", \""+rd.name+"\");' ext:qtip='查看流程事件日志'>事件</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_dealflow.viewFlowProgress("+value+", \""+rd.name+"\");' ext:qtip='查看流程办理进度'>进度</a>";
			h += "&nbsp;&nbsp;";
			h += rd.flowtype == '0' ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_dealflow.viewFlow2("+rd.lid+", \""+rd.flowname+"\");' ext:qtip='查看流程图'>[流程图]</a>" : "";
			h += "<br />";
			
			h += ro == 1 ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_dealflow.viewFlow("+value+", "+rd.flowtype+");' ext:qtip='办理流程'><span class='cnoa_color_red'>办理</span></a>" : "<span class='cnoa_color_gray2'>办理</span>";
			//h += "&nbsp;&nbsp;";
			//h += ro == 1 ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_dealflow.deleteFlow("+value+");' ext:qtip='把流程委托给他人办理'>委托</a>" : "";

		return h;
	},
	
	//查看流程(操作)
	showFlow : function(ulid, flowtype){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_SHOWFLOW");
		if(flowtype == '0'){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=showflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_SHOWFLOW", "查看工作流程", "icon-flow");
		}else if(flowtype == '1'){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&module=commonFlow&task=loadPage&from=showflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_SHOWFLOW", "查看工作流程", "icon-flow");
		}
	},
	
	//查看流程(操作)
	viewFlow : function(ulid, flowtype){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_VIEWFLOW");
		if(flowtype == 0){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=viewflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_VIEWFLOW", "办理工作流程", "icon-flow");
		}else if(flowtype == 1){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&module=commonFlow&task=loadPage&from=viewflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_VIEWFLOW", "办理工作流程", "icon-flow");
		}
	},
	
	//查看流程图
	viewFlow2 : function(lid, name){
		CNOA_flow_flow_flowpreview = new CNOA_flow_flow_flowpreviewClass(lid, name);
		CNOA_flow_flow_flowpreview.show();
	},
	
	viewFlowEvent : function(ulid, title){
		var gridEvent = new CNOA_flow_flow_flowViewEventClass(ulid, false);
		
		var win = new Ext.Window({
			title: "查看流程详细事件 - " + title,
			layout: "fit",
			width: 650,
			height: 300,
			modal: true,
			maximizable: true,
			items: [gridEvent.grid],
			buttons : [
				{
					text : "关闭",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	viewFlowProgress : function(ulid, title){
		var gridEvent = new CNOA_flow_flow_flowViewProgressClass(ulid, false);
		
		var win = new Ext.Window({
			title: "查看流程详细进度 - " + title,
			layout: "fit",
			width: 650,
			height: 300,
			modal: true,
			maximizable: true,
			items: [gridEvent.grid],
			buttons : [
				{
					text : "关闭",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	}
}



var CNOA_flow_flow_user_entrustflowClass, CNOA_flow_flow_user_entrustflow;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_user_entrustflowClass = CNOA.Class.create();
CNOA_flow_flow_user_entrustflowClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;
		this.sort = 0;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		this.fields = [
			{name:"fromuid"},
			{name:"touid"},
			{name:"touname"},
			{name:"stime"},
			{name:"etime"},
			{name:"status"},
			{name:"statusText"},
			{name:"expiresText"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getEntrustJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			//this.sm,
			{header: "fromuid", dataIndex: 'fromuid', hidden: true},
			{header: '被委托人', dataIndex: 'touname', width: 180, sortable: true, menuDisabled :true},
			{header: '开始时间', dataIndex: 'stime', width: 120, sortable: true, menuDisabled :true},
			{header: '结束时间', dataIndex: 'etime', width: 120, sortable: true, menuDisabled :true},
			{header: "状态", dataIndex: 'statusText', width: 60, sortable: false,menuDisabled :true, renderer:this.makeStatus.createDelegate(this)},
			{header: "操作", dataIndex: 'fromuid', width: 100, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 15}
				]
			})
		});
		

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid]//, this.bottomPanel
		});
	},
	
	makeStatus : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;

		var h = "";
			 if(rs == 0){h = "<span class='cnoa_color_red'>"+value+"</span>";}
		else if(rs == 1){h = "<span class='cnoa_color_green'>"+value+"</span>";}
		else if(rs == 2){h = "<span class='cnoa_color_gray'>"+value+"</span>";}
		
		return h + rd.expiresText;
	},

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var _this = this;

		var rd = record.data;
		var h = "";
		var btnId = Ext.id();
		var btn = createGridButton.defer(1, this, [btnId]);

		function createGridButton(){
			return new Ext.Button({
				text: '设置',
				iconCls: 'icon-order-s-con-pour',
				//autoWidth: true,
				width: 55,
				handler: function(){
					_this.showSettingPanel();
				}
			}).render(document.body, btnId);
		}
		
		return "<div id="+btnId+"></div>";
	},

	showSettingPanel : function(){
		var _this = this;
		
		var ID_save = Ext.id();
		
		var submit = function(){
			var f = form.getForm();
			
			if (f.isValid()) {
				f.submit({
					url: _this.baseUrl+"&task=setEntrust",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					//params: {fid : _this.edit_id},
					success: function(form, action) {
						_this.store.reload();
						win.close();
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							
						}.createDelegate(this));
					}.createDelegate(this)
				});
			}else{
				var position = Ext.getCmp(ID_save).getEl().getBox();
					position = [position['x']+35, position['y']+26];
	
				CNOA.miniMsg.alert("部分表单未完成,请检查", position);
			}
		}
		
		var form = new Ext.form.FormPanel({
			border: false,
			labelWidth: 70,
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			defaults: {
				width: 160
			},
			items: [
				{
					xtype: "panel",
					hideBorders: true,
					border: false,
					layout: "table",
					width: 346,
					layoutConfig: {
						columns: 2
					},
					items: [
						{
							xtype: "panel",
							layout: "form",
							width: 240,
							items: [
								{
									xtype: "textfield",
									allowBlank: false,
									name: "toName",
									readOnly: true,
									fieldLabel: '被委托人',
									width: 160
								},
								{
									xtype: "hidden",
									name: "touid"
								}
							]
						},
						{
							xtype: "btnForPoepleSelectorSingle",
							text: '选择',
							dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
							style: "margin-bottom:5px;",
							listeners: {
								"selected" : function(th, data){
									var names = new Array();
									var uids = new Array();
									if (data.length>0){
										for (var i=0;i<data.length;i++){
											names.push(data[i].uname);
											uids.push(data[i].uid);
										}
									}
									form.getForm().findField("toName").setValue(names.join(","));
									form.getForm().findField("touid").setValue(uids.join(","));
								},

								"onrender" : function(th){
									th.setSelectedUids(form.getForm().findField("touid").getValue().split(","));
								}
							}
						}
					]
				},
				{
					name: 'stime',
					fieldLabel: "开始时间",
					xtype:'datetimefield',
					allowBlank: false,
					format:'H:i'
				},
				{
					name: 'etime',
					fieldLabel: "结束时间",
					xtype:'datetimefield',
					allowBlank: false,
					format:'H:i'
				},
				{
					xtype: 'radiogroup',
					fieldLabel: '是否启用',
					//allowBlank: false,
					name: "operatorpersonGroup",
					items: [
						{boxLabel: '启用', name: 'status', inputValue: "1", checked: true},
						{boxLabel: '禁用', name: 'status', inputValue: "0"}
					],
					listeners:{
						"change":function(th, checked){
							
						}.createDelegate(this),

						"render" : function(th){
							
						}
					}
				},
				{
					xtype: "displayfield",
					hideLabel: true,
					width: 280,
					value: "<span class='cnoa_color_gray'>设置工作委托人，则所有需要经由你办事的工作也同样可以由被委托人办理。</span>"
				}
			]
		});
		var win = new Ext.Window({
			title: "设置流程委托人",
			layout: "fit",
			width: 317,
			height: 228,
			modal: true,
			maximizable: true,
			resizable: false,
			items: [form],
			buttons : [
				{
					text: "保存",
					id: ID_save,
					iconCls: 'icon-order-s-accept',
					handler: function(){
						//Ext.getDom(ID_iframe).contentWindow.getContentText();
						submit();
					}
				},
				{
					text : "取消",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
		
		form.getForm().load({
			url: _this.baseUrl+"&task=editEntrustLoadForm",
			params: {fid: _this.edit_id},
			method:'POST',
			waitMsg: CNOA.lang.msgLoadMask,
			success: function(form, action){
				
			}.createDelegate(this),
			failure: function(form, action) {
				_this.close();
				CNOA.msg.alert(action.result.msg, function(){
					
				});
			}.createDelegate(this)
		});
	}
}



var CNOA_flow_flow_user_flowlistClass, CNOA_flow_flow_user_flowlist;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_user_flowlistClass = CNOA.Class.create();
CNOA_flow_flow_user_flowlistClass.prototype = {
	init : function(){
		var _this = this;

		this.edit_id = 0;
		this.sort = 0;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		this.fields = [
			{name:"lid"},
			{name:"name"},
			{name:"sname"},
			{name:"formid"},
			{name:"ftype"}//流程类型：null:正常流程/1:通用流程/2:自由流程
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getFlowJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
			listeners: {
				load : function(store){
					var p = new Ext.data.Record({
						lid:'0',
						name:"通用流程",
						sname:'',
						ftype:'1'
					});
					
					store.insert(0, p);
					
					_this.grid.getView().refresh();
				}
			}
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			//this.sm,
			{header: "lid", dataIndex: 'lid', hidden: true},
			{header: '流程名称', dataIndex: 'name', width: 140, sortable: true, menuDisabled :true, renderer:this.makeName.createDelegate(this)},
			{header: "所属分类", dataIndex: 'sname', width: 70, sortable: false,menuDisabled :true},
			{header: "查看", dataIndex: 'lid', width: 70, sortable: false,menuDisabled :true, renderer:this.makeView.createDelegate(this)},
			{header: "操作", dataIndex: 'lid', width: 70, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			emptyMsg:"没有数据显示",
			displayMsg:"显示第{0}--{1}条数据，共{2}条",   
			store: this.store,
			style: 'border-left-width:1px;',
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					params.sort = _this.sort;
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			bodyStyle: 'border-left-width:1px;',
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 16}
				]
			}),
			bbar: this.pagingBar
		});
		
		
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			text: "所有分类",
			sid: '0',
			iconCls: "icon-tree-root-cnoa",
			id: this.ID_tree_treeRoot,
			expanded: true//,
		});
		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl+"&task=getSortList&type=tree",
			preloadChildren: true,
			clearOnLoad: false,
			listeners:{
				"load" : function(node){
					_this.sortTree.selectPath(_this.ID_tree_treeRoot);
				}.createDelegate(this)
			}
		})
		this.sortTree = new Ext.tree.TreePanel({
			hideBorders: true,
			border: false,
			rootVisible: true,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: this.treeLoader,
			root: this.treeRoot,
			autoScroll: true,
			listeners:{
				"click":function(node){
					_this.sort = node.attributes.sid;
					_this.store.load({params:{sort: _this.sort}});
				}
			}
		});
		this.sortPanel = new Ext.Panel({
			region: 'west',
			layout:'fit',
			split: true,
			width: 140,
			minWidth: 80,
			maxWidth: 380,
			bodyStyle: 'border-right-width:1px;',
			items: [this.sortTree],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					"流程分类",
					"->",
					//刷新tree
					{
						handler : function(button, event) {
							_this.treeRoot.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: CNOA.lang.mainUser.refreshList,
						text : CNOA.lang.mainUser.refreshList
					}
				]
			})
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid, this.sortPanel]//, this.bottomPanel
		});
	},

	makeName : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var h = "";
		if(rd.ftype == '1'){
			h = "<span class='cnoa_color_red' ext:qtip='发起通用流程，由发起人定义流程的步骤及各步骤经办人。'>"+value+"</span>";
		}else{
			h = value;
		}
		
		return h;
	},

	makeView : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		if(rd.ftype == '1'){
			h = "";
		}else{
			var src = "./resources/images/icons/";
			var h  = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_flow_flow_user_flowlist.viewFlow("+value+", \""+rd.name+"\");'><img src='"+src+"icon-flow.png' style='margin:0 2px 4px 0' align='absmiddle' />流程图</a>";
				h += "&nbsp;&nbsp;&nbsp;";
				h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_flowlist.viewForm("+rd.formid+");'><img src='"+src+"application_view_list.png' style='margin:0 2px 4px 0' align='absmiddle' />工作表单</a>";
				h += "</div>";
		}
		
		return h;
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		if(rd.ftype == '1'){
			var h  = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_flow_flow_user_flowlist.newCommonFlow();'><img src='"+src+"page_addedit.png' style='margin:0 2px 4px 0' align='absmiddle' />新建</a>";
				h += "</div>";
		}else{
			var h  = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_flow_flow_user_flowlist.newFlow("+value+");'><img src='"+src+"page_addedit.png' style='margin:0 2px 4px 0' align='absmiddle' />新建</a>";
				h += "</div>";
		}
		
		return h;
	},
	
	//新建流程
	newFlow : function(lid){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_NEWFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=newflow&ac=add&lid="+lid, "CNOA_MENU_FLOW_USER_NEWFLOW", "新建工作流程", "icon-flow-new");
	},
	
	//新建通用流程
	newCommonFlow : function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_NEWCOMMONFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&module=commonFlow&task=loadPage&from=newflow", "CNOA_MENU_FLOW_USER_NEWCOMMONFLOW", "新建工作流程", "icon-flow-new");
	},

	//查看流程图
	viewFlow : function(lid, name){
		CNOA_flow_flow_flowpreview = new CNOA_flow_flow_flowpreviewClass(lid, name);
		CNOA_flow_flow_flowpreview.show();
	},
	
	//下载工作表单
	viewForm : function(fid){
		var _this = this;
		
		var box = Ext.getBody().getBox();
		var w	= box.width - 100;
		var h	= box.height - 100;
		
		var load = function(){
			Ext.Ajax.request({  
				url: _this.baseUrl + "&task=loadFormData",
				method: 'POST',
				params: {fid: fid},
				success: function(r){
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						panel.getEl().update("<center>"+result.data.content+"</center>");
					}else{
						CNOA.msg.alert(result.msg, function(){});
					}
					win.getEl().unmask();
				}
			});
		}
		
		var panel = new Ext.Panel({
			border: false,
			
			html: "工作表单载入中...",
			listeners: {
				afterrender : function(p){
					load();
				}
			}
		});
		
		var win = new Ext.Window({
			title: "查看工作表单",
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
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			],
			listeners: {
				show : function(win){
					win.getEl().mask(CNOA.lang.msgLoadMask);
				}
			}
		}).show();
	},
	
	//下载工作表单
	viewForm2 : function(fid){
		var _this = this;

		var ID_filedownload = Ext.id();

		var loadFormData = function(){
			form.getForm().load({
				url: _this.baseUrl + "&task=loadFormData",
				params: { fid: fid },
				method:'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				success: function(form, action){
					var e = Ext.getCmp(ID_filedownload);
					if(action.result.data.fileInfo == null){
						e.getEl().update("下载工作表单: (该工作表单尚未上传)");
					}else{
						//url/size
						e.getEl().update('下载工作表单: <img src="./resources/images/icons/icon_save.gif"><a href="'+action.result.data.fileInfo.url+'" target="_blank" ext:qtip="请点击右键另存为">下载</a>');
					}
				}.createDelegate(this),
				failure: function(form, action) {
					win.close();
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});
		}

		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender : function(th){
					loadFormData();
				}
			},
			items: [
				{
					xtype: "displayfield",
					name: "name",
					fieldLabel: '表单名称'
				},
				new Ext.BoxComponent({
					id: ID_filedownload,
					autoEl: {
						tag: 'div',
						style: 'padding-left:16px',
						html: ''
					}
				})
			]
		});

		var win = new Ext.Window({
			title: "查看工作表单",
			width: 350,
			height: 200,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	}
}


CNOA_flow_flow_flowpreviewClass = CNOA.Class.create();
CNOA_flow_flow_flowpreviewClass.prototype = {
	init: function(flowId, flowName){
		var _this = this;

		this.ID_window_numberTip = Ext.id();
		this.ID_btn_addedit_save = Ext.id();
		this.ID_CNOA_flow_flow_setting_design_ct = Ext.id();
		
		this.ID_DESIGN_BUTTON = "flow_flow_user_flowpreview_button_";
		this.ID_DESIGN_LINE = "flow_flow_user_flowpreview_line_";
		
		this.flowId = flowId;
		this.flowName = flowName;
		this.data = null;
		this.newStepCount = 1;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.treeRoot = new Ext.tree.TreeNode({
			expanded: true
		}); 
		this.treePanel = new Ext.tree.TreePanel({
			root: this.treeRoot,
			border: false,
			id: "flow_flow_preview",
			//useArrows: true,
			rootVisible: false
		});
		this.rightPanel = new Ext.Panel({
			border: false,
			region: "east",
			width: 180,
			minWidth: 180,
			maxWidth: 180,
			split: true,
			autoScroll:true,
			bodyStyle: "padding-top:4px;",
			items: [this.treePanel]
		});
		
		this.centerPanel = new Ext.Panel({
			border: false,
			region: "center",
			autoScroll: true,
			bodyStyle: "background:#FFF url('./resources/images/mxgraph/grid.gif');",
			html: "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center' valign='top' style='padding-top:20px;'><div id='"+this.ID_CNOA_flow_flow_setting_design_ct+"'></div></td></tr></table>",
			listeners: {
				afterrender: function(th){
					_this.readFlowData();
				}
			}
		});
		
		this.mainPanel = new Ext.Window({
			title: "查看流程 - " + this.flowName,
			width: 700,
			height: 600,
			layout: "border",
			modal: true,
			maximizable: true,
			resizable: true,
			items: [this.centerPanel, this.rightPanel],
			buttons: [
				{
					text: CNOA.lang.btnClose,
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
	
	readFlowData : function(){
		var _this = this;
		
		_this.mainPanel.getEl().mask(CNOA.lang.msgLoadMask);

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=flowpreviewLoadData",
			method: 'POST',
			params: { lid: _this.flowId },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					if(result.data.length < 1){
						_this.data = [
							{
								name: "开始",
								id: "0",
								type: "start"
							}
						];
					}else{
						_this.data = result.data;
					}
				}else{
					CNOA.msg.alert(result.msg);
				}
				
				_this.makeFlowViewport();
				
				_this.mainPanel.getEl().unmask();
			}
		});
	},
	
	//生成流程图
	makeFlowViewport : function(){
		var _this = this;
		
		document.getElementById(_this.ID_CNOA_flow_flow_setting_design_ct).innerHTML = "";
		
		var lastLineId = 0;
		Ext.each(_this.data, function(v, i, c){
			_this.createButton(v.id, v.name, v.type);
			_this.createLine(v.id);
			lastLineId = v.id;
		});
		
		//删除最后一个连线
		try{
			var p = document.getElementById(_this.ID_CNOA_flow_flow_setting_design_ct);
			var s = document.getElementById(_this.ID_DESIGN_LINE+lastLineId);
			p.removeChild(s);
		}catch(e){}
	},
	
	//删除流程节点
	deleteNode : function(nodeId){
		var _this = this;
		
		var tmp = new Array();
		var id = 0;
		
		Ext.each(_this.data, function(v, i, c){
			if(v.id != nodeId){
				v.id = id;
				tmp.push(v);
				id++;
			}
		});
		
		_this.data = tmp;
		
		_this.makeFlowViewport();
	},
	
	//添加流程节点
	addNode : function(preNodeId){
		var _this = this;
		
		var tmp = new Array();
		
		var node = {
			name : "新步骤"+this.newStepCount++,
			type : "node"
		}
		
		var id = 0;
		Ext.each(_this.data, function(v, i, c){
			v.id = id;
			tmp.push(v);
			id++;
			if(v.id == preNodeId){
				node.id = id;
				tmp.push(node);
				id++;
			}
		});
		
		_this.data = tmp;
		
		_this.makeFlowViewport();
	},
	
	//生成流程节点按钮
	createButton : function(id, name, type){
		var _this = this;
			
		new Ext.Button({
			text: name,
			scale: 'large',
			btype: type,
			id: this.ID_DESIGN_BUTTON+id,
			enableToggle: true,
			toggleGroup: "flow_flow_setting_design_btn",
			renderTo: _this.ID_CNOA_flow_flow_setting_design_ct,
			autoWidth: true,
			handler: function(th){
				th.toggle(true);
			},
			listeners: {
				render : function(btn){
					
				},
				toggle : function(btn, ck){
					if(ck){
						_this.makeViewTreeData(id);
					}
				}
			}
		});
	},
	
	//生成右边预览tree数据(节点属性查看器)
	makeViewTreeData : function(id){
		var _this = this;
		
		var makeNode = function(text, leaf){
			return new Ext.tree.TreeNode({
				iconCls: leaf ? "icon-none" : "icon-fileview-column2",
				text: text,
				expanded: true
			});
		}
		
		var nowNode = _this.getDataById(id);
		
		if((nowNode.operator === null) || (nowNode.operator === undefined)){
			nowNode.operator = {user: [], station:[]}
		}

		//生成节点-步骤名称
		var a1 = makeNode("<b>步骤名称</b>", false);
		var a2 = makeNode("<span style='color:#616161'>"+nowNode.name+"</span>", true);
		a1.appendChild(a2);
		
		//生成节点-上一步骤名称
		var b1 = makeNode("<b>上一步骤名称</b>", false);
		var b2 = makeNode("<span style='color:#616161'>"+_this.getDataById(parseInt(id)-1).name+"</span>", true);
		b1.appendChild(b2);
		
		//生成节点-下一步骤名称
		var c1 = makeNode("<b>下一步骤名称</b>", false);
		var c2 = makeNode("<span style='color:#616161'>"+_this.getDataById(parseInt(id)+1).name+"</span>", true);
		c1.appendChild(c2);
		
		//生成节点-经办角色
		var d1 = makeNode("<b>经办角色</b>", false);
		var d2 = makeNode("<b>人员</b>", false);
		var d3 = makeNode("<b>岗位</b>", false);
		try{
			Ext.each(nowNode.operator.user, function(v, i){
				var d = makeNode("<span style='color:#616161'>"+v.name+"</span>", true);
				d2.appendChild(d);
			});
			if(nowNode.operator.user.length == 0){
				//d2.appendChild(makeNode("无", true));
			}
		}catch(e){
			//d2.appendChild(makeNode("无", true));
		}
		try{
			Ext.each(nowNode.operator.station, function(v, i){
				var d = makeNode("<span style='color:#616161'>"+v.name+"</span>", true);
				d3.appendChild(d);
			});
			if(nowNode.operator.station.length == 0){
				//d3.appendChild(makeNode("无", true));
			}
		}catch(e){
			//d3.appendChild(makeNode("无", true));
		}
		d1.appendChild(d2);
		d1.appendChild(d3);
		/* 如果是第一步骤并且经办角色是任意人时 */
		if(nowNode.id == 0){
			if((nowNode.operator.station.length == 0) && (nowNode.operator.user.length == 0)){
				d1.childNodes = [];
				d1.appendChild(makeNode("<span style='color:#616161'>任意人</span>", true));
			}
		}
		
		//表单字段
		//node.formitems
		var g1 = makeNode("<b>表单字段</b>", false);
		var g2 = makeNode("<b>可填字段</b>", true);
		var g3 = makeNode("<b>必填字段</b>", true);
		Ext.each(nowNode.formitems, function(v, i){
			if(v.need){
				g2.appendChild(makeNode("<span style='color:#616161'>"+v.name+"</span>", true));
			}
			if(v.must){
				g3.appendChild(makeNode("<span style='color:#616161'>"+v.name+"</span>", true));
			}
		});
		g1.appendChild(g2);
		g1.appendChild(g3);
		
		//经办方式operatortype
		var operatorperson = nowNode.operatorperson == "2" ? "<span style='color:#616161'>多人办理</span>" : "<span style='color:#616161'>单人办理</span>";
		var operatortype = nowNode.operatortype == "1" ? "<span style='color:#616161'>任意一人同意</span>" : "<span style='color:#616161'>所有人同意</span>";
		var e1 = makeNode("<b>经办方式</b>", false);
		var e2 = makeNode("<span style='color:#616161'>"+operatorperson+"</span>", true);
		e1.appendChild(e2);
		if(nowNode.operatorperson == "2"){
			var e3 = makeNode("<b>转下一步条件: </b>", false);
			var e4 = makeNode("<span style='color:#616161'>"+operatortype+"</span>", true);
			e3.appendChild(e4);
			e1.appendChild(e3);
		}
		
		//生成节点-允许操作
		var f1 = makeNode("<b>允许操作</b>", false);
		if(parseInt(nowNode.upAttach) == 1){
			var f2 = makeNode("<span style='color:#616161'>允许上传附件</span>", true);
			f1.appendChild(f2);
		}
		if(parseInt(nowNode.downAttach) == 1){
			var f3 = makeNode("<span style='color:#616161'>允许下载附件</span>", true);
			f1.appendChild(f3);
		}
		//if(!f1.hasChildNodes()){
		//	var f4 = makeNode("无", true);
		//	f1.appendChild(f4);
		//}
		
		//添加到树列表
		_this.treeRoot = new Ext.tree.TreeNode({
			expanded: true
		}); 
		if(nowNode.type == "end"){
			_this.treeRoot.appendChild([a1, b1]);
		}else{
			_this.treeRoot.appendChild([a1, b1, c1, d1, g1, e1, f1]);
		}
		_this.treePanel.setRootNode(_this.treeRoot);
	},
	
	//根据ID获取节点数据
	getDataById : function(id){
		var _this = this;
		id = parseInt(id);

		if((id < 0) || (id > _this.data.length-1)){
			return {name: "无", id: "-1"}
		}
		
		var value;
		Ext.each(_this.data, function(v, i, c){
			if(v.id == id){
				value = v;
			}
		});
		return value;
	},
	
	//生成箭头线
	createLine : function(source){
		var _this = this;
		
		new Ext.BoxComponent({
			id: this.ID_DESIGN_LINE+source,
			renderTo: _this.ID_CNOA_flow_flow_setting_design_ct,
			autoEl: {
				tag: 'div',
				html: '<img src="./resources/images/cnoa/arrow.gif" />'
			}
		})
	}
}

var CNOA_flow_flow_user_myflowClass, CNOA_flow_flow_user_myflow;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_user_myflowClass = CNOA.Class.create();
CNOA_flow_flow_user_myflowClass.prototype = {
	init : function(){
		var _this = this;
		
		this.edit_id = 0;
		this.sort = 0;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		
		//查询
		this.ID_find_name		= Ext.id();
		this.ID_find_title		= Ext.id();
		this.ID_find_beginTime	= Ext.id();
		this.ID_find_endTime	= Ext.id(); 
		this.ID_find_status		= Ext.id();
		
		//查询参数
		this.searchParams = {
			name: '',
			title: '',
			beginTime: '',
			endTime: '',
			status: 0
		};
		
		this.fields = [
			{name:"lid"},
			{name:"ulid"},
			{name:"name"},
			{name:"flowname"},
			{name:"title"},
			{name:"level"},
			{name:"step"},
			{name:"posttime"},
			{name:"status"},
			{name:"flowtype"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getMyFlowJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			//this.sm,
			{header: "ulid", dataIndex: 'ulid', hidden: true},
			{header: '流程编号', dataIndex: 'name', width: 180, sortable: true, menuDisabled :true},
			{header: '所属流程', dataIndex: 'flowname', width: 120, sortable: true, menuDisabled :true},
			{header: '标题', dataIndex: 'title', width: 120, sortable: true, menuDisabled :true},
			{header: "重要等级", dataIndex: 'level', width: 60, sortable: false,menuDisabled :true},
			{header: "当前步骤", dataIndex: 'step', width: 70, sortable: false,menuDisabled :true},
			{header: "状态", dataIndex: 'status', width: 50, sortable: false,menuDisabled :true, renderer:this.makeStatus.createDelegate(this)},
			{header: "发起日期", dataIndex: 'posttime', width: 110, sortable: false,menuDisabled :true},
			{header: "操作", dataIndex: 'ulid', width: 160, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			emptyMsg:"没有数据显示",
			displayMsg:"显示第{0}--{1}条数据，共{2}条",   
			store: this.store,
			style: 'border-left-width:1px;',
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					params.sort = _this.sort;
					
					//查询参数
					if(_this.searchParams.name != ''){
						params.name = _this.searchParams.name;
					}
					if(_this.searchParams.title != ''){
						params.title = _this.searchParams.title;
					}
					if(_this.searchParams.beginTime != ''){
						params.beginTime = _this.searchParams.beginTime;
					}
					if(_this.searchParams.endTime != ''){
						params.endTime = _this.searchParams.endTime;
					}
					if(_this.searchParams.status != 0){
						params.status = _this.searchParams.status;
					}
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			bodyStyle: 'border-left-width:1px;',
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				//forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},'-',{
						handler : function(button, event) {
							_this.openExportExcelWindow();
						}.createDelegate(this),
						iconCls: 'icon-excel',
						tooltip: '导出流程列表为Excel文件',
						text : '导出'
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 18}
				]
			}),
			bbar: this.pagingBar
		});
		
		
		
		this.treeRoot = new Ext.tree.AsyncTreeNode({
			text: "所有分类",
			sid: '0',
			iconCls: "icon-tree-root-cnoa",
			id: this.ID_tree_treeRoot,
			expanded: true//,
		});
		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl+"&task=getSortList&type=tree",
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
			rootVisible: true,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: this.treeLoader,
			root: this.treeRoot,
			autoScroll: true,
			listeners:{
				"click":function(node){
					_this.sort = node.attributes.sid;
					_this.store.load({params:{sort: _this.sort}});
				}
			}
		});
		this.sortPanel = new Ext.Panel({
			region: 'west',
			layout:'fit',
			split: true,
			width: 120,
			minWidth: 80,
			maxWidth: 380,
			bodyStyle: 'border-right-width:1px;',
			items: [this.sortTree],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					"流程分类",
					"->",
					//刷新tree
					{
						handler : function(button, event) {
							_this.treeRoot.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: CNOA.lang.mainUser.refreshList,
						text : CNOA.lang.mainUser.refreshList
					}
				]
			})
		});
		
	//查询工具条
		this.gridCt = new Ext.Panel({
			border: false,
			layout: 'fit',
			items: [this.grid],
			region: 'center',
			listeners: {
				afterrender: function(){
					new Ext.Toolbar({
						style: 'border-left-width:1px;',
						items: [
							'发起开始时间：',
							{
								xtype: "datefield",
								width: 170,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_beginTime
							},
							' 发起最后时间：',
							{
								xtype: "datefield",
								width: 170,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_endTime
							},
							'-',
							{
								xtype: 'button',
								text: '查询',
								cls: 'x-btn-over',
								listeners: {
									'mouseout': function(btn){
										btn.addClass('x-btn-over');
									}
								},
								handler: function(){
									_this.searchParams = {
										name: Ext.getCmp(_this.ID_find_name).getValue(),
										title: Ext.getCmp(_this.ID_find_title).getValue(),
										beginTime: Ext.getCmp(_this.ID_find_beginTime).getRawValue(),
										endTime: Ext.getCmp(_this.ID_find_endTime).getRawValue(),
										status: Ext.getCmp(_this.ID_find_status).getValue()										
									};
									_this.store.load({params:_this.searchParams});
								}
							},'-',
							{
								xtype: 'button',
								text: '清空',
								handler: function(){
									Ext.getCmp(_this.ID_find_name).setValue('');
									Ext.getCmp(_this.ID_find_title).setValue('');
									Ext.getCmp(_this.ID_find_beginTime).setRawValue('');
									Ext.getCmp(_this.ID_find_endTime).setRawValue('');
									Ext.getCmp(_this.ID_find_status).setValue(-99);
									_this.searchParams = {
										name: '',
										title: '',
										beginTime: 0,
										endTime: 0,
										status: 0
									};
									_this.store.load();
								}
							}
						]
					}).render(this.tbar);
				}
			},
			tbar: new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [					
					'标题：',
					{
						xtype: 'textfield',
						width: 200,
						id: _this.ID_find_title
					},
					' 编号名称',
					{
						xtype: 'textfield',
						width: 150,
						id: _this.ID_find_name
					},
					'当前状态：',
					{
						xtype: 'combo',
						id: _this.ID_find_status,
						editable: false,
						mode: 'local',
						triggerAction: 'all',
						valueField: 'value',
						displayField: 'display',
						store: new Ext.data.ArrayStore({
							fields: ['value', 'display'],
							data: [[-99, ''], [-1, '已删除'], [0, '未发布'], [1, '办理中'], [2, '已办理'], [3, '已退件'], [4, '已撤销']]
						}),
						value: -99
					}
				]				
			})		
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.gridCt, this.sortPanel]
		});
	},
	
	openExportExcelWindow : function(){
		var _this = this;
		
		var submit = function(){
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: _this.baseUrl + "&task=exportExcel",
					waitMsg: CNOA.lang.msgLoadMask,
					params: {},
					method: 'POST',
					success: function(form, action) {
						win.close();
						_this.openDownloadExcelWindow(action.result.msg);
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
						});
					}
				});
			}
		};

		var formPanel = new Ext.form.FormPanel({
			border: false,
			bodyStyle: "padding:10px;",
			waitMsgTarget: true,
			labelWidth: 110,
			items: [
				{
					xtype: "datefield",
					fieldLabel: "流程发起开始时间",
					width: 170,
					format: "Y-m-d",
					editable: false,
					allowBlank: false,
					name: "stime"
				},
				{
					xtype: "datefield",
					fieldLabel: "流程发起最后时间",
					width: 170,
					format: "Y-m-d",
					editable: false,
					allowBlank: false,
					name: "etime"
				}
			]
		});
		var win = new Ext.Window({
			width: 320,
			height: 140,
			title: "导出工作流程",
			resizable: false,
			modal: true,
			layout: "fit",
			items: formPanel,
			buttons : [
				//汇报
				{
					text : "导出",
					iconCls: 'icon-excel',
					handler : function() {
						submit();
					}.createDelegate(this)
				},
				//关闭
				{
					text : "取消",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	openDownloadExcelWindow : function(code){
		//var fname = url.substring(url.lastIndexOf('/') + 1, url.length);
		var win = new Ext.Window({
			width: 320,
			height: 150,
			title: "下载EXCEL表格",
			resizable: false,
			modal: true,
			layout: "fit",
			bodyStyle: "padding:10px;background-color: #FFF;",
			html: "请点击下载：<br/>"+makeDownLoadIcon2(code, "img"),
			buttons : [
				//关闭
				{
					text : "关闭",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>未发送</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>办理中</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>已办理</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>已退件</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_gray'>已撤销</span>";}
		return h;
	},

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;
		
		//-1已删除 0:未发布 1:办理中 2已办理 3已退件 4已撤销
		
		var h  = "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_myflow.showFlow("+value+", "+rd.flowtype+");'>查看</a>";
			h += "&nbsp;&nbsp;";
			//状态为未发布/已退件/已撤销时
			h += (rs == '0' || rs == '3' || rs == '4') ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_myflow.deleteFlow("+value+");'>删除</a>" : "<span class='cnoa_color_gray2'>删除</span>";
			h += "&nbsp;&nbsp;";
			//状态为未发布时
			h += rs == '0' ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_myflow.editPanel("+value+", "+rd.flowtype+");'>编辑</a>" : "<span class='cnoa_color_gray2'>编辑</span>";
			h += "&nbsp;&nbsp;";
			//状态为办理中/已办理时
			h += rs == '1' ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_myflow.recall("+value+");'>召回</a>" : "<span class='cnoa_color_gray2'>召回</span>";
			h += "&nbsp;&nbsp;";
			//状态为办理中/已办理时
			h += rs == '1' ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_myflow.repeal("+value+");'>撤销</a>" : "<span class='cnoa_color_gray2'>撤销</span>";
			
			h += "<br />";
			
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_myflow.viewFlowEvent("+value+", \""+rd.name+"\");' ext:qtip='查看流程事件日志'>事件</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_myflow.viewFlowProgress("+value+", \""+rd.name+"\");' ext:qtip='查看流程办理进度'>进度</a>";
			h += "&nbsp;&nbsp;";
			h += rd.flowtype == '0' ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_myflow.viewFlow2("+rd.lid+", \""+rd.flowname+"\");' ext:qtip='查看流程图'>[流程图]</a>" : "";
		return h;
	},
	
	editPanel : function(ulid, flowtype){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_NEWFLOW");
		if(flowtype == 0){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=newflow&ac=edit&ulid="+ulid, "CNOA_MENU_FLOW_USER_NEWFLOW", "编辑工作流程", "icon-flow-new");
		}else if(flowtype == 1){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&module=commonFlow&task=loadPage&from=newflow&ac=edit&ulid="+ulid, "CNOA_MENU_FLOW_USER_NEWFLOW", "编辑工作流程", "icon-flow-new");
		}
	},
	
	deleteFlow : function(ulid){
		var _this = this;
		
		CNOA.msg.cf("确认删除吗？", function(btn){
			if(btn == "yes"){
				Ext.Ajax.request({
					url: _this.baseUrl + "&task=deleteflow",
					method: 'POST',
					params: { ulid: ulid },
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.alert(result.msg, function(){
								_this.store.reload();
							});
						}else{
							CNOA.msg.alert(result.msg);
						}
					}
				});
			}
		});
	},
	
	//查看流程(操作)
	showFlow : function(ulid, flowtype){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_SHOWFLOW");
		if(flowtype == 0){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=showflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_SHOWFLOW", "查看工作流程", "icon-flow");
		}else if(flowtype == 1){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&module=commonFlow&task=loadPage&from=showflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_SHOWFLOW", "查看工作流程", "icon-flow");
		}
	},
	
	//查看流程图
	viewFlow2 : function(lid, name){
		CNOA_flow_flow_flowpreview = new CNOA_flow_flow_flowpreviewClass(lid, name);
		CNOA_flow_flow_flowpreview.show();
	},
	
	viewFlowEvent : function(ulid, title){
		var gridEvent = new CNOA_flow_flow_flowViewEventClass(ulid, false);
		
		var win = new Ext.Window({
			title: "查看流程详细事件 - " + title,
			layout: "fit",
			width: 650,
			height: 300,
			modal: true,
			maximizable: true,
			items: [gridEvent.grid],
			buttons : [
				{
					text : "关闭",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	viewFlowProgress : function(ulid, title){
		var gridEvent = new CNOA_flow_flow_flowViewProgressClass(ulid, false);
		
		var win = new Ext.Window({
			title: "查看流程详细进度 - " + title,
			layout: "fit",
			width: 650,
			height: 300,
			modal: true,
			maximizable: true,
			items: [gridEvent.grid],
			buttons : [
				{
					text : "关闭",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	//召回流程
	recall : function(ulid){
		var _this = this;
		
		CNOA.msg.cf("你确定要召回本流程吗？", function(btn){
			if(btn == 'yes'){
				Ext.Ajax.request({  
					url: _this.baseUrl + "&task=recall",
					method: 'POST',
					params: {ulid: ulid},
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.alert(result.msg, function(){
								_this.store.reload();
							});
						}else{
							CNOA.msg.alert(result.msg, function(){});
						}
					}
				});
			}
		});
	},
	
	//撤销流程
	repeal : function(ulid){
		var _this = this;
		
		CNOA.msg.cf("你确定要撤销本流程吗？", function(btn){
			if(btn == 'yes'){
				Ext.Ajax.request({  
					url: _this.baseUrl + "&task=repeal",
					method: 'POST',
					params: {ulid: ulid},
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.alert(result.msg, function(){
								_this.store.reload();
							});
						}else{
							CNOA.msg.alert(result.msg, function(){});
						}
					}
				});
			}
		});
	}
};


var CNOA_flow_flow_user_newflowClass, CNOA_flow_flow_user_newflow;
var CNOA_flow_flow_user_newflow_goNextStepClass, CNOA_flow_flow_user_newflow_goNextStep;
var CNOA_flow_flow_user_newflow_autoSaveTimer;

CNOA_flow_flow_user_newflow_goNextStepClass = CNOA.Class.create();
CNOA_flow_flow_user_newflow_goNextStepClass.prototype = {
	/**
	 * 获取当前步骤的下一步信息
	 * @param {Object} flowid 流程ID
	 * @param {Object} stepid 步骤ID
	 */
	init: function(flowid, stepid){
		var _this = this;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.flowid = flowid;
		this.stepid = stepid;
		this.steptp = "node";
		this.operatorperson = null;
		
		this.ID_html = Ext.id();
		this.ID_multiselects = Ext.id();
		this.ID_multiselects_tip = Ext.id();
		this.ID_window = Ext.id();
		this.ID_nextStepTr1 = Ext.id();
		this.ID_nextStepTr2 = Ext.id();
		
		this.Tpl = new Ext.Template('<table width="596" border="0" cellspacing="1" cellpadding="0" style="background:#999;margin-bottom:5px;"><tr><td width="147" height="24" align="right" valign="middle" bgcolor="#D9D9D9">下一步骤名称:&nbsp;</td><td width="446" valign="middle" bgcolor="#F2F2F2">&nbsp;{name}</td></tr><tr id="'+this.ID_nextStepTr1+'"><td height="24" align="right" valign="middle" bgcolor="#D9D9D9">经办方式:&nbsp;</td><td valign="middle" bgcolor="#F2F2F2">&nbsp;{operatorperson}</td></tr><tr id="'+this.ID_nextStepTr2+'"><td height="24" align="right" valign="middle" bgcolor="#D9D9D9">转下下一步条件:&nbsp;</td><td valign="middle" bgcolor="#F2F2F2">&nbsp;{operatortype}</td></tr></table>');
		
		this.dataStoreFrom = new Ext.data.ArrayStore({data: [],fields: ['uid', 'name']});
		this.dataStoreTo = new Ext.data.ArrayStore({data: [],fields: ['uid', 'name']});
		
		//添加短信组件
		this.smsSender = new Ext.form.SMSSender({
			from: "flow",
			hideLabel: true,
			modal: true,
			//fwindow: this.ID_window,
			boxLabel: "手机短信提醒"
		});
		
		this.formPanel = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 100,
			labelAlign: 'right',
			bodyStyle: "padding:10px;",
			waitMsgTarget: true,
			items: [
				{
					xtype: "panel",
					border: false,
					id: this.ID_html,
					html: ''
				},
				_this.smsSender,
				{
					xtype: 'itemselector',
					name: 'itemselector',
					hideLabel: true,
					id: this.ID_multiselects,
					imagePath: CNOA_EXTJS_PATH + '/ux/images/',
					multiselects: [
						{
							width: 288,
							height: 200,
							store: this.dataStoreFrom,
							displayField: 'name',
							valueField: 'uid',
							tbar: ['待选人员',{text: '&nbsp;',disabled: true}]
						},
						{
							width: 288,
							height: 200,
							store: this.dataStoreTo,
							displayField: 'name',
							valueField: 'uid',
							tbar: [
								'经办人',"->",
								{
									text: '清除',
									iconCls: "icon-clear",
									handler: function() {
										_this.formPanel.getForm().findField('itemselector').reset();
									}
								}
							]
						}
					]
				},
				{
					xtype: "displayfield",
					hideLabel: true,
					id: this.ID_multiselects_tip,
					value: "注意: 你所选择的经办人如果有多人，则列表第一人将作为主办人，只有主办人有权限决定他的下一步骤经办人。"
				}
			]
		});
		
		this.mainPanel = new Ext.Window({
			id: this.ID_window,
			title: "转下一步 - 选择经办人",
			width: 1000,
			height: 430,
			layout: "fit",
			//layout: "border",
			modal: true,
			maximizable: false,
			resizable: false,
			//items: [this.centerPanel, this.rightPanel],
			items: [this.formPanel],
			buttons: [
				{
					text: "转下一步",
					iconCls: 'icon-fileview-column3',
					handler: function(btn){
						var v = _this.formPanel.getForm().findField('itemselector').getValue();
						
						if(Ext.isEmpty(v) && (_this.steptp!="end")){
							CNOA.miniMsg.alertShowAt(btn, "请选择至少一人作为经办人");
						}else{
							var vv = v.split(",");
							if((_this.operatorperson == '1') && (vv.length != 1)){
								CNOA.miniMsg.alertShowAt(btn, "该步骤经办方式为:单人办理, 只能选择一人作为经办人");
							}else{
								if(_this.smsSender.validateValue()){
									CNOA_flow_flow_user_newflow.sendFormStep2(v, function(){
										//提交手机短信
										try{
											_this.smsSender.submit();
										}catch(e){}
										
										_this.close();
									});
								}
							}
						}
					}
				},
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						_this.close();
					}
				}
			],
			listeners: {
				close : function(){
					
				}
			}
		});
	},
	
	show: function(){
		this.mainPanel.show();
		
		this.loadNextStepInfo();
	},
	
	close: function(){
		this.mainPanel.close();
	},
	
	loadNextStepInfo: function(){
		var _this = this;
		
		_this.mainPanel.getEl().mask(CNOA.lang.msgLoadMask);

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=loadNextStepInfo",
			method: 'POST',
			params: {flowid: _this.flowid, stepid: _this.stepid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					var rd = result.data;
					var rdNext = rd.nextData;

					//单人办理/多人办理 变量
					_this.operatorperson = result.data.operatorperson;
					
					//处理html模板
					result.data.operatorperson = result.data.operatorperson == '1' ? "单人办理" : "多人办理";
					result.data.operatortype = result.data.operatortype == '1' ? "任意一人同意" : "所有人同意";
					_this.Tpl.append(_this.ID_html, result.data);
					
					//处理待选列表
					if(rd.nextStepType == "end"){
						_this.steptp = "end";

						Ext.fly(_this.ID_nextStepTr1).dom.style.display='none';
						Ext.fly(_this.ID_nextStepTr2).dom.style.display='none';
						Ext.getCmp(_this.ID_multiselects).hide();
						Ext.getCmp(_this.ID_multiselects_tip).hide();
						
						_this.mainPanel.setHeight(306);
					}else{
						_this.dataStoreFrom.removeAll();
						for(var i=0;i<result.data.operator.length;i++){
							var records = new Ext.data.Record(result.data.operator[i]);
							_this.dataStoreFrom.add(records);
						}
					}
					
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
				_this.mainPanel.getEl().unmask();
			}
		});
	}
}

/**
* 主面板-列表
*
*/
CNOA_flow_flow_user_newflowClass = CNOA.Class.create();
CNOA_flow_flow_user_newflowClass.prototype = {
	init: function(){
		var _this = this;
		
		this.lid		= CNOA.flow.flow.user_newflow.lid;
		this.fid		= CNOA.flow.flow.user_newflow.fid;
		this.ulid		= CNOA.flow.flow.user_newflow.ulid;
		this.flowName	= CNOA.flow.flow.user_newflow.flowName;
		this.flowNumber	= CNOA.flow.flow.user_newflow.flowNumber;
		this.action		= CNOA.flow.flow.user_newflow.ac;
		this.allowup	= CNOA.flow.flow.user_newflow.allowup;
		
		//以下为编辑时使用
		this.title		= CNOA.flow.flow.user_newflow.title;
		this.level		= CNOA.flow.flow.user_newflow.level;
		this.reason		= CNOA.flow.flow.user_newflow.reason;
		this.about		= CNOA.flow.flow.user_newflow.about;
		this.formData	= CNOA.flow.flow.user_newflow.formData;
				
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_save = Ext.id();
		
		this.baseField = [
			{
				xtype: "fieldset",
				title: "流程信息",
				//width: 730,
				autoHeight: true,
				defaults: {
					width: 90,
					xtype: "textfield"
				},
				items: [
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 2
						},
						items: [{
							xtype: "panel",
							layout: "form",
							width: 350,
							items: [
								{
									xtype: "textfield",
									readOnly: true,
									fieldLabel: '流程名称',
									value: this.flowName,
									width: 255
								}
							]
						}, {
							xtype: "panel",
							layout: "form",
							width: 350,
							items: [
								{
									xtype: "cnoa_textfield",
									readOnly: true,
									fieldLabel: '流程编号',
									width: 270,
									value: this.flowNumber,
									tooltip: this.makeNumberTip()
								}
							]
						}]
					},
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 2
						},
						items: [
							{
								xtype: "panel",
								layout: "form",
								width: 350,
								items: [
									{
										xtype: "textfield",
										fieldLabel: '自定义标题',
										name: "title",
										width: 255,
										value: this.action == "edit" ? this.title : ""
									}
								]
							},
							{
								xtype: "panel",
								layout: "form",
								width: 350,
								items: [
									new Ext.form.ComboBox({
										fieldLabel: "重要程度",
										name: 'level',
										width: 270,
										store: new Ext.data.SimpleStore({
											fields: ['value', 'level'],
											data: [['1', "普通"], ['2', "重要"], ['3', "非常重要"]]
										}),
										hiddenName: 'level',
										valueField: 'value',
										displayField: 'level',
										mode: 'local',
										triggerAction: 'all',
										forceSelection: true,
										editable: false,
										value: this.action == "edit" ? this.level : 1
									})
								]
							}
						]
					},
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 2
						},
						items: [
							{
								xtype: "panel",
								layout: "form",
								width: 350,
								items: [
									{
										xtype: "textarea",
										fieldLabel: '发起理由',
										//allowBlank: false,
										name: "reason",
										width: 255,
										height: 120,
										value: this.action == "edit" ? this.reason : ""
									}
								]
							},
							{
								xtype: "panel",
								layout: "form",
								width: 350,
								items: [
									{
										xtype: "textarea",
										fieldLabel: '特殊备注',
										name: "about",
										width: 270,
										height: 120,
										value: this.action == "edit" ? this.about : ""
									}
								]
							}
						]
					},
					{
						xtype: "flashfile",
						fieldLabel: "附件",
						name: "attach",
						width: 620,
						style: "margin-top:5px;",
						inputPre: "filesUpload",
						hideLabel: this.allowup == '0' ? true : false,
						hidden: this.allowup == '0' ? true : false,
						listeners: {
							render : function(th){
								th.setValue(CNOA.flow.flow.user_newflow.attach);
							}
						}
					}
				]
			}
			/*
			,{
				xtype: "fieldset",
				title: "工作表单",
				width: 730,
				autoHeight: true,
				defaults: {
					width: 325,
					xtype: "textfield"
				},
				items: [
					{
						xtype: 'fileuploadfield',
						name: 'fileup',
						fieldLabel: "上传表单",
						allowBlank: false,
						emptyText: "请选择要上传的工作表单文件",
						buttonCfg: {
							text: "浏览"
							//iconCls: 'upload-icon'
						},
						width: 620,
						listeners: {
							'fileselected': function(fb, v){
							
							}
						}
					}
				]
			}
			*/
		];
		
		this.flowForm = new Ext.Panel({
			title: "工作表单",
			border: true,
			frame: true,
			autoScroll: true,
			bodyStyle: "background-color:#FFF",
			//tbar: new Ext.Toolbar({
			///	items: ["工作表单"]
			//}),
			html: "工作表单载入中...",
			listeners: {
				afterrender : function(p){
					_this.loadFlowForm(p);
				}
			}
		});
		
		this.formPanel = new Ext.form.FormPanel({
			fileUpload: true,
			autoWidth: true,
			border: false,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender: function(th){
				
				}
			},
			items: [this.baseField, this.flowForm]
		});
		
		this.centerPanel = new Ext.Panel({
			hideBorders: true,
			border: false,
			region: "center",
			autoScroll: true,
			items: [this.formPanel],
			tbar: new Ext.Toolbar({
				items: [{
					text: "转下一步",
					id: this.ID_btn_send,
					iconCls: 'icon-fileview-column3',
					handler: function(btn){
						//检查表单
						var f = _this.formPanel.getForm();
						if(!f.isValid() || !_this.checkForm()){
							CNOA.miniMsg.alertShowAt(btn, "部分表单未完成,请检查(请确认工作表单的必填字段已经填写完毕)", 30);
							return false;
						}
						
						_this.sendFormStep1();
					}
				}, '-', {
					text: "暂存",
					id: this.ID_btn_save,
					iconCls: 'icon-btn-save',
					handler: function(){
						_this.saveForm();
					}
				}, '-', {
					text: "取消",
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						_this.closeTab();
					}
				}, '-', '-', {
					text: "流程图",
					iconCls: 'icon-flow',
					handler: function(){
						_this.viewFlow(_this.lid, _this.flowName);
					}
				}, "->", {xtype: 'cnoa_helpBtn', helpid: 69}]
			})
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'border',
			autoScroll: false,
			items: [this.centerPanel]
		});

		Ext.getCmp(CNOA.flow.flow.user_newflow.parentID).on("deactivate", function(){
			clearInterval(CNOA_flow_flow_user_newflow_autoSaveTimer);
		});
		Ext.getCmp(CNOA.flow.flow.user_newflow.parentID).on("destroy", function(){
			clearInterval(CNOA_flow_flow_user_newflow_autoSaveTimer);
		});
		
		setTimeout(function(){
			_this.getAutoSaveFormData()
		}, 500);
	},
	
	setAutoSaveFormData : function(){
		var _this = this;
		
		CNOA_flow_flow_user_newflow_autoSaveTimer = setInterval(function(){
			Ext.Ajax.request({
				url: _this.baseUrl + "&task=setAutoSaveFormData",
				method: 'POST',
				params: {lid: _this.lid,data: Ext.encode(_this.formPanel.getForm().getValues())},
				success: function(r){
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						
					}else{
						CNOA.msg.alert(result.msg, function(){});
					}
				}
			});
		},5000);
		
	},
	
	
	getAutoSaveFormData : function(){
		var _this = this;
		Ext.Ajax.request({
			url: _this.baseUrl + "&task=getAutoSaveFormData",
			method: 'POST',
			params: {lid: _this.lid},
			success: function(r){
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					var autoSaveValues = result.data;
					//if(!Ext.isEmpty(data)){
						autoSaveValues = Ext.decode(autoSaveValues);
					//}
					
					//var autoSaveValues = _this.getCookie("CNOA_FLOW_NEW_AUTOSVE_"+_this.lid);
					if(Ext.isEmpty(autoSaveValues) !== true){
						CNOA.msg.cf("检测到未完成的表单数据是否载入<br>按<b>是</b>载入，按<b>否</b>取消<br>",function(btn){
							if(btn == 'yes'){
								//autoSaveValues = Ext.decode(autoSaveValues);
								_this.formPanel.getForm().setValues(autoSaveValues);
								
								for(var i in autoSaveValues){
									var id = i.replace("FLOWDATA[", "").replace("]", "");
									
									var dd = _this.formPanel.getEl().query("input[flowitemid="+id+"],textarea[flowitemid="+id+"],select[flowitemid="+id+"]");
									//dump(dd[0]);
									try{
										dd[0].value = autoSaveValues[i];
									}catch(e){}
								}
							}
							//_this.clearFormCookie();
							//_this.setFormCookie();
						});
					}else{
						//_this.setFormCookie();
					}
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
				_this.setAutoSaveFormData();
			}
		});
	},
	
	loadFlowForm : function(panel){
		var _this = this;
		
		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=loadFormData2",
			method: 'POST',
			params: {fid: _this.fid, lid: _this.lid},
			success: function(r){
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					var toolTip = "<div style='padding-bottom:5px;width:100%' class='x-border-layout-ct'><div class='flow_flow_form'><div>图示：</div><div class='flow_form_must_struct flow_form_must_color'>&nbsp;</div><div>必填字段</div><div class='flow_form_must_struct flow_form_need_color'>&nbsp;</div><div>可填字段</div><div class='flow_form_must_struct flow_form_disabled_color'>&nbsp;</div><div>不可填字段</div></div><div class='clear'></div></div>";
					panel.body.update(toolTip + result.data.content);
					
					var elements = _this.flowForm.getEl().query("input[name^=FLOWDATA],textarea[name^=FLOWDATA],select[name^=FLOWDATA]");

					Ext.each(elements, function(v, i){
						if(_this.action == "edit"){
							Ext.each(_this.formData, function(vv, ii){
								if(("FLOWDATA["+vv.itemid+"]") == v.name){
									v.value = vv.data;
								}
							});
						}
						
						if(v.readOnly == true){
							//v.style.borderWidth = '0';
							v.style.border = '1px solid #E1E1E1';
							v.style.backgroundColor = '#F6F6F6';
						}else{
							v.style.border = '1px solid #BCBCBC';
						}
						v.title = "";
					});
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},
	
	//查看流程图
	viewFlow : function(lid, name){
		CNOA_flow_flow_flowpreview = new CNOA_flow_flow_flowpreviewClass(lid, name);
		CNOA_flow_flow_flowpreview.show();
	},
	
	closeTab : function(){
		var pid = CNOA.flow.flow.user_newflow.parentID.replace("docs-", "");
		mainPanel.closeTab(pid);
	},
	
	goToMyFlow : function(){
		//mainPanel.closeTab("CNOA_MENU_FLOW_USER_MYFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=myflow", "CNOA_MENU_FLOW_USER_MYFLOW", "我的流程", "icon-flow-my");
	},
	
	saveForm: function(){
		var _this = this;
		
		var f = _this.formPanel.getForm();
		
		var reason	= f.findField("reason");
		//var fileup	= f.findField("fileup");
		reason.allowBlank = true;	reason.validationEvent = false;	reason.clearInvalid();
		//fileup.allowBlank = true;	fileup.validationEvent = false;	fileup.clearInvalid();
		
		if(f.isValid() && this.checkForm()){
			
			f.submit({
				url: _this.baseUrl+"&task=saveFlow_"+_this.action,
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				method: 'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				params: {lid : _this.lid, ulid: _this.ulid},
				success: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						_this.goToMyFlow();
						_this.closeTab();
						
						try{
							CNOA_flow_flow_user_myflow.store.reload();
						}catch(e){}
					}.createDelegate(this));
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){});
				}.createDelegate(this)
			});
		}else{
			CNOA.miniMsg.alertShowAt(_this.ID_btn_save, "部分表单未完成,请检查(请确认工作表单的必填字段已经填写完毕)", 30);
		}
	},
	
	checkForm: function(){
		var e1 = this.flowForm.getEl().query("input[name^=FLOWDATA][class*=flow_form_must]");
		var e2 = this.flowForm.getEl().query("textarea[name^=FLOWDATA][class*=flow_form_must]");
		var e3 = this.flowForm.getEl().query("select[name^=FLOWDATA][class*=flow_form_must]");		
		
		var t = true;
		Ext.each(e1, function(v, i){
			if(Ext.isEmpty(v.value)){
				t = false;
			}
		});
		Ext.each(e2, function(v, i){
			if(Ext.isEmpty(v.value)){
				t = false;
			}
		});
		Ext.each(e3, function(v, i){
			if(Ext.isEmpty(v.value)){
				t = false;
			}
		});	

		return t;
	},
	
	//获取下一步要发送的相关人员数据
	sendFormStep1: function(){
		var _this = this;
		
		//选择下一步经办人
		CNOA_flow_flow_user_newflow_goNextStep = new CNOA_flow_flow_user_newflow_goNextStepClass(_this.lid);
		CNOA_flow_flow_user_newflow_goNextStep.show();
	},
	
	//发送数据
	sendFormStep2: function(uids, callback){
		var _this = this;
		
		if(Ext.isArray(uids)){
			uids = uids.join(",");
		}
		
		var f = _this.formPanel.getForm();
		if(f.isValid() && this.checkForm()){
			f.submit({
				url: _this.baseUrl+"&task=sendFlow",
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				method: 'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				params: {lid : _this.lid, ulid: _this.ulid, nextUids: uids},
				success: function(form, action) {
					try {
						callback.call(this);
					} catch (e) {}
							
					CNOA.msg.alert(action.result.msg, function(){
						_this.goToMyFlow();
						_this.closeTab();
						
						try{
							CNOA_flow_flow_user_myflow.store.reload();
						}catch(e){}
					}.createDelegate(this));
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){});
				}.createDelegate(this)
			});
		}else{
			CNOA.miniMsg.alertShowAt(_this.ID_btn_save, "部分表单未完成,请检查(请确认工作表单的必填字段已经填写完毕)", 30);
		}
	},
	
	makeNumberTip : function(){
		var title = "编号规则";
		var html  = "{F}: 此变量表示当前的流程名称。<br>{U}: 此变量表示当前的流程发起者。<br>{Y}: 此变量表示当前时间的四位年份值，如2008。<br>{M}: 此变量表示当前时间的两位月份值，如12。<br>{D}: 此变量表示当前时间的两位日期值，如31。<br>{H}: 此变量表示当前时间的两位小时值，如08。<br>{I}: 此变量表示当前时间的两位分钟值，如59。<br>{S}: 此变量表示当前时间的两位秒钟值，如59。<br>{N}: 此变量表示当天的系统流水号，{N}表示一位流水号，{NNNNN}表示五位流水号，最多五位，如00001。<br><br><b>示例</b>: 如：{F}{Y}{M}{D}-{NNNN}，将会被转化为：流程名称20080808-0008。";
		
		return {text: html, title: title};
	}
}




/**
* 主面板-列表
*
*/
CNOA_flow_flow_user_showflowClass = CNOA.Class.create();
CNOA_flow_flow_user_showflowClass.prototype = {
	init : function(){
		var _this = this;

		this.ulid = CNOA.flow.flow.user_showflow.ulid;
		this.ID_goto_nextstep = Ext.id();
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.flowPanelLoaded = this.formPanelLoaded = this.progressPanelLoaded = this.eventPanelLoaded = false;

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		this.ID_printArea = Ext.id();
		
		this.flowInfo = null;

		this.topTpl = new Ext.XTemplate(
			'<table width="100%" border="0" cellspacing="1" cellpadding="0" style="background-color:#CDCDCD">',
			'  <tr>',
			'    <td width="15%" height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程编号:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{name}</td>',
			'    <td width="15%" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程名称:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;[{flowname}]{title}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起人:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{uname}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起日期:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{posttime}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">当前步骤:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{stepText}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程状态:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{statusText}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">申请事由:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{reason}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">特殊备注:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{about}</td>',
			'  </tr>',
			'  <tpl if="attachCount &gt; 0">',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程附件:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;',
			'		<tpl for="attach">',
			'		<a href="#" id="user_flow_atach_{id}" class="attach_down"><img src="'+Ext.BLANK_IMAGE_URL+'" onload="makeFlowAttacBtn(\'user_flow_atach_{id}\', \'{url}\', \'{name}\', \'{size}\');"></a>',
			//'			<div> </div>',
			//'			<div id="user_flow_atach_{id}"></div>',
			//'			<div style="text-decoration:underline;"><!-- <a href="{url}" target="_blank">下载</a> --><img src="'+Ext.BLANK_IMAGE_URL+'" onload="makeDownLoadIcon(\'user_flow_atach_{id}\', \'{url}\', \'{name}\');" /></div>',
			//'		',
			'		</tpl>',
			'    </td>',
			'  </tr>',
			'  </tpl>',
			'</table>'
		);
		
		/*
		 * 事件信息
		 */
		this.flowPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			style: "margin-bottom:10px;",
			title: "流程信息",
			listeners : {
				afterrender : function(p){
					_this.flowPanelLoaded = true;
					_this.view_loadFlowInfo();
				}
			}
		});
		
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
		
		/*
		 * 事件进度
		 */
		this.gridProgress = new CNOA_flow_flow_flowViewProgressClass(this.ulid);
		this.progressPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "流程进度",
			layout: "fit",
			items: [this.gridProgress.grid],
			listeners : {
				afterrender : function(p){
					var data = {};
					//_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		/*
		 * 事件日志
		 */
		this.gridEvent = new CNOA_flow_flow_flowViewEventClass(this.ulid);
		this.eventPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			layout: "fit",
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "事件日志",
			items: [this.gridEvent.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					///_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		/*
		 * 评阅记录
		 */
		this.gridReader = new CNOA_flow_flow_flowViewReaderClass(this.ulid);
		this.readerPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			layout: "fit",
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "评阅记录",
			items: [this.gridReader.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					///_this.topTpl.overwrite(p.body, data);
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
					items: [this.flowPanel, this.formPanel, this.progressPanel, this.eventPanel, this.readerPanel]
				}
			]
		});
		
		this.exportArray = {i:1,f:1,p:1,e:1,r:1};
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel],
			tbar : new Ext.Toolbar({
				items: [
					'显示：',
					{
						xtype: 'checkbox',
						boxLabel: '流程信息',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.i = 1;
									_this.flowPanel.show();
								}else{
									_this.exportArray.i = 0;
									_this.flowPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '工作表单',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.f = 1;
									_this.formPanel.show();
								}else{
									_this.exportArray.f = 0;
									_this.formPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '流程进度',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.p = 1;
									_this.progressPanel.show();
								}else{
									_this.exportArray.p = 0;
									_this.progressPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '事件日志',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.e = 1;
									_this.eventPanel.show();
								}else{
									_this.exportArray.e = 0;
									_this.eventPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '评阅记录',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.r = 1;
									_this.readerPanel.show();
								}else{
									_this.exportArray.r = 0;
									_this.readerPanel.hide();
								}
							}
						}
					},'-',
					{
						text: '导出',
						iconCls: 'icon-download',
						tooltip: "导出为HTML文件",
						menu : {
							items: [
								{
									iconCls:'document-html',
									text:'导出为网页文件(html)',
									handler: function(){
										_this.exportFlow('html');
									}.createDelegate(this)
								},
								{
									iconCls:'document-word',
									text:'导出为Word文档',
									handler: function(){
										_this.exportFlow('word');
									}.createDelegate(this)
								}
							]
						}
					},'-',
					{
						handler : function(button, event) {
							_this.dispenseFlow();
							//printArea(_this.ID_printArea, "流程打印");
						}.createDelegate(this),
						iconCls: 'icon-flow-entrust',
						tooltip: "分发",
						text : "分发"
					},'-',
					{
						//id: this.ID_btn_refreshList,
						handler : function(button, event) {
							_this.gridReader.showSay();
						}.createDelegate(this),
						hidden: Ext.isAir ? true : false,
						iconCls: 'icon-view-form-png',
						text : "我要评阅"
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
					},'-',{
						iconCls: 'icon-dialog-cancel',
						text : "关闭",
						handler : function(button, event) {
							_this.closeTab();
						}.createDelegate(this)
					},
					"->",
					{xtype: 'cnoa_helpBtn', helpid: 19}
				]
			})
		});
	},
	
	view_loadFlowInfo : function(){
		var _this = this;
		
		if(!this.flowPanelLoaded || !this.formPanelLoaded){
			return false;
		}

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=show_loadFlowInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					//基本信息
					_this.topTpl.overwrite(_this.flowPanel.body, result.data.flowInfo);
					
					_this.flowInfo = result.data.flowInfo;
					
					//工作表单
					_this.formPanel.body.update(result.data.formInfo);
					
					/*
					//工作表单数据
					var elements = _this.formPanel.getEl().query("input[name^=DATA_],textarea[name^=DATA_],select[name^=DATA_]");
					Ext.each(elements, function(v, i){
						Ext.each(result.data.formData, function(vv, ii){
							if(("DATA_"+vv.itemid) == v.name){
								//v.value = vv.data;
							}
						});
						
						v.readOnly = true;
						v.style.border = '1px solid #E1E1E1';
						v.style.backgroundColor = '#F6F6F6';
						
						v.title = "";
					});
					*/
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},

	closeTab : function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_SHOWFLOW");
	},
	
	exportFlow : function(target){
		var _this = this;
		var ID_filedownload = Ext.id();
		
		var exportArray = Ext.encode(this.exportArray);
		var loadFormData = function(){
			form.getForm().submit({
				url: _this.baseUrl + "&task=exportFlow&target="+target,
				params: { ulid: _this.ulid, exportArray: exportArray },
				method:'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				success: function(form, action){
					var e = Ext.getCmp(ID_filedownload);
					e.getEl().update("请点击下载：<br/>"+makeDownLoadIcon2(action.result.msg, "img"));
				}.createDelegate(this),
				failure: function(form, action) {
					win.close();
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});
		}

		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender : function(th){
					loadFormData();
				}
			},
			items: [
				{
					xtype: "displayfield",
					name: "name",
					fieldLabel: '流程名称',
					value: _this.flowInfo.name
				},
				new Ext.BoxComponent({
					id: ID_filedownload,
					autoEl: {
						tag: 'div',
						style: 'padding-left:16px',
						html: ''
					}
				})
			]
		});
		var win = new Ext.Window({
			title: "导出工作流程",
			width: 320,
			height: 150,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	printFlow : function(){
		var exportArray = Ext.encode(this.exportArray);
		window.open(this.baseUrl + "&task=exportFlow&target=printer&ulid="+this.ulid+"&exportArray="+exportArray, '_blank', 'width=740,height='+(screen.availHeight-120)+',left='+((screen.availWidth-740)/2)+',top=60,scrollbars=yes,resizable=yes,status=no');
	},
	
	readedFlow : function(){
		CNOA_flow_flow_view_reader = new CNOA_flow_flow_view_readerClass(this.ulid);
		CNOA_flow_flow_view_reader.show();
	},
	
	dispenseFlow : function(){
		var _this = this;
		var ID_dispenseFlow = Ext.id();
		
		var submit = function(btn){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=dispenseFlow",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
						});
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}else{
				CNOA.miniMsg.alertShowAt(btn, "未选择要分发的人员", 30);
			}
		}
		
		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			items: [
				{
					xtype: "textarea",
					style: 'margin: 0; padding: 0;',
					height: 65,
					width: 325,
					readOnly: true,
					allowBlank: false,
					fieldLabel: '分发给谁',
					name: "allUserNames"
				},
				{
					xtype: "hidden",
					name: "allUids"
				},
				{
					xtype: "buttonForSMS",
					style: 'margin: 0; padding: 0',
					autoWidth: true,
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					style: "margin-left:75px;",
					text: "选择人员",
					listeners: {
						"selected" : function(th, data){
							var names = new Array();
							var uids = new Array();
							if (data.length>0){
								for (var i=0;i<data.length;i++){
									names.push(data[i].uname);
									uids.push(data[i].uid);
								}
							}
							form.getForm().findField("allUserNames").setValue(names.join(","));
							form.getForm().findField("allUids").setValue(uids.join(","));
						},		
						"onrender" : function(th){
							th.setSelectedUids(form.getForm().findField("allUids").getValue().split(","));
						}
					}
				}
			]
		});
		
		var win = new Ext.Window({
			title: "分发工作流",
			width: 475,
			height: 180,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: "分发",
					id: ID_dispenseFlow,
					handler: function(btn){
						submit(btn);
					}
				},
				{
					text: CNOA.lang.btnClose,
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	}
}

var CNOA_flow_flow_user_viewflowClass, CNOA_flow_flow_user_viewflow;
var CNOA_flow_flow_user_dealflow_goNextStepClass, CNOA_flow_flow_user_dealflow_goNextStep;

CNOA_flow_flow_user_dealflow_goNextStepClass = CNOA.Class.create();
CNOA_flow_flow_user_dealflow_goNextStepClass.prototype = {
	/**
	 * 获取当前步骤的下一步信息
	 * @param {Object} flowid 流程ID
	 * @param {Object} stepid 步骤ID
	 */
	init: function(ulid){
		var _this = this;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.ulid = ulid;
		this.operatorperson = null;
		this.sponsor = false; //是否是主办人
		this.stepText = "";
		
		this.ID_html = Ext.id();
		this.ID_multiselects = Ext.id();
		this.ID_multiselects_tip = Ext.id();
		this.ID_nextStepTr1 = Ext.id();
		this.ID_nextStepTr2 = Ext.id();
		this.ID_window = Ext.id();
		
		//添加短信组件
		this.smsSender = new Ext.form.SMSSender({
			from: "flow",
			hideLabel: true,
			modal: true,
			//fwindow: this.ID_window,
			boxLabel: "手机短信提醒"
		});
		
		this.Tpl = new Ext.Template('<table width="596" border="0" cellspacing="1" cellpadding="0" style="background:#999;margin-bottom:5px;"><tr><td height="24" colspan="2" align="left" valign="middle" bgcolor="#C9C9C9">&nbsp;<b>下一步骤信息</b></td></tr><tr><td width="147" height="24" align="right" valign="middle" bgcolor="#D9D9D9">下一步骤名称:&nbsp;</td><td width="446" valign="middle" bgcolor="#F2F2F2">&nbsp;{name}</td></tr><tr id="'+this.ID_nextStepTr1+'"><td height="24" align="right" valign="middle" bgcolor="#D9D9D9">经办方式:&nbsp;</td><td valign="middle" bgcolor="#F2F2F2">&nbsp;{operatorperson}</td></tr><tr id="'+this.ID_nextStepTr2+'"><td height="24" align="right" valign="middle" bgcolor="#D9D9D9">转下下一步条件:&nbsp;</td><td valign="middle" bgcolor="#F2F2F2">&nbsp;{operatortype}</td></tr></table>');
		
		this.dataStoreFrom = new Ext.data.ArrayStore({data: [],fields: ['uid', 'name']});
		this.dataStoreTo = new Ext.data.ArrayStore({data: [],fields: ['uid', 'name']});
		
		this.formPanel = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 100,
			labelAlign: 'top',
			bodyStyle: "padding:10px;",
			waitMsgTarget: true,
			items: [
				{
					xtype: "panel",
					border: false,
					header: false,
					id: this.ID_html,
					html: ''
				},
				this.smsSender,
				{
					xtype: "textarea",
					fieldLabel: "办理意见",
					//allowBlank: false,
					name: "say",
					width: 597,
					height: 110,
					value: "同意"
				},
				{
					xtype: "radiogroup",
					hideLabel: true,
					name: "sayTgroup",
					height:50,
					width:250,
					items: [
						{boxLabel: "同意",name:"sayT",inputValue:"1",checked:true},
						{boxLabel: "不同意",name:"sayT",inputValue:"2"}
					],
					listeners: {
						"change" :function(th,checked){
							if(checked.inputValue == "1"){
								_this.formPanel.getForm().findField("say").setValue("同意");
							}else if(checked.inputValue=="2"){
								_this.formPanel.getForm().findField("say").setValue("不同意");
							}
						}	
					}
				},
				{
					xtype: 'itemselector',
					name: 'itemselector',
					hideLabel: true,
					id: this.ID_multiselects,
					imagePath: CNOA_EXTJS_PATH + '/ux/images/',
					multiselects: [
						{
							width: 288,
							height: 200,
							store: this.dataStoreFrom,
							displayField: 'name',
							valueField: 'uid',
							tbar: ['待选人员',{text: '&nbsp;',disabled: true}]
						},
						{
							width: 288,
							height: 200,
							store: this.dataStoreTo,
							displayField: 'name',
							valueField: 'uid',
							tbar: [
								'经办人',"->",
								{
									text: '清除',
									iconCls: "icon-clear",
									handler: function() {
										_this.formPanel.getForm().findField('itemselector').reset();
									}
								}
							]
						}
					]
				},
				{
					xtype: "displayfield",
					hideLabel: true,
					id: this.ID_multiselects_tip,
					value: "注意: 你所选择的经办人如果有多人，则列表第一人将作为主办人，只有主办人有权限决定他的下一步骤经办人。"
				}
			]
		});
		
		this.mainPanel = new Ext.Window({
			title: "转下一步 - 选择经办人",
			id: this.ID_window,
			width: 630,
			height: 610,
			layout: "fit",
			//layout: "border",
			modal: true,
			maximizable: false,
			resizable: false,
			//items: [this.centerPanel, this.rightPanel],
			items: [this.formPanel],
			buttons: [
				{
					text: "转下一步",
					iconCls: 'icon-fileview-column3',
					handler: function(btn){
						var f = _this.formPanel.getForm();
						if(f.isValid()){
							var v = f.findField('itemselector').getValue();
							var s = f.findField('say').getValue();
							//如果是主办人
							if(_this.sponsor){
								if(Ext.isEmpty(v)){
									CNOA.miniMsg.alertShowAt(btn, "请选择至少一人作为经办人");
									return false;
								}else{
									var vv = v.split(",");
									if((_this.operatorperson == '1') && (vv.length != 1)){
										CNOA.miniMsg.alertShowAt(btn, "该步骤经办方式为:单人办理, 只能选择一人作为经办人");
										return false;
									}
								}
							}
							if(_this.smsSender.validateValue()){
								CNOA_flow_flow_user_viewflow.sendFormStep2(s, v, function(){
									//提交手机短信
									try{
										_this.smsSender.submit();
									}catch(e){}
									
									_this.close();
								});
							}							
						}else{
							CNOA.miniMsg.alertShowAt(btn, "请检查未填写的地方");
						}
					}
				},
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						_this.close();
					}
				}
			],
			listeners: {
				close : function(){
					
				}
			}
		});
	},
	
	show: function(){
		this.mainPanel.show();
		
		this.loadNextStepInfo();
	},
	
	close: function(){
		this.mainPanel.close();
	},
	
	loadNextStepInfo: function(){
		var _this = this;
		
		_this.mainPanel.getEl().mask(CNOA.lang.msgLoadMask);

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=deal_loadNextStepInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					var rd = result.data;
					var rdNext = rd.nextData;
					//单人办理/多人办理 变量
					_this.operatorperson = rdNext.operatorperson;
					
					//处理html模板
					rdNext.operatorperson = rdNext.operatorperson == '1' ? "单人办理" : "多人办理";
					rdNext.operatortype = rdNext.operatortype == '1' ? "任意一人同意" : "所有人同意";
					_this.Tpl.append(_this.ID_html, rdNext);
					
					if(rd.nextStepType == "end"){
						Ext.fly(_this.ID_nextStepTr1).dom.style.display='none';
						Ext.fly(_this.ID_nextStepTr2).dom.style.display='none';
						Ext.getCmp(_this.ID_multiselects).hide();
						Ext.getCmp(_this.ID_multiselects_tip).hide();
						
						_this.mainPanel.setHeight(360);
					}else{
						if(rd.sponsor == '1'){
							//如果是主办人，处理待选列表
							_this.sponsor = true;
							
							_this.dataStoreFrom.removeAll();
							for(var i=0;i<rdNext.operator.length;i++){
								var records = new Ext.data.Record(rdNext.operator[i]);
								_this.dataStoreFrom.add(records);
							}
						}else{
							//如果不是主办人，隐藏下一步经办人列表
							_this.sponsor = false;
							
							Ext.getCmp(_this.ID_multiselects).hide();
							Ext.getCmp(_this.ID_multiselects_tip).hide();
							
							_this.mainPanel.setHeight(360);
						}
					}
				}else{
					CNOA.msg.alert(result.msg, function(){
						_this.close();
					});
				}
				_this.mainPanel.getEl().unmask();
			}
		});
	}
}

/**
* 主面板
*
*/
CNOA_flow_flow_user_viewflowClass = CNOA.Class.create();
CNOA_flow_flow_user_viewflowClass.prototype = {
	init : function(){
		var _this = this;

		this.ulid = CNOA.flow.flow.user_viewflow.ulid;
		this.ID_goto_nextstep = Ext.id();
		this.ID_disagree = Ext.id();
		this.ID_goto_prestep = Ext.id();
		this.ID_huiqian = Ext.id();
		this.ID_zhuangban = Ext.id();
		this.ID_huiqian_say = Ext.id();
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.flowPanelLoaded = this.formPanelLoaded = this.progressPanelLoaded = this.eventPanelLoaded = false;

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		this.ID_printArea = Ext.id();

		this.topTpl = new Ext.XTemplate(
			'<table width="100%" border="0" cellspacing="1" cellpadding="0" style="background-color:#CDCDCD">',
			'  <tr>',
			'    <td width="15%" height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程编号:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{name}</td>',
			'    <td width="15%" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程名称:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;[{flowname}]{title}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起人:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{uname}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起日期:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{posttime}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">当前步骤:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{stepText}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程状态:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{statusText}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">申请事由:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{reason}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">特殊备注:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{about}</td>',
			'  </tr>',
			'  <tpl if="attachCount &gt; 0">',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程附件:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;',
			'		<ul style="margin-top:10px;margin-left:10px;">',
			'			<tpl for="attach">',
			'			<li style="height:30px;margin-bottom:10px;background:url(./resources/images/icons/icon-attachment.png) no-repeat;text-indent:36px;">',
			'				<div>{name} <span style="color:#CCC">({size})</span></div>',
			'				<div id="user_flow_atach_{id}"></div>',
			'				<div style="text-decoration:underline;"><!-- <a href="{url}" target="_blank">下载</a> --><img src="'+Ext.BLANK_IMAGE_URL+'" onload="makeDownLoadIcon(\'user_flow_atach_{id}\', \'{url}\', \'{name}\');" /></div>',
			'			</li>',
			'			</tpl>',
			'		</ul>',
			'    </td>',
			'  </tr>',
			'  </tpl>',
			'</table>'
		);
		
		this.flowPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			style: "margin-bottom:10px;",
			title: "流程信息",
			listeners : {
				afterrender : function(p){
					_this.flowPanelLoaded = true;
					_this.view_loadFlowInfo();
				}
			}
		});
		
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
		
		this.gridProgress = new CNOA_flow_flow_flowViewProgressClass(this.ulid);
		this.progressPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "流程进度",
			items: [this.gridProgress.grid],
			listeners : {
				afterrender : function(p){
					var data = {};
					//_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		this.gridEvent = new CNOA_flow_flow_flowViewEventClass(this.ulid);
		this.eventPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "事件日志",
			items: [this.gridEvent.grid],
			listeners : {
				afterrender : function(p){
					var data = {};
					///_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		this.exportArray = {i:1,f:1,p:1,e:1};
		
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
					items: [this.flowPanel, this.formPanel, this.progressPanel, this.eventPanel]
				}
			],
			tbar : new Ext.Toolbar({
				items: [
					
					'显示：',
					{
						xtype: 'checkbox',
						boxLabel: '流程信息',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.i = 1;
									_this.flowPanel.show();
								}else{
									_this.exportArray.i = 0;
									_this.flowPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '工作表单',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.f = 1;
									_this.formPanel.show();
								}else{
									_this.exportArray.f = 0;
									_this.formPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '流程进度',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.p = 1;
									_this.progressPanel.show();
								}else{
									_this.exportArray.p = 0;
									_this.progressPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '事件日志',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.e = 1;
									_this.eventPanel.show();
								}else{
									_this.exportArray.e = 0;
									_this.eventPanel.hide();
								}
							}
						}
					},'-',
					{
						text: '导出',
						iconCls: 'icon-download',
						tooltip: "导出为HTML文件",
						menu : {
							items: [
								{
									iconCls:'document-html',
									text:'导出为网页文件(html)',
									handler: function(){
										_this.exportFlow('html');
									}.createDelegate(this)
								},
								{
									iconCls:'document-word',
									text:'导出为Word文档',
									handler: function(){
										_this.exportFlow('word');
									}.createDelegate(this)
								}
							]
						}
					},'-',
					{
						//id: this.ID_btn_refreshList,
						handler : function(button, event) {
							//printArea(_this.ID_printArea, "流程打印");
							_this.printFlow();
						}.createDelegate(this),
						iconCls: 'icon-print',
						hidden: Ext.isAir ? true : false,
						tooltip: "打印",
						text : "打印"
					}
				]
			})
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
						iconCls: 'icon-flow-goto-nextstep',
						text : "同意(转下一步)",
						id: this.ID_goto_nextstep,
						handler : function(btn, event) {
							//检查表单
							var f = _this.formPanel.getForm();
							if(!f.isValid() || !_this.checkForm()){
								CNOA.miniMsg.alertShowAt(btn, "部分表单未完成,请检查(请确认工作表单的必填字段已经填写完毕)", 30);
								return false;
							}
							
							_this.sendFormStep1();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-entrust',
						text : "会签",
						id: this.ID_huiqian,
						handler : function(btn, event) {
							_this.huiqian();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-entrust',
						text : "转办",
						id: this.ID_zhuangban,
						handler : function(btn, event) {
							_this.zhuangban();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-entrust',
						text : "会签意见",
						hidden: true,
						id: this.ID_huiqian_say,
						handler : function(btn, event) {
							_this.showHuiqianWindow();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-goto-firststep',
						text : "不同意(退件)",
						id: this.ID_disagree,
						handler : function(button, event) {
							_this.disagree();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-goto-prevstep',
						text : "退回上一步",
						id: this.ID_goto_prestep,
						handler : function(button, event) {
							_this.goto_prevstep();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-dialog-cancel',
						text : "关闭",
						handler : function(button, event) {
							_this.closeTab();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 20}
				]
			})
		});
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>未发送</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>办理中</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>已办理</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>已退件</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_graye'>已撤销</span>";}
		return h;
	},

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;
		
		var h  = "<a href='javascript:void(0);' onclick='("+value+");'>查看</a>";
			h += "&nbsp;&nbsp;";
			//状态为未发布/已退件/已撤销时
			h += (rs == '0' || rs == '3' || rs == '4') ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_viewflow.deleteFlow("+value+");'>删除</a>" : "　　";
			h += "&nbsp;&nbsp;";
			//状态为未发布时
			h += rs == '0' ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_viewflow.editPanel("+value+");'>编辑</a>" : "　　";
			h += "&nbsp;&nbsp;";
			//状态为办理中/已办理时
			h += (rs != '0' && rs != '3' && rs != '4') ? "<a href='javascript:void(0);' onclick='("+value+");'>召回</a>" : "　　";
			h += "&nbsp;&nbsp;";
			//状态为办理中/已办理时
			h += (rs != '0' && rs != '3' && rs != '4') ? "<a href='javascript:void(0);' onclick='("+value+");'>撤销</a>" : "　　";
		return h;
	},
	
	view_loadFlowInfo : function(){
		var _this = this;
		
		if(!this.flowPanelLoaded || !this.formPanelLoaded){
			return false;
		}

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=view_loadFlowInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					//基本信息
					_this.topTpl.overwrite(_this.flowPanel.body, result.data.flowInfo);
					
					_this.stepText = result.data.flowInfo.stepText;
					
					if(result.data.flowInfo.status == "2"){
						_this.hideOperatBtn();
					}
					
					if(result.data.isHuiQian == "1"){
						_this.showHuiqianBtn();
					}
					
					//工作表单
					var attCtId = Ext.id();
					var toolTip = "<div style='padding-bottom:5px;' class='x-border-layout-ct'><div class='flow_flow_form'><div>图示：</div><div class='flow_form_must_struct flow_form_must_color'>&nbsp;</div><div>必填字段</div><div class='flow_form_must_struct flow_form_need_color'>&nbsp;</div><div>可填字段</div><div class='flow_form_must_struct flow_form_disabled_color'>&nbsp;</div><div>不可填字段</div></div><div class='clear'></div><div id='"+attCtId+"'></div></div>";
					_this.formPanel.body.update(toolTip + result.data.formInfo, false, function(){
						if(result.data.flowInfo.allowup == '1'){
							new Ext.form.FlashFile({
								xtype: "flashfile",
								fieldLabel: "附件",
								name: "attach",
								style: "margin-top:5px;",
								inputPre: "filesUpload",
								renderTo: attCtId
							});
						}
					});

					//如果流程是已退件的流程
					if(result.data.flowInfo.status=="1" && result.data.flowInfo.step=="0"){
						var elements = _this.formPanel.getEl().query("input[name^=FLOWDATA],textarea[name^=FLOWDATA],select[name^=FLOWDATA]");
						//cdump(elements);
						Ext.each(elements, function(v, i){
							//if(_this.action == "edit"){
								Ext.each(result.data.formData, function(vv, ii){
									if(("FLOWDATA["+vv.itemid+"]") == v.name){
										v.value = vv.data;
									}
								});
							//}
							
							if(v.readOnly == true){
								//v.style.borderWidth = '0';
								v.style.border = '1px solid #E1E1E1';
								v.style.backgroundColor = '#F6F6F6';
							}else{
								v.style.border = '1px solid #BCBCBC';
							}
							v.title = "";
						});
					}
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},
	
	hideOperatBtn : function(){
		Ext.getCmp(this.ID_goto_nextstep).hide();
		Ext.getCmp(this.ID_disagree).hide();
		Ext.getCmp(this.ID_goto_prestep).hide();
	},
	
	showHuiqianBtn : function(){
		Ext.getCmp(this.ID_goto_nextstep).hide();
		Ext.getCmp(this.ID_disagree).hide();
		Ext.getCmp(this.ID_goto_prestep).hide();
		Ext.getCmp(this.ID_huiqian).hide();
		Ext.getCmp(this.ID_zhuangban).hide();
		Ext.getCmp(this.ID_huiqian_say).show();
	},
	
	showHuiqianWindow : function(){
		var _this = this;
		
		var loadHuiQianInfo = function(){
			var f = formPanel.getForm();
			f.load({
				url: _this.baseUrl + "&task=loadHuiQianInfo",
				params: {ulid: _this.ulid},
				method:'POST',
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				success: function(form, action){
					
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}
			})
		};
		
		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelWidth: 90,
			labelAlign: 'top',
			waitMsgTarget: true,
			bodyStyle: "padding-top:20px;padding:10px;",
			items: [
				{
					xtype: "textarea",
					height: 147,
					width: 360,
					name: "say",
					hideLabel: true,
					value: "已阅"
				},
				{
					xtype: 'radiogroup',
					hideLabel: true,
					width : 200,
					allowBlank: false,
					name: "sayTGroup",
					items: [
						{boxLabel: '已阅', name: 'sayT', inputValue: "1", checked: true},
						{boxLabel: '同意', name: 'sayT', inputValue: "2"},
						{boxLabel: '不同意', name: 'sayT', inputValue: "3"}
					],
					listeners : {
						"change" : function(th, checked){
							if(checked.inputValue=="1"){
								formPanel.getForm().findField("say").setValue("已阅");
							}else if(checked.inputValue=="2"){
								formPanel.getForm().findField("say").setValue("同意");
							}else if(checked.inputValue=="3"){
								formPanel.getForm().findField("say").setValue("不同意");
							}
						}
					}
				}
			]
		});
		var win = new Ext.Window({
			width: 400,
			height: 300,
			title: "会签意见",
			resizable: false,
			modal: true,
			layout: "fit",
			items: formPanel,
			buttons : [
				//汇报
				{
					text : "确定",
					id: _this.ID_btn_report_window_report,
					iconCls: 'icon-order-s-accept',
					handler : function() {
						submitHuiQian();
					}.createDelegate(this)
				},
				//关闭
				{
					text : "取消",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
		
		var submitHuiQian = function(){
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: _this.baseUrl+"&task=submitHuiQianInfo",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							try{CNOA_flow_flow_user_dealflow.store.reload();}catch(e){}
							_this.view_loadFlowInfo();
							_this.gridEvent.store.reload();
							win.close();
						}.createDelegate(this));
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}
		};

		loadHuiQianInfo();
	},
	
	goToDealFlowList : function(){
		//mainPanel.closeTab("CNOA_MENU_FLOW_USER_MYFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=dealflow", "CNOA_MENU_FLOW_DEALFLOW", "审批流程", "icon-flow-deal");
	},

	closeTab : function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_VIEWFLOW");
	},
	
	huiqian : function(){
		var _this = this;

		var ID_window = Ext.id();
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=setHuiQianInfo",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action){
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
						});
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		};
		var getHuiQianInfo = function(){
			var f = form.getForm();
			f.load({
				url: _this.baseUrl + "&task=getHuiQianInfo",
				params: {ulid: _this.ulid},
				method:'POST',
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				success: function(form, action){
					
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}
			})
		};
		var form = new Ext.form.FormPanel({
			border: false,
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			items: [
				{
					xtype: "displayfield",
					hideLabel: true,
					value: "步骤名称: " + _this.stepText,
					name: "stepText"
				},
				{
					xtype: "textarea",
					width: 483,
					height: 188,
					//anchor: "-10",
					readOnly: true,
					fieldLabel: '会签人员',
					name: "allUserNames"
				},
				{
					xtype: "hidden",
					name: "allUids"
				},
				{
					xtype: "buttonForSMS",
					autoWidth: true,
					dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					text: "选择人员",
					listeners: {
						"selected" : function(th, data){
							var names = new Array();
							var uids = new Array();
							if (data.length>0){
								for (var i=0;i<data.length;i++){
									names.push(data[i].uname);
									uids.push(data[i].uid);
								}
							}
							form.getForm().findField("allUserNames").setValue(names.join(","));
							form.getForm().findField("allUids").setValue(uids.join(","));
						},

						"onrender" : function(th){
							th.setSelectedUids(form.getForm().findField("allUids").getValue().split(","));
						},
						
						"afterrender" : function(){
							getHuiQianInfo();
						}
					}
				}
			]
		});
		var win = new Ext.Window({
			title: "设置本步骤会签人员",
			modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 360,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : "确定",
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : "取消",
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	zhuangban : function(){
		var _this = this;
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=setZhuangbanInfo",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action){
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
							_this.closeTab();
							try{
								CNOA_flow_flow_user_dealflow.store.reload();
							}catch(e){}
						});
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		};
		
		var form = new Ext.form.FormPanel({
			border: false,
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			items: [
				{
					xtype: "hidden",
					name: "uid"
				},
				{
					xtype: "triggerForPeople",
					fieldLabel:"选择转办人",
					allowBlank: false,
					name:"uname",
					dataUrl: _this.baseUrl+"&task=getAllUserListsInPermitDeptTree",
					width: 340,
					listeners: {
						"selected": function(th, uid, uname){
							form.getForm().findField("uid").setValue(uid);
						}
					}
				},
				{
					xtype : "textarea",
					width : 340,
					height: 200,
					fieldLabel : "转办意见",
					name : "say"
				}
			]
		});
		var win = new Ext.Window({
			title: "转办工作流",
			modal: true,
			maximizable: false,
			resizable: false,
			width: 375,
			height: 360,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : "确定",
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : "取消",
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	disagree : function(){
		var _this = this;

		var ID_window = Ext.id();

		//添加短信组件
		var smsSender = new Ext.form.SMSSender({
			from: "flow",
			modal: true,
			//fwindow: ID_window,
			hideLabel: true,
			boxLabel: '手机短信提醒'
		});
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=disagree",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action){
						//提交手机短信
						try{
							smsSender.submit();
						}catch(e){}

						CNOA.msg.alert(action.result.msg, function(){
							win.close();
							_this.closeTab();

							try{
								CNOA_flow_flow_user_dealflow.store.reload();
							}catch(e){}
						}.createDelegate(this));
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		}
		var form = new Ext.form.FormPanel({
			border: false,
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			items: [
				{
					xtype: "textarea",
					width: 483,
					height: 100,
					//allowBlank: false,
					value: '不同意',
					name: "reason",
					fieldLabel: "不同意理由"
				},
				{
					xtype: 'radiogroup',
					hideLabel: true,
					width : 200,
					allowBlank: false,
					name: "sayTgroup",
					items: [
						{boxLabel: '同意', name: 'sayT', inputValue: "1"},
						{boxLabel: '不同意', name: 'sayT', inputValue: "2",checked: true}
					],
					listeners : {
						"change" : function(th, checked){
							if(checked.inputValue=="1"){
								form.getForm().findField("reason").setValue("同意");
							}else if(checked.inputValue=="2"){
								form.getForm().findField("reason").setValue("不同意");
							}
						}
					}
				},
				smsSender
			]
		});
		var win = new Ext.Window({
			title: "不同意(退件)",
			//modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 300,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : "确定",
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : "取消",
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	goto_prevstep : function(){
		var _this = this;

		var ID_window = Ext.id();

		//添加短信组件
		var smsSender = new Ext.form.SMSSender({
			from: "flow",
			modal: true,
			//fwindow: ID_window,
			hideLabel: true,
			boxLabel: '手机短信提醒'
		});
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=gotoPrevstep",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action) {
						//提交手机短信
						try{
							smsSender.submit();
						}catch(e){}

						CNOA.msg.alert(action.result.msg, function(){
							win.close();
							_this.closeTab();
							try{
								CNOA_flow_flow_user_dealflow.store.reload();
							}catch(e){}
						}.createDelegate(this));
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		}
		var form = new Ext.form.FormPanel({
			border: false,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			waitMsgTarget: true,
			items: [
				{
					xtype: "textarea",
					width: 483,
					height: 220,
					//allowBlank: false,
					name: "reason",
					fieldLabel: "退回理由"
				},
				smsSender
			]
		});
		var win = new Ext.Window({
			title: "退回上一步",
			//modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 360,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : "确定",
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : "取消",
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	//获取下一步要发送的相关人员数据
	sendFormStep1: function(){
		var _this = this;
		
		//选择下一步经办人
		CNOA_flow_flow_user_dealflow_goNextStep = new CNOA_flow_flow_user_dealflow_goNextStepClass(_this.ulid);
		CNOA_flow_flow_user_dealflow_goNextStep.show();
	},
	
	checkForm: function(){
		
		var e1 = this.formPanel.getEl().query("input[name^=FLOWDATA][class*=flow_form_must],textarea[name^=FLOWDATA][class*=flow_form_must],select[name^=FLOWDATA][class*=flow_form_must]");
		
		var t = true;
		Ext.each(e1, function(v, i){
			if(Ext.isEmpty(v.value)){
				t = false;
			}
		});

		return t;
	},
	
	//发送数据
	sendFormStep2: function(say, uids, callback){
		var _this = this;
		
		if(Ext.isArray(uids)){
			uids = uids.join(",");
		}

		var f = _this.formPanel.getForm();
		if(f.isValid() && this.checkForm()){
			f.submit({
				url: _this.baseUrl+"&task=deal_sendFlow",
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				method: 'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				params: {ulid: _this.ulid, nextUids: uids, say: say},
				success: function(form, action) {
					try {
						callback.call(this);
					} catch (e) {}
					
					CNOA.msg.alert(action.result.msg, function(){
						_this.goToDealFlowList();
						_this.closeTab();
						
						try{
							CNOA_flow_flow_user_dealflow.store.reload();
						}catch(e){}
					}.createDelegate(this));
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){});
				}.createDelegate(this)
			});
		}else{
			CNOA.miniMsg.alertShowAt(_this.ID_goto_nextstep, "部分表单未完成,请检查(请确认工作表单的必填字段已经填写完毕)", 30);
		}
	},
	
	exportFlow : function(target){
		var _this = this;

		var ID_filedownload = Ext.id();
		var exportArray = Ext.encode(this.exportArray);

		var loadFormData = function(){
			form.getForm().submit({
				url: _this.baseUrl + "&task=exportFlow&target="+target,
				params: { ulid: _this.ulid, exportArray: exportArray },
				method:'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				success: function(form, action){
					var e = Ext.getCmp(ID_filedownload);
					e.getEl().update("请点击下载：<br/>"+makeDownLoadIcon2(action.result.msg, "img"));
				}.createDelegate(this),
				failure: function(form, action) {
					win.close();
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});
		}

		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender : function(th){
					loadFormData();
				}
			},
			items: [
				{
					xtype: "displayfield",
					name: "name",
					fieldLabel: '流程名称'
				},
				new Ext.BoxComponent({
					id: ID_filedownload,
					autoEl: {
						tag: 'div',
						style: 'padding-left:16px',
						html: ''
					}
				})
			]
		});
		var win = new Ext.Window({
			title: "导出工作流程",
			width: 320,
			height: 150,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	printFlow : function(){
		var exportArray = Ext.encode(this.exportArray);
		window.open(this.baseUrl + "&task=exportFlow&target=printer&ulid="+this.ulid+"&exportArray="+exportArray, '_blank', 'width=740,height='+(screen.availHeight-120)+',left='+((screen.availWidth-740)/2)+',top=60,scrollbars=yes,resizable=yes,status=no');
	}
}



var CNOA_flow_flow_user_flowlistOutClass, CNOA_flow_flow_user_flowlistOut;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_user_flowlistOutClass = CNOA.Class.create();
CNOA_flow_flow_user_flowlistOutClass.prototype = {
	init : function(){
		var _this = this;

		this.sort = CNOA.flow.flow.user_flowlistOut.sort;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		this.fields = [
			{name:"lid"},
			{name:"name"},
			{name:"formid"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getFlowJsonDataForout&sort="+this.sort, disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			//this.sm,
			{header: "lid", dataIndex: 'lid', hidden: true},
			{header: '流程名称', dataIndex: 'name', width: 140, sortable: true, menuDisabled :true},
			{header: "查看", dataIndex: 'lid', width: 70, sortable: false,menuDisabled :true, renderer:this.makeView.createDelegate(this)},
			{header: "操作", dataIndex: 'lid', width: 70, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 17}
				]
			}),
			bbar: this.pagingBar
		});
		

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid]//, this.bottomPanel
		});
	},

	makeView : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		var h  = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_flow_flow_user_flowlistOut.viewFlow("+value+", \""+rd.name+"\");'><img src='"+src+"icon-flow.png' style='margin:0 2px 4px 0' align='absmiddle' />流程图</a>";
			h += "&nbsp;&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_flowlistOut.viewForm("+rd.formid+");'><img src='"+src+"application_view_list.png' style='margin:0 2px 4px 0' align='absmiddle' />工作表单</a>";
			h += "</div>";
		return h;
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		var h  = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_flow_flow_user_flowlistOut.newFlow("+value+");'><img src='"+src+"page_addedit.png' style='margin:0 2px 4px 0' align='absmiddle' />新建</a>";
			h += "</div>";
		return h;
	},
	
	//新建流程
	newFlow : function(lid){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_NEWFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=newflow&ac=add&lid="+lid, "CNOA_MENU_FLOW_USER_NEWFLOW", "新建工作流程", "icon-flow-new");
	},

	//查看流程图
	viewFlow : function(lid, name){
		CNOA_flow_flow_flowpreview = new CNOA_flow_flow_flowpreviewClass(lid, name);
		CNOA_flow_flow_flowpreview.show();
	},
	
	//下载工作表单
	viewForm : function(fid){
		var _this = this;
		
		var box = Ext.getBody().getBox();
		var w	= box.width - 100;
		var h	= box.height - 100;
		
		var load = function(){
			Ext.Ajax.request({  
				url: _this.baseUrl + "&task=loadFormData",
				method: 'POST',
				params: {fid: fid},
				success: function(r){
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						panel.getEl().update("<center>"+result.data.content+"</center>");
					}else{
						CNOA.msg.alert(result.msg, function(){});
					}
					win.getEl().unmask();
				}
			});
		}
		
		var panel = new Ext.Panel({
			border: false,
			
			html: "工作表单载入中...",
			listeners: {
				afterrender : function(p){
					load();
				}
			}
		});
		
		var win = new Ext.Window({
			title: "查看工作表单",
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
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			],
			listeners: {
				show : function(win){
					win.getEl().mask(CNOA.lang.msgLoadMask);
				}
			}
		}).show();
	},
	
	//下载工作表单
	viewForm2 : function(fid){
		var _this = this;

		var ID_filedownload = Ext.id();

		var loadFormData = function(){
			form.getForm().load({
				url: _this.baseUrl + "&task=loadFormData",
				params: { fid: fid },
				method:'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				success: function(form, action){
					var e = Ext.getCmp(ID_filedownload);
					if(action.result.data.fileInfo == null){
						e.getEl().update("下载工作表单: (该工作表单尚未上传)");
					}else{
						//url/size
						e.getEl().update('下载工作表单: <img src="./resources/images/icons/icon_save.gif"><a href="'+action.result.data.fileInfo.url+'" target="_blank" ext:qtip="请点击右键另存为">下载</a>');
					}
				}.createDelegate(this),
				failure: function(form, action) {
					win.close();
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});
		}

		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender : function(th){
					loadFormData();
				}
			},
			items: [
				{
					xtype: "displayfield",
					name: "name",
					fieldLabel: '表单名称'
				},
				new Ext.BoxComponent({
					id: ID_filedownload,
					autoEl: {
						tag: 'div',
						style: 'padding-left:16px',
						html: ''
					}
				})
			]
		});

		var win = new Ext.Window({
			title: "查看工作表单",
			width: 350,
			height: 200,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	}
}


var CNOA_flow_flow_user_newcommonflow_Card1Class  = CNOA.Class.create();
CNOA_flow_flow_user_newcommonflow_Card1Class.prototype = {
	init: function(){
		var _this = this;
		
		this.ac		 = CNOA.flow.flow.user_newcommonflow.ac;
		this.ulid	 = CNOA.flow.flow.user_newcommonflow.ulid;
		this.baseUrl = "index.php?app=flow&func=flow&action=user&module=commonFlow";
		
		this.baseField = [
			{
				xtype: "fieldset",
				title: "流程信息",
				//width: 730,
				autoHeight: true,
				defaults: {
					width: 90,
					xtype: "textfield"
				},
				items: [
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 2
						},
						items: [{
							xtype: "panel",
							layout: "form",
							width: 350,
							items: [
								{
									xtype: "textfield",
									readOnly: true,
									fieldLabel: '流程名称',
									value: "通用流程",
									name: "flowname",
									width: 255
								}
							]
						}, {
							xtype: "panel",
							layout: "form",
							width: 350,
							items: [
								{
									xtype: "cnoa_textfield",
									readOnly: true,
									fieldLabel: '流程编号',
									width: 270,
									name: "name",
									value: "通用流程{NNNNNN}",
									tooltip: this.makeNumberTip()
								}
							]
						}]
					},
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 2
						},
						items: [
							{
								xtype: "panel",
								layout: "form",
								width: 350,
								items: [
									{
										xtype: "textfield",
										fieldLabel: '自定义标题',
										name: "title",
										width: 255,
										value: this.action == "edit" ? this.title : ""
									}
								]
							},
							{
								xtype: "panel",
								layout: "form",
								width: 350,
								items: [
									new Ext.form.ComboBox({
										fieldLabel: "重要程度",
										name: 'level',
										width: 270,
										store: new Ext.data.SimpleStore({
											fields: ['value', 'level'],
											data: [['1', "普通"], ['2', "重要"], ['3', "非常重要"]]
										}),
										hiddenName: 'level',
										valueField: 'value',
										displayField: 'level',
										mode: 'local',
										triggerAction: 'all',
										forceSelection: true,
										editable: false,
										value: this.action == "edit" ? this.level : 1
									})
								]
							}
						]
					},
					{
						xtype: "htmleditor",
						width: 620,
						height: 460,
						fieldLabel: "发起理由",
						name: "reason"
					},
					{
						xtype: "flashfile",
						fieldLabel: "附件",
						name: "attach",
						width: 620,
						style: "margin-top:5px;",
						inputPre: "filesUpload",
						listeners: {
							render : function(th){
								//th.setValue(CNOA.flow.flow.user_newflow.attach);
							}
						}
					}
				]
			}
		];
		
		this.formPanel = new Ext.form.FormPanel({
			fileUpload: true,
			autoWidth: true,
			border: false,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender: function(th){
					if(_this.ac == "edit"){
						_this.loadForm();
					}
				}
			},
			items: [this.baseField]
		});
		
		this.mainPanel = new Ext.Panel({
			hideBorders: true,
			border: false,
			autoScroll: true,
			items: [this.formPanel],
			tbar: new Ext.Toolbar({
				items: [
					"第一步：填写流程信息&nbsp;&nbsp;",
					{
						text: "下一步",
						iconCls: 'icon-fileview-column3',
						handler: function(){
							CNOA_flow_flow_user_newcommonflow.mainPanel.getLayout().setActiveItem(1);
						}
					}, '-', {
						text: "取消",
						iconCls: 'icon-dialog-cancel',
						handler: function(){
							CNOA_flow_flow_user_newcommonflow.closeTab();
						}
					}
				]
			})
		});
	},
	
	makeNumberTip : function(){
		var title = "编号规则";
		var html  = "{F}: 此变量表示当前的流程名称。<br>{U}: 此变量表示当前的流程发起者。<br>{Y}: 此变量表示当前时间的四位年份值，如2008。<br>{M}: 此变量表示当前时间的两位月份值，如12。<br>{D}: 此变量表示当前时间的两位日期值，如31。<br>{H}: 此变量表示当前时间的两位小时值，如08。<br>{I}: 此变量表示当前时间的两位分钟值，如59。<br>{S}: 此变量表示当前时间的两位秒钟值，如59。<br>{N}: 此变量表示当天的系统流水号，{N}表示一位流水号，{NNNNN}表示五位流水号，最多五位，如00001。<br><br><b>示例</b>: 如：{F}{Y}{M}{D}-{NNNN}，将会被转化为：流程名称20080808-0008。";
		
		return {text: html, title: title};
	},

	loadForm : function(){
		var _this = this;
		this.formPanel.getForm().load({
			url: _this.baseUrl+"&task=editLoadFormDataInfo",
			method: 'POST',
			waitMsg: CNOA.lang.msgLoadMask,
			params: {ulid: this.ulid},
			success: function(form, action){
				CNOA_flow_flow_user_newcommonflow.stepInfo = action.result.step;
				CNOA_flow_flow_user_newcommonflow.cardPanel2.insertData(action.result.step);
				//if(action.result.data.face != ""){
				//	Ext.fly("CNOA_MY_INDEX_INFO_FACEBOX").dom.src = action.result.data.face;
				//}
			}.createDelegate(this),
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg, function(){
					//_this.close();
				});
			}.createDelegate(this)
		});
	}
}

var CNOA_flow_flow_user_newcommonflow_Card2Class  = CNOA.Class.create();
CNOA_flow_flow_user_newcommonflow_Card2Class.prototype = {
	init: function(){
		var _this = this;
		
		this.ac		 = CNOA.flow.flow.user_newcommonflow.ac;
		this.ulid	 = CNOA.flow.flow.user_newcommonflow.ulid;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user&module=commonFlow";
		
		this.ID_btn_add		= Ext.id();
		this.ID_btn_edit	= Ext.id();
		this.selectedRow	= 0;
		
		this.store = new Ext.data.Store({
			//autoLoad : true,
			//proxy:new Ext.data.HttpProxy({url: this.baseUrl + '&task=getPrivList'}),   
			reader:new Ext.data.JsonReader({
				totalProperty:"total",
				root:"data", 
				fields: [
					{name:"id"},
					{name:"stepName"},
					{name:"uid"},
					{name:"uname"}
				]
			})
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:true
		});

		this.colum = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', sortable: true, hidden: true},
			{header : "步骤名称" , width: 400 , sortable: true, dataIndex : 'stepName'},
			{header : "经办人" , width: 200 ,sortable: true, dataIndex : 'uname'},
			{header : "删除", dataIndex: 'id' , width: 100 , sortable: true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);

		this.grid = new Ext.grid.GridPanel({
			store : this.store,
			sm: this.sm,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			region: "center",
			hideBorders: true,
			border: false,
			colModel : this.colum,
			bodyStyle: 'border-top-width:1px;',
			listeners: {
				cellclick : function(grid, rowIndex, columnIndex, e){
					if(columnIndex < 5){
						_this.selectedRow = rowIndex;
					
						Ext.getCmp(_this.ID_btn_edit).show();
						var record = grid.getStore().getAt(rowIndex);
						_this.addPanel.getForm().loadRecord(record);
					}
				}
			}
		});
		
		this.addPanel = new Ext.form.FormPanel({
			hideBorders: true,
			border: false,
			waitMsgTarget: true,
			autoScroll: true,
			header: false,
			height: 100,
			labelWidth: 90,
			region: "north",
			bodyStyle: 'background-color:#EEEEEE;',
			labelAlign: 'right',
			items: [
				{
					xtype : "panel",
					border : false,
					autoHeight: true,
					hideBorders : true,
					bodyStyle: 'background:none;',
					items : [
						{
							xtype: "panel",
							hideBorders: true,
							border: false,
							layout: "table",
							autoHeight: true,
							bodyStyle: 'background:none;padding:10px;',
							autoWidth: true,
							layoutConfig: {
								columns: 6
							},
							defaults: {
								style: "margin-right:5px"
							},
							items: [
								{
									xtype: "label",
									text: "步骤名称："
								},
								{
									xtype: "textfield",
									width: 220,
									emptyText: "请填写步骤名称",
									blankText: "请填写步骤名称",
									allowBlank: false,
									name: "stepName"
								},{
									xtype: "label",
									text: "经办人："
								},
								{
									xtype: "textfield",
									readOnly: true,
									width: 100,
									readOnly: true,
									emptyText: "请选择经办人",
									blankText: "请选择经办人",
									allowBlank: false,
									name: "uname"
								},
								{
									xtype: "hidden",
									allowBlank: false,
									name: "uid"
								},
								{
									xtype: "btnForPoepleSelectorSingle",
									text: "选择人员",
									dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
									listeners: {
										"selected" : function(th, data){
											var names = new Array();
											var uids = new Array();
											if (data.length>0){
												for (var i=0;i<data.length;i++){
													names.push(data[i].uname);
													uids.push(data[i].uid);
												}
											}
											_this.addPanel.getForm().findField("uname").setValue(names.join(","));
											_this.addPanel.getForm().findField("uid").setValue(uids.join(","));
										},
		
										"onrender" : function(th){
											th.setSelectedUids(_this.addPanel.getForm().findField("uid").getValue().split(","));
										}
									}
								}
								/*,
								{
									xtype: "displayfield",
									colspan: 4,
									value: "点击'选择人员'按钮后，选择某一个拥有审批权限的人，确定后点击'添加'按钮，即可添加到下面列表中"
								}*/
							]
						}
					]
				},
				{
					xtype : "panel",
					border : false,
					width : 900,
					hideBorders : true,
					bodyStyle: 'background:none;',
					items : [
						{
							xtype: "panel",
							hideBorders: true,
							border: false,
							layout: "table",
							bodyStyle: 'background:none;',
							autoWidth: true,
							layoutConfig: {
								columns: 2
							},
							items: [
								{
									xtype: "button",
									text: "添加新步骤",
									id: this.ID_btn_add,
									style: "margin-left:376px;",
									iconCls: 'icon-utils-s-add',
									handler: function(btn){
										_this.addData(btn);
									}
								},
								{
									xtype: "button",
									text: "修改",
									hidden: true,
									id: this.ID_btn_edit,
									style: "margin-left:5px",
									iconCls: 'icon-utils-s-add',
									handler: function(btn){
										_this.editData(btn);
									}
								}
							]
						}
					]
				}
			],
			tbar: new Ext.Toolbar({
				items: [
					"第二步：设置流程流转步骤&nbsp;&nbsp;",
					{
						text: "上一步",
						iconCls: 'icon-btn-arrow-left',
						handler: function(){
							CNOA_flow_flow_user_newcommonflow.mainPanel.getLayout().setActiveItem(0);
						}
					}, '-', {
						text: "提交",
						iconCls: 'icon-dialog-apply2',
						handler: function(btn){
							_this.getData(btn);
						}
					}, '-', {
						text: "取消",
						iconCls: 'icon-dialog-cancel',
						handler: function(){
							CNOA_flow_flow_user_newcommonflow.closeTab();
						}
					}
				]
			})
		});
		
		this.mainPanel = new Ext.Panel({
			layout: "border",
			items: [this.addPanel, this.grid]
		});
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		return "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_newcommonflow.cardPanel2.delData("+rowIndex+");'>删除</a>";
	},
	
	insertData : function(data){
		var _this = this;
		//cdump(data);
		Ext.each(data, function(v, i){
			var _rs = new Ext.data.Record({ 
				stepName: v.stepName,
				uname: v.uname,
				uid: v.uid
			});
			_this.grid.getStore().add(_rs);
		});
		_this.grid.getView().refresh();
	},
	
	addData : function(btn){
		if(this.addPanel.getForm().isValid()){
			Ext.getCmp(this.ID_btn_edit).hide();
		
			var _rs = new Ext.data.Record({ 
				//id: this.addPanel.getForm().findField(""),
				stepName: this.addPanel.getForm().findField("stepName").getValue(),
				uname: this.addPanel.getForm().findField("uname").getValue(),
				uid: this.addPanel.getForm().findField("uid").getValue()
			});
			this.addPanel.getForm().reset();
			this.grid.getStore().add(_rs);
			this.grid.getView().refresh();
		}else{
			CNOA.miniMsg.alertShowAt(btn, "请填写步骤名称及选择经办人");
		}
	},
	
	editData : function(btn){
		if(this.addPanel.getForm().isValid()){
			Ext.getCmp(this.ID_btn_edit).hide();
		
			var _rs = new Ext.data.Record({ 
				//id: this.addPanel.getForm().findField(""),
				stepName: this.addPanel.getForm().findField("stepName").getValue(),
				uname: this.addPanel.getForm().findField("uname").getValue(),
				uid: this.addPanel.getForm().findField("uid").getValue()
			});
			
			this.addPanel.getForm().reset();
			this.grid.getStore().removeAt(this.selectedRow);
			this.grid.getStore().insert(this.selectedRow, _rs);
			this.grid.getView().refresh();
		}else{
			CNOA.miniMsg.alertShowAt(btn, "请填写步骤名称及选择经办人");
		}
	},
	
	delData : function(rowIndex){
		this.grid.getStore().removeAt(rowIndex);
		this.grid.getView().refresh();
	},
	
	getData : function(btn){
		var data = [];
		var store = this.grid.getStore();
		
		if(store.getCount() >= 1){
			store.each(function(v, i){
				data.push(v.data);
			});

			CNOA_flow_flow_user_newcommonflow.submit(data);
		}else{
			CNOA.miniMsg.alertShowAt(btn, "请设置流程流转步骤，流程流转过程中至少需要有一个步骤");
		}
	}
}
	
CNOA_flow_flow_user_newcommonflowClass = CNOA.Class.create();
CNOA_flow_flow_user_newcommonflowClass.prototype = {
	init: function(){
		var _this = this;
		
		this.ac		 = CNOA.flow.flow.user_newcommonflow.ac;
		this.ulid	 = CNOA.flow.flow.user_newcommonflow.ulid;
		this.stepInfo = {};


		this.baseUrl = "index.php?app=flow&func=flow&action=user&module=commonFlow";
		this.cardPanel1 = new CNOA_flow_flow_user_newcommonflow_Card1Class();
		this.cardPanel2 = new CNOA_flow_flow_user_newcommonflow_Card2Class();

		this.mainPanel = new Ext.Panel({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'card',
			activeItem: 0,
			autoScroll: false,
			items: [this.cardPanel1.mainPanel, this.cardPanel2.mainPanel]
		});
	},
	
	closeTab: function(){
		var pid = CNOA.flow.flow.user_newcommonflow.parentID.replace("docs-", "");
		mainPanel.closeTab(pid);
	},
	
	goToMyFlow: function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_MYFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=myflow", "CNOA_MENU_FLOW_USER_MYFLOW", "我的流程", "icon-flow-my");
	},
	
	submit: function(data){
		var	_this = this;
		var f = this.cardPanel1.formPanel.getForm();
		//var f = CNOA_flow_flow_user_newcommonflow.cardPanel1.formPanel.getForm();
		//data.form = f.getValues();

		CNOA.msg.cf("确认要提交吗？", function(btn) {
			if (btn == 'yes') {
				//f.findField('content').syncValue();

				if (f.isValid()) {
					f.submit({
						url: _this.baseUrl + "&task=sendFlow",
						waitMsg: CNOA.lang.msgLoadMask,
						params: {step: Ext.encode(data), ac: _this.ac, ulid: _this.ulid},
						method: 'POST',	
						success: function(form, action) {
							_this.closeTab();
							_this.goToMyFlow();
						}.createDelegate(this),
						failure: function(form, action) {
							CNOA.msg.alert(action.result.msg);
						}.createDelegate(this)
					});
				}

				/*
				Ext.Ajax.request({
					url: this.baseUrl + "&task=sendFlow",
					method: 'POST',
					params: { data: Ext.encode(data), ac: this.ac, ulid: this.ulid },
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							CNOA.msg.alert(result.msg, function(){
								_this.closeTab();
								_this.goToMyFlow();
							});
						}else{
							CNOA.msg.alert(result.msg);
						}
					}
				});
				*/
			}
		});
	}
}

/**
通用流程，查看界面
**/
var CNOA_flow_flow_user_showcommonflowClass, CNOA_flow_flow_user_showcommonflow;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_user_showcommonflowClass = CNOA.Class.create();
CNOA_flow_flow_user_showcommonflowClass.prototype = {
	init : function(){
		var _this = this;

		this.ulid = CNOA.flow.flow.user_showcommonflow.ulid;
		this.ID_goto_nextstep = Ext.id();
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.flowPanelLoaded = this.progressPanelLoaded = this.eventPanelLoaded = false;

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		this.ID_printArea = Ext.id();
		this.flowInfo = null;

		this.topTpl = new Ext.XTemplate(
			'<table width="100%" border="0" cellspacing="1" cellpadding="0" style="background-color:#CDCDCD">',
			'  <tr>',
			'    <td width="15%" height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程编号:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{name}</td>',
			'    <td width="15%" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程名称:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;[{flowname}]{title}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起人:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{uname}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起日期:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{posttime}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">当前步骤:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{stepText}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程状态:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{statusText}</td>',
			'  </tr>',
			//'  <tr>',
			//'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">申请事由:&nbsp;</td>',
			//'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{reason}</td>',
			//'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程步骤:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{stepInfo}</td>',
			'  </tr>',
			'  <tpl if="attachCount &gt; 0">',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程附件:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;',
			'		<tpl for="attach">',
			'		<a href="#" id="user_flow_atach_{id}" class="attach_down"><img src="'+Ext.BLANK_IMAGE_URL+'" onload="makeFlowAttacBtn(\'user_flow_atach_{id}\', \'{url}\', \'{name}\', \'{size}\');"></a>',
			'		</tpl>',
			'    </td>',
			'  </tr>',
			'  </tpl>',
			'</table>'
		);
		
		/*
		 * 事件信息
		 */
		this.flowPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			style: "margin-bottom:10px;",
			title: "流程信息",
			listeners : {
				afterrender : function(p){
					_this.flowPanelLoaded = true;
					_this.view_loadFlowInfo();
				}
			}
		});
		
		/*
		 * 事件进度
		 */
		this.gridProgress = new CNOA_flow_flow_flowViewProgressClass(this.ulid, true, false);
		this.progressPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "流程进度",
			layout: "fit",
			items: [this.gridProgress.grid],
			listeners : {
				afterrender : function(p){
					var data = {};
					//_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		/*
		 * 事件日志
		 */
		this.gridEvent = new CNOA_flow_flow_flowViewEventClass(this.ulid);
		this.eventPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			layout: "fit",
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "事件日志",
			items: [this.gridEvent.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					///_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		/*
		 * 评阅记录
		 */
		this.gridReader = new CNOA_flow_flow_flowViewReaderClass(this.ulid);
		this.readerPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			layout: "fit",
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "评阅记录",
			items: [this.gridReader.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					///_this.topTpl.overwrite(p.body, data);
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
					items: [this.flowPanel, this.progressPanel, this.eventPanel, this.readerPanel]
				}
			]
		});
		
		this.exportArray = {i:1,p:1,e:1,r:1};
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel],
			tbar : new Ext.Toolbar({
				items: [
					'显示：',
					{
						xtype: 'checkbox',
						boxLabel: '流程信息',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.i = 1;
									_this.flowPanel.show();
								}else{
									_this.exportArray.i = 0;
									_this.flowPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '流程进度',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.p = 1;
									_this.progressPanel.show();
								}else{
									_this.exportArray.p = 0;
									_this.progressPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '事件日志',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.e = 1;
									_this.eventPanel.show();
								}else{
									_this.exportArray.e = 0;
									_this.eventPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '评阅记录',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.r = 1;
									_this.readerPanel.show();
								}else{
									_this.exportArray.r = 0;
									_this.readerPanel.hide();
								}
							}
						}
					},'-',
					{
						text: '导出',
						iconCls: 'icon-download',
						tooltip: "导出为HTML文件",
						menu : {
							items: [
								{
									iconCls:'document-html',
									text:'导出为网页文件(html)',
									handler: function(){
										_this.exportFlow('html');
									}.createDelegate(this)
								},
								{
									iconCls:'document-word',
									text:'导出为Word文档',
									handler: function(){
										_this.exportFlow('word');
									}.createDelegate(this)
								}
							]
						}
					},'-',
					{
						handler : function(button, event) {
							_this.dispenseFlow();
							//printArea(_this.ID_printArea, "流程打印");
						}.createDelegate(this),
						iconCls: 'icon-flow-entrust',
						tooltip: "分发",
						text : "分发"
					},'-',
					{
						//id: this.ID_btn_refreshList,
						handler : function(button, event) {
							_this.gridReader.showSay();
						}.createDelegate(this),
						hidden: Ext.isAir ? true : false,
						iconCls: 'icon-view-form-png',
						text : "我要评阅"
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
					},'-',{
						iconCls: 'icon-dialog-cancel',
						text : "关闭",
						handler : function(button, event) {
							_this.closeTab();
						}.createDelegate(this)
					},
					"->",
					{xtype: 'cnoa_helpBtn', helpid: 19}
				]
			})
		});
	},
	
	view_loadFlowInfo : function(){
		var _this = this;
		
		if(!this.flowPanelLoaded){
			return false;
		}

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=show_loadFlowInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					//基本信息
					_this.topTpl.overwrite(_this.flowPanel.body, result.data.flowInfo);
					
					_this.flowInfo = result.data.flowInfo;
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},

	closeTab : function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_SHOWFLOW");
	},
	
	exportFlow : function(target){
		var _this = this;
		var ID_filedownload = Ext.id();
		var exportArray = Ext.encode(this.exportArray);
		var loadFormData = function(){
			form.getForm().submit({
				url: _this.baseUrl + "&task=exportFlow&target="+target,
				params: { ulid: _this.ulid, exportArray: exportArray },
				method:'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				success: function(form, action){
					var e = Ext.getCmp(ID_filedownload);
					e.getEl().update("请点击下载：<br/>"+makeDownLoadIcon2(action.result.msg, "img"));
				}.createDelegate(this),
				failure: function(form, action) {
					win.close();
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});
		}

		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender : function(th){
					loadFormData();
				}
			},
			items: [
				{
					xtype: "displayfield",
					name: "flowname",
					fieldLabel: '流程名称',
					value: _this.flowInfo.name
				},
				new Ext.BoxComponent({
					id: ID_filedownload,
					autoEl: {
						tag: 'div',
						style: 'padding-left:16px',
						html: ''
					}
				})
			]
		});
		var win = new Ext.Window({
			title: "导出工作流程",
			width: 320,
			height: 150,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	printFlow : function(){
		var exportArray = Ext.encode(this.exportArray);
		window.open(this.baseUrl + "&task=exportFlow&target=printer&ulid="+this.ulid+"&exportArray="+exportArray, '_blank', 'width=740,height='+(screen.availHeight-120)+',left='+((screen.availWidth-740)/2)+',top=60,scrollbars=yes,resizable=yes,status=no');
	},
	
	readedFlow : function(){
		CNOA_flow_flow_view_reader = new CNOA_flow_flow_view_readerClass(this.ulid);
		CNOA_flow_flow_view_reader.show();
	},
	
	dispenseFlow : function(){
		var _this = this;
		var ID_dispenseFlow = Ext.id();
		
		var submit = function(btn){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=dispenseFlow",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
						});
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}else{
				CNOA.miniMsg.alertShowAt(btn, "未选择要分发的人员", 30);
			}
		}
		
		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			items: [
				{
					xtype: "textarea",
					style: 'margin: 0; padding: 0;',
					height: 65,
					width: 365,
					readOnly: true,
					allowBlank: false,
					fieldLabel: '分发给谁',
					name: "allUserNames"
				},
				{
					xtype: "hidden",
					name: "allUids"
				},
				{
					xtype: "buttonForSMS",
					style: 'margin: 0; padding: 0',
					autoWidth: true,
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					style: "margin-left:75px;",
					text: "选择人员",
					listeners: {
						"selected" : function(th, data){
							var names = new Array();
							var uids = new Array();
							if (data.length>0){
								for (var i=0;i<data.length;i++){
									names.push(data[i].uname);
									uids.push(data[i].uid);
								}
							}
							form.getForm().findField("allUserNames").setValue(names.join(","));
							form.getForm().findField("allUids").setValue(uids.join(","));
						},		
						"onrender" : function(th){
							th.setSelectedUids(form.getForm().findField("allUids").getValue().split(","));
						}
					}
				}
			]
		});
		
		var win = new Ext.Window({
			title: "分发工作流",
			width: 475,
			height: 180,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: "分发",
					id: ID_dispenseFlow,
					handler: function(btn){
						submit(btn);
					}
				},
				{
					text: CNOA.lang.btnClose,
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	}
}

var CNOA_flow_flow_user_viewcommonflowClass, CNOA_flow_flow_user_viewcommonflow;
var CNOA_flow_flow_user_dealcommonflow_goNextStepClass, CNOA_flow_flow_user_dealcommonflow_goNextStep;

CNOA_flow_flow_user_dealcommonflow_goNextStepClass = CNOA.Class.create();
CNOA_flow_flow_user_dealcommonflow_goNextStepClass.prototype = {
	/**
	 * 获取当前步骤的下一步信息
	 * @param {Object} flowid 流程ID
	 * @param {Object} stepid 步骤ID
	 */
	init: function(ulid){
		var _this = this;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.ulid = ulid;
		this.operatorperson = null;
		this.sponsor = false; //是否是主办人
		this.stepText = "";
		
		this.ID_html = Ext.id();
		this.ID_multiselects = Ext.id();
		this.ID_multiselects_tip = Ext.id();
		this.ID_nextStepTr1 = Ext.id();
		this.ID_nextStepTr2 = Ext.id();
		this.ID_window = Ext.id();
		
		//添加短信组件
		this.smsSender = new Ext.form.SMSSender({
			from: "flow",
			hideLabel: true,
			modal: true,
			//fwindow: this.ID_window,
			boxLabel: "手机短信提醒"
		});
		
		this.Tpl = new Ext.Template('<table width="596" border="0" cellspacing="1" cellpadding="0" style="background:#999;margin-bottom:5px;"><tr><td height="24" colspan="2" align="left" valign="middle" bgcolor="#C9C9C9">&nbsp;<b>下一步骤信息</b></td></tr><tr><td width="147" height="24" align="right" valign="middle" bgcolor="#D9D9D9">下一步骤名称:&nbsp;</td><td width="446" valign="middle" bgcolor="#F2F2F2">&nbsp;{name}</td></tr><tr id="'+this.ID_nextStepTr1+'"><td height="24" align="right" valign="middle" bgcolor="#D9D9D9">经办方式:&nbsp;</td><td valign="middle" bgcolor="#F2F2F2">&nbsp;{operatorperson}</td></tr><tr id="'+this.ID_nextStepTr2+'"><td height="24" align="right" valign="middle" bgcolor="#D9D9D9">转下下一步条件:&nbsp;</td><td valign="middle" bgcolor="#F2F2F2">&nbsp;{operatortype}</td></tr></table>');
		
		this.dataStoreFrom = new Ext.data.ArrayStore({data: [],fields: ['uid', 'name']});
		this.dataStoreTo = new Ext.data.ArrayStore({data: [],fields: ['uid', 'name']});
		
		this.formPanel = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 100,
			labelAlign: 'top',
			bodyStyle: "padding:10px;",
			waitMsgTarget: true,
			items: [
				{
					xtype: "panel",
					border: false,
					header: false,
					id: this.ID_html,
					html: ''
				},
				this.smsSender,
				{
					xtype: "textarea",
					fieldLabel: "办理意见",
					name: "say",
					width: 597,
					height: 110,
					value: "同意"
				},
				{
					xtype: 'radiogroup',
					hideLabel: true,
					width : 200,
					allowBlank: false,
					name: "sayTgroup",
					items: [
						{boxLabel: '同意', name: 'sayT', inputValue: "1", checked: true},
						{boxLabel: '不同意', name: 'sayT', inputValue: "2"}
					],
					listeners : {
						"change" : function(th, checked){
							if(checked.inputValue=="1"){
								_this.formPanel.getForm().findField("say").setValue("同意");
							}else if(checked.inputValue=="2"){
								_this.formPanel.getForm().findField("say").setValue("不同意");
							}
						}
					}
				},
				{
					xtype: 'itemselector',
					name: 'itemselector',
					hideLabel: false,
					id: this.ID_multiselects,
					imagePath: CNOA_EXTJS_PATH + '/ux/images/',
					multiselects: [
						{
							width: 288,
							height: 200,
							store: this.dataStoreFrom,
							fromLegend:"已选资金来源",
							displayField: 'name',
							valueField: 'uid',
							tbar: ['待选人员',{text: '&nbsp;',disabled: true}]
						},
						{
							width: 288,
							height: 200,
							store: this.dataStoreTo,
							toLegend:"已选资金来源",
							displayField: 'name',
							valueField: 'uid',
							tbar: [
								'经办人',"->",
								{
									text: '清除',
									iconCls: "icon-clear",
									handler: function() {
										_this.formPanel.getForm().findField('itemselector').reset();
									}
								}
							]
						}
					]
				},
				{
					xtype: "displayfield",
					hideLabel: true,
					id: this.ID_multiselects_tip,
					value: "注意: 你所选择的经办人如果有多人，则列表第一人将作为主办人，只有主办人有权限决定他的下一步骤经办人。"
				}
			]
		});
		
		this.mainPanel = new Ext.Window({
			title: "转下一步 - 选择经办人",
			id: this.ID_window,
			width: 630,
			height: 610,
			layout: "fit",
			//layout: "border",
			modal: true,
			maximizable: false,
			resizable: false,
			//items: [this.centerPanel, this.rightPanel],
			items: [this.formPanel],
			buttons: [
				{
					text: "转下一步",
					iconCls: 'icon-fileview-column3',
					handler: function(btn){
						var f = _this.formPanel.getForm();
						if(f.isValid()){
							var v = f.findField('itemselector').getValue();
							var s = f.findField('say').getValue();
							//如果是主办人
							if(_this.sponsor){
								if(Ext.isEmpty(v)){
									CNOA.miniMsg.alertShowAt(btn, "请选择至少一人作为经办人");
									return false;
								}else{
									var vv = v.split(",");
									if((_this.operatorperson == '1') && (vv.length != 1)){
										CNOA.miniMsg.alertShowAt(btn, "该步骤经办方式为:单人办理, 只能选择一人作为经办人");
										return false;
									}
								}
							}
							if(_this.smsSender.validateValue()){
								CNOA_flow_flow_user_viewcommonflow.sendFormStep2(s, v, function(){
									//提交手机短信
									try{
										_this.smsSender.submit();
									}catch(e){}
									
									_this.close();
								});
							}							
						}else{
							CNOA.miniMsg.alertShowAt(btn, "请检查未填写的地方");
						}
					}
				},
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						_this.close();
					}
				}
			],
			listeners: {
				close : function(){
					
				}
			}
		});
	},
	
	show: function(){
		this.mainPanel.show();
		
		this.loadNextStepInfo();
	},
	
	close: function(){
		this.mainPanel.close();
	},
	
	loadNextStepInfo: function(){
		var _this = this;
		
		_this.mainPanel.getEl().mask(CNOA.lang.msgLoadMask);

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=deal_loadNextStepInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					var rd = result.data;
					var rdNext = rd.nextData;
					//单人办理/多人办理 变量
					_this.operatorperson = rdNext.operatorperson;
					
					//处理html模板
					rdNext.operatorperson = rdNext.operatorperson == '1' ? "单人办理" : "多人办理";
					rdNext.operatortype = rdNext.operatortype == '1' ? "任意一人同意" : "所有人同意";
					_this.Tpl.append(_this.ID_html, rdNext);

					if(rd.nextStepType == "end"){
						Ext.fly(_this.ID_nextStepTr1).dom.style.display='none';
						Ext.fly(_this.ID_nextStepTr2).dom.style.display='none';
						Ext.getCmp(_this.ID_multiselects).hide();
						Ext.getCmp(_this.ID_multiselects_tip).hide();

						_this.mainPanel.setHeight(306);
					}else{
						if(rd.sponsor == '1'){
							//如果是主办人，处理待选列表
							_this.sponsor = true;
							
							_this.dataStoreFrom.removeAll();
							for(var i=0;i<rdNext.operator.length;i++){
								var records = new Ext.data.Record(rdNext.operator[i]);
								_this.dataStoreFrom.add(records);
							}

							Ext.getCmp(_this.ID_multiselects).fromMultiselect.view.select([0]);
							Ext.getCmp(_this.ID_multiselects).fromTo();
						}else{
							//如果不是主办人，隐藏下一步经办人列表
							_this.sponsor = false;
							
							Ext.getCmp(_this.ID_multiselects).hide();
							Ext.getCmp(_this.ID_multiselects_tip).hide();
							
							_this.mainPanel.setHeight(360);
						}
					}
				}else{
					CNOA.msg.alert(result.msg, function(){
						_this.close();
					});
				}
				_this.mainPanel.getEl().unmask();
			}
		});
	}
}

/**
* 主面板
*
*/
CNOA_flow_flow_user_viewcommonflowClass = CNOA.Class.create();
CNOA_flow_flow_user_viewcommonflowClass.prototype = {
	init : function(){
		var _this = this;

		this.ulid = CNOA.flow.flow.user_viewcommonflow.ulid;
		this.ID_goto_nextstep = Ext.id();
		this.ID_disagree = Ext.id();
		this.ID_goto_prestep = Ext.id();
		this.ID_huiqian = Ext.id();
		this.ID_zhuangban = Ext.id();
		this.ID_huiqian_say = Ext.id();
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.flowPanelLoaded = this.progressPanelLoaded = this.eventPanelLoaded = false;

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		this.ID_printArea = Ext.id();

		this.topTpl = new Ext.XTemplate(
			'<table width="100%" border="0" cellspacing="1" cellpadding="0" style="background-color:#CDCDCD">',
			'  <tr>',
			'    <td width="15%" height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程编号:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{name}</td>',
			'    <td width="15%" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程名称:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;[{flowname}]{title}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起人:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{uname}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起日期:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{posttime}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">当前步骤:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{stepText}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程状态:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{statusText}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">申请事由:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{reason}</td>',
			'  </tr>',
			'  <tpl if="attachCount &gt; 0">',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程附件:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;',
			'		<tpl for="attach">',
			'		<a href="#" id="user_flow_atach_{id}" class="attach_down"><img src="'+Ext.BLANK_IMAGE_URL+'" onload="makeFlowAttacBtn(\'user_flow_atach_{id}\', \'{url}\', \'{name}\', \'{size}\');"></a>',
			'		</tpl>',
			'    </td>',
			'  </tr>',
			'  </tpl>',
			'</table>'
		);
		
		this.flowPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			style: "margin-bottom:10px;",
			title: "流程信息",
			listeners : {
				afterrender : function(p){
					_this.flowPanelLoaded = true;
					_this.view_loadFlowInfo();
				}
			}
		});
		
		this.gridProgress = new CNOA_flow_flow_flowViewProgressClass(this.ulid, true, false);
		this.progressPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "流程进度",
			items: [this.gridProgress.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					//_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		this.gridEvent = new CNOA_flow_flow_flowViewEventClass(this.ulid);
		this.eventPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "事件日志",
			items: [this.gridEvent.grid],
			listeners : {
				afterrender : function(p){
					var data = {};
					///_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		this.exportArray = {i:1,p:1,e:1};
		
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
					items: [this.flowPanel, this.progressPanel, this.eventPanel]
				}
			],
			tbar : new Ext.Toolbar({
				items: [
					
					'显示：',
					{
						xtype: 'checkbox',
						boxLabel: '流程信息',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.i = 1;
									_this.flowPanel.show();
								}else{
									_this.exportArray.i = 0;
									_this.flowPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '流程进度',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.p = 1;
									_this.progressPanel.show();
								}else{
									_this.exportArray.p = 0;
									_this.progressPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '事件日志',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.e = 1;
									_this.eventPanel.show();
								}else{
									_this.exportArray.e = 0;
									_this.eventPanel.hide();
								}
							}
						}
					},'-',
					{
						text: '导出',
						iconCls: 'icon-download',
						tooltip: "导出为HTML文件",
						menu : {
							items: [
								{
									iconCls:'document-html',
									text:'导出为网页文件(html)',
									handler: function(){
										_this.exportFlow('html');
									}.createDelegate(this)
								},
								{
									iconCls:'document-word',
									text:'导出为Word文档',
									handler: function(){
										_this.exportFlow('word');
									}.createDelegate(this)
								}
							]
						}
					},'-',
					{
						//id: this.ID_btn_refreshList,
						handler : function(button, event) {
							//printArea(_this.ID_printArea, "流程打印");
							_this.printFlow();
						}.createDelegate(this),
						iconCls: 'icon-print',
						hidden: Ext.isAir ? true : false,
						tooltip: "打印",
						text : "打印"
					}
				]
			})
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
						iconCls: 'icon-flow-goto-nextstep',
						text : "同意(转下一步)",
						id: this.ID_goto_nextstep,
						handler : function(btn, event) {
							_this.sendFormStep1();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-entrust',
						text : "会签",
						id: this.ID_huiqian,
						handler : function(btn, event) {
							_this.huiqian();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-entrust',
						text : "转办",
						id: this.ID_zhuangban,
						handler : function(btn, event) {
							_this.zhuangban();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-entrust',
						text : "会签意见",
						hidden: true,
						id: this.ID_huiqian_say,
						handler : function(btn, event) {
							_this.showHuiqianWindow();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-goto-firststep',
						text : "不同意(退件)",
						id: this.ID_disagree,
						handler : function(button, event) {
							_this.disagree();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-flow-goto-prevstep',
						text : "退回上一步",
						id: this.ID_goto_prestep,
						handler : function(button, event) {
							_this.goto_prevstep();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-dialog-cancel',
						text : "关闭",
						handler : function(button, event) {
							_this.closeTab();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 20}
				]
			})
		});
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>未发送</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>办理中</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>已办理</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>已退件</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_graye'>已撤销</span>";}
		return h;
	},

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;
		
		var h  = "<a href='javascript:void(0);' onclick='("+value+");'>查看</a>";
			h += "&nbsp;&nbsp;";
			//状态为未发布/已退件/已撤销时
			h += (rs == '0' || rs == '3' || rs == '4') ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_viewcommonflow.deleteFlow("+value+");'>删除</a>" : "　　";
			h += "&nbsp;&nbsp;";
			//状态为未发布时
			h += rs == '0' ? "<a href='javascript:void(0);' onclick='CNOA_flow_flow_user_viewcommonflow.editPanel("+value+");'>编辑</a>" : "　　";
			h += "&nbsp;&nbsp;";
			//状态为办理中/已办理时
			h += (rs != '0' && rs != '3' && rs != '4') ? "<a href='javascript:void(0);' onclick='("+value+");'>召回</a>" : "　　";
			h += "&nbsp;&nbsp;";
			//状态为办理中/已办理时
			h += (rs != '0' && rs != '3' && rs != '4') ? "<a href='javascript:void(0);' onclick='("+value+");'>撤销</a>" : "　　";
		return h;
	},
	
	view_loadFlowInfo : function(){
		var _this = this;
		
		if(!this.flowPanelLoaded){
			return false;
		}

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=view_loadFlowInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					//基本信息
					_this.topTpl.overwrite(_this.flowPanel.body, result.data.flowInfo);
					
					_this.stepText = result.data.flowInfo.stepText;
					
					if(result.data.flowInfo.status == "2"){
						_this.hideOperatBtn();
					}
					
					if(result.data.isHuiQian == "1"){
						_this.showHuiqianBtn();
					}
					
					//工作表单
					var attCtId = Ext.id();
					var toolTip = "<div style='padding-bottom:5px;' class='x-border-layout-ct'><div class='flow_flow_form'><div>图示：</div><div class='flow_form_must_struct flow_form_must_color'>&nbsp;</div><div>必填字段</div><div class='flow_form_must_struct flow_form_need_color'>&nbsp;</div><div>可填字段</div><div class='flow_form_must_struct flow_form_disabled_color'>&nbsp;</div><div>不可填字段</div></div><div class='clear'></div><div id='"+attCtId+"'></div></div>";

					//如果流程是已退件的流程
					if(result.data.flowInfo.status=="1" && result.data.flowInfo.step=="0"){
						var elements = _this.formPanel.getEl().query("input[name^=FLOWDATA],textarea[name^=FLOWDATA],select[name^=FLOWDATA]");
						//cdump(elements);
						Ext.each(elements, function(v, i){
							//if(_this.action == "edit"){
								Ext.each(result.data.formData, function(vv, ii){
									if(("FLOWDATA["+vv.itemid+"]") == v.name){
										v.value = vv.data;
									}
								});
							//}
							
							if(v.readOnly == true){
								//v.style.borderWidth = '0';
								v.style.border = '1px solid #E1E1E1';
								v.style.backgroundColor = '#F6F6F6';
							}else{
								v.style.border = '1px solid #BCBCBC';
							}
							v.title = "";
						});
					}
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},
	
	hideOperatBtn : function(){
		Ext.getCmp(this.ID_goto_nextstep).hide();
		Ext.getCmp(this.ID_disagree).hide();
		Ext.getCmp(this.ID_goto_prestep).hide();
	},
	
	showHuiqianBtn : function(){
		Ext.getCmp(this.ID_goto_nextstep).hide();
		Ext.getCmp(this.ID_disagree).hide();
		Ext.getCmp(this.ID_goto_prestep).hide();
		Ext.getCmp(this.ID_huiqian).hide();
		Ext.getCmp(this.ID_zhuangban).hide();
		Ext.getCmp(this.ID_huiqian_say).show();
	},
	
	showHuiqianWindow : function(){
		var _this = this;
		
		var loadHuiQianInfo = function(){
			var f = formPanel.getForm();
			f.load({
				url: _this.baseUrl + "&task=loadHuiQianInfo",
				params: {ulid: _this.ulid},
				method:'POST',
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				success: function(form, action){
					
				},
				failure: function(form, action) {
					//CNOA.msg.alert(action.result.msg, function(){
						
					//});
				}
			})
		};
		
		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelWidth: 90,
			labelAlign: 'top',
			waitMsgTarget: true,
			bodyStyle: "padding-top:20px;padding:10px;",
			items: [
				{
					xtype: "textarea",
					height: 147,
					width: 360,
					name: "say",
					hideLabel: true,
					value: "已阅"
				},
				{
					xtype: 'radiogroup',
					hideLabel: true,
					width : 200,
					allowBlank: false,
					name: "sayTGroup",
					items: [
						{boxLabel: '已阅', name: 'sayT', inputValue: "1", checked: true},
						{boxLabel: '同意', name: 'sayT', inputValue: "2"},
						{boxLabel: '不同意', name: 'sayT', inputValue: "3"}
					],
					listeners : {
						"change" : function(th, checked){
							if(checked.inputValue=="1"){
								formPanel.getForm().findField("say").setValue("已阅");
							}else if(checked.inputValue=="2"){
								formPanel.getForm().findField("say").setValue("同意");
							}else if(checked.inputValue=="3"){
								formPanel.getForm().findField("say").setValue("不同意");
							}
						}
					}
				}
			]
		});
		var win = new Ext.Window({
			width: 400,
			height: 300,
			title: "会签意见",
			resizable: false,
			modal: true,
			layout: "fit",
			items: formPanel,
			buttons : [
				//汇报
				{
					text : "确定",
					id: _this.ID_btn_report_window_report,
					iconCls: 'icon-order-s-accept',
					handler : function() {
						submitHuiQian();
					}.createDelegate(this)
				},
				//关闭
				{
					text : "取消",
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
		
		var submitHuiQian = function(){
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: _this.baseUrl+"&task=submitHuiQianInfo",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							try{CNOA_flow_flow_user_dealflow.store.reload();}catch(e){}
							_this.view_loadFlowInfo();
							_this.gridEvent.store.reload();
							win.close();
						}.createDelegate(this));
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}
		};

		loadHuiQianInfo();
	},
	
	goToDealFlowList : function(){
		//mainPanel.closeTab("CNOA_MENU_FLOW_USER_MYFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=dealflow", "CNOA_MENU_FLOW_DEALFLOW", "审批流程", "icon-flow-deal");
	},

	closeTab : function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_VIEWFLOW");
	},
	
	huiqian : function(){
		var _this = this;

		var ID_window = Ext.id();
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=setHuiQianInfo",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action){
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
						});
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		};
		var getHuiQianInfo = function(){
			var f = form.getForm();
			f.load({
				url: _this.baseUrl + "&task=getHuiQianInfo",
				params: {ulid: _this.ulid},
				method:'POST',
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				success: function(form, action){
					
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}
			})
		};
		var form = new Ext.form.FormPanel({
			border: false,
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			items: [
				{
					xtype: "displayfield",
					hideLabel: true,
					value: "步骤名称: " + _this.stepText,
					name: "stepText"
				},
				{
					xtype: "textarea",
					width: 483,
					height: 188,
					//anchor: "-10",
					readOnly: true,
					fieldLabel: '会签人员',
					name: "allUserNames"
				},
				{
					xtype: "hidden",
					name: "allUids"
				},
				{
					xtype: "buttonForSMS",
					autoWidth: true,
					dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					text: "选择人员",
					listeners: {
						"selected" : function(th, data){
							var names = new Array();
							var uids = new Array();
							if (data.length>0){
								for (var i=0;i<data.length;i++){
									names.push(data[i].uname);
									uids.push(data[i].uid);
								}
							}
							form.getForm().findField("allUserNames").setValue(names.join(","));
							form.getForm().findField("allUids").setValue(uids.join(","));
						},

						"onrender" : function(th){
							th.setSelectedUids(form.getForm().findField("allUids").getValue().split(","));
						},
						
						"afterrender" : function(){
							getHuiQianInfo();
						}
					}
				}
			]
		});
		var win = new Ext.Window({
			title: "设置本步骤会签人员",
			modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 360,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : "确定",
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : "取消",
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	zhuangban : function(){
		var _this = this;
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=setZhuangbanInfo",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action){
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
							_this.closeTab();
							try{
								CNOA_flow_flow_user_dealflow.store.reload();
							}catch(e){}
						});
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		};
		
		var form = new Ext.form.FormPanel({
			border: false,
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			items: [
				{
					xtype: "hidden",
					name: "uid"
				},
				{
					xtype: "triggerForPeople",
					fieldLabel:"选择转办人",
					allowBlank: false,
					name:"uname",
					dataUrl: _this.baseUrl+"&task=getAllUserListsInPermitDeptTree",
					width: 340,
					listeners: {
						"selected": function(th, uid, uname){
							form.getForm().findField("uid").setValue(uid);
						}
					}
				},
				{
					xtype : "textarea",
					width : 340,
					height: 200,
					fieldLabel : "转办意见",
					name : "say"
				}
			]
		});
		var win = new Ext.Window({
			title: "转办工作流",
			modal: true,
			maximizable: false,
			resizable: false,
			width: 375,
			height: 360,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : "确定",
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : "取消",
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	disagree : function(){
		var _this = this;

		var ID_window = Ext.id();

		//添加短信组件
		var smsSender = new Ext.form.SMSSender({
			from: "flow",
			modal: true,
			//fwindow: ID_window,
			hideLabel: true,
			boxLabel: '手机短信提醒'
		});
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=disagree",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action){
						//提交手机短信
						try{
							smsSender.submit();
						}catch(e){}

						CNOA.msg.alert(action.result.msg, function(){
							win.close();
							_this.closeTab();

							try{
								CNOA_flow_flow_user_dealflow.store.reload();
							}catch(e){}
						}.createDelegate(this));
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		}
		var form = new Ext.form.FormPanel({
			border: false,
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			items: [
				{
					xtype: "textarea",
					width: 483,
					height: 220,
					//allowBlank: false,
					name: "reason",
					fieldLabel: "不同意理由"
				},
				smsSender
			]
		});
		var win = new Ext.Window({
			title: "不同意(退件)",
			//modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 360,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : "确定",
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : "取消",
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	goto_prevstep : function(){
		var _this = this;

		var ID_window = Ext.id();

		//添加短信组件
		var smsSender = new Ext.form.SMSSender({
			from: "flow",
			modal: true,
			//fwindow: ID_window,
			hideLabel: true,
			boxLabel: '手机短信提醒'
		});
		
		var submit = function(){
			var f = form.getForm();
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl+"&task=gotoPrevstep",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					waitMsg: CNOA.lang.msgLoadMask,
					params: {ulid: _this.ulid},
					success: function(form, action) {
						//提交手机短信
						try{
							smsSender.submit();
						}catch(e){}

						CNOA.msg.alert(action.result.msg, function(){
							win.close();
							_this.closeTab();
							try{
								CNOA_flow_flow_user_dealflow.store.reload();
							}catch(e){}
						}.createDelegate(this));
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		}
		var form = new Ext.form.FormPanel({
			border: false,
			bodyStyle: "padding:10px;",
			labelAlign: "top",
			waitMsgTarget: true,
			items: [
				{
					xtype: "textarea",
					width: 483,
					height: 220,
					//allowBlank: false,
					name: "reason",
					fieldLabel: "退回理由"
				},
				smsSender
			]
		});
		var win = new Ext.Window({
			title: "退回上一步",
			//modal: true,
			maximizable: false,
			resizable: false,
			width: 520,
			height: 360,
			layout: "fit",
			items: [form],
			buttons: [
				{
					iconCls: 'icon-order-s-accept',
					text : "确定",
					handler : function(button, event) {
						submit();
					}.createDelegate(this)
				},
				{
					iconCls: 'icon-dialog-cancel',
					text : "取消",
					handler : function(button, event) {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	//获取下一步要发送的相关人员数据
	sendFormStep1: function(){
		var _this = this;
		
		//选择下一步经办人
		CNOA_flow_flow_user_dealcommonflow_goNextStep = new CNOA_flow_flow_user_dealcommonflow_goNextStepClass(_this.ulid);
		CNOA_flow_flow_user_dealcommonflow_goNextStep.show();
	},
	
	checkForm: function(){
		
		var e1 = this.formPanel.getEl().query("input[name^=FLOWDATA][class*=flow_form_must],textarea[name^=FLOWDATA][class*=flow_form_must],select[name^=FLOWDATA][class*=flow_form_must]");
		
		var t = true;
		Ext.each(e1, function(v, i){
			if(Ext.isEmpty(v.value)){
				t = false;
			}
		});

		return t;
	},
	
	//发送数据
	sendFormStep2: function(say, uids, callback){
		var _this = this;
		
		if(Ext.isArray(uids)){
			uids = uids.join(",");
		}

		Ext.Ajax.request({  
			url: _this.baseUrl+"&task=deal_sendFlow",
			method: 'POST',
			params: {ulid: _this.ulid, nextUids: uids, say: say},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					try {
						callback.call(this);
					} catch (e) {}
					
					CNOA.msg.alert(result.msg, function(){
						_this.goToDealFlowList();
						_this.closeTab();
						
						try{
							CNOA_flow_flow_user_dealflow.store.reload();
						}catch(e){}
					}.createDelegate(this));
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},
	
	exportFlow : function(target){
		var _this = this;

		var ID_filedownload = Ext.id();
		var exportArray = Ext.encode(this.exportArray);

		var loadFormData = function(){
			form.getForm().submit({
				url: _this.baseUrl + "&task=exportFlow&target="+target,
				params: { ulid: _this.ulid, exportArray: exportArray },
				method:'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				success: function(form, action){
					var e = Ext.getCmp(ID_filedownload);
					e.getEl().update("请点击下载：<br/>"+makeDownLoadIcon2(action.result.msg, "img"));
				}.createDelegate(this),
				failure: function(form, action) {
					win.close();
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});
		}

		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender : function(th){
					loadFormData();
				}
			},
			items: [
				{
					xtype: "displayfield",
					name: "name",
					fieldLabel: '流程名称'
				},
				new Ext.BoxComponent({
					id: ID_filedownload,
					autoEl: {
						tag: 'div',
						style: 'padding-left:16px',
						html: ''
					}
				})
			]
		});
		var win = new Ext.Window({
			title: "导出工作流程",
			width: 320,
			height: 150,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	printFlow : function(){
		var exportArray = Ext.encode(this.exportArray);
		window.open(this.baseUrl + "&task=exportFlow&target=printer&ulid="+this.ulid+"&exportArray="+exportArray, '_blank', 'width=740,height='+(screen.availHeight-120)+',left='+((screen.availWidth-740)/2)+',top=60,scrollbars=yes,resizable=yes,status=no');
	}
}


var CNOA_flow_flow_dispense_listClass, CNOA_flow_flow_dispense_list;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_dispense_listClass = CNOA.Class.create();
CNOA_flow_flow_dispense_listClass.prototype = {
	init : function(){
		var _this = this;
		
		this.edit_id = 0;
		this.sort = 0;
		this.isread = CNOA.flow.flow.dispense_list.isread;
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		
		//查询
		this.ID_find_name		= Ext.id();
		this.ID_find_title		= Ext.id();
		this.ID_find_beginTime	= Ext.id();
		this.ID_find_endTime	= Ext.id(); 
		this.ID_find_status		= Ext.id();
		
		//查询参数
		this.searchParams = {
			name: '',
			title: '',
			beginTime: '',
			endTime: '',
			status: 0
		};
		
		this.fields = [
			{name:"lid"},
			{name:"ulid"},
			{name:"name"},
			{name:"flowname"},
			{name:"title"},
			{name:"level"},
			{name:"step"},
			{name:"posttime"},
			{name:"status"},
			{name:"flowtype"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getDispenseJsonData&isread="+this.isread, disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//sm,new Ext.grid.RowNumberer({header:"编号",width:50}),
			new Ext.grid.RowNumberer(),
			//this.sm,
			{header: "ulid", dataIndex: 'ulid', hidden: true},
			{header: '流程编号', dataIndex: 'name', width: 180, sortable: true, menuDisabled :true},
			{header: '所属流程', dataIndex: 'flowname', width: 120, sortable: true, menuDisabled :true},
			{header: '标题', dataIndex: 'title', width: 120, sortable: true, menuDisabled :true},
			{header: "重要等级", dataIndex: 'level', width: 60, sortable: false,menuDisabled :true},
			{header: "当前步骤", dataIndex: 'step', width: 70, sortable: false,menuDisabled :true},
			{header: "状态", dataIndex: 'status', width: 50, sortable: false,menuDisabled :true, renderer:this.makeStatus.createDelegate(this)},
			{header: "发起日期", dataIndex: 'posttime', width: 110, sortable: false,menuDisabled :true},
			{header: "操作", dataIndex: 'ulid', width: 160, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			emptyMsg:"没有数据显示",
			displayMsg:"显示第{0}--{1}条数据，共{2}条",   
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){
					params.sort = _this.sort;
					
					//查询参数
					if(_this.searchParams.name != ''){
						params.name = _this.searchParams.name;
					}
					if(_this.searchParams.title != ''){
						params.title = _this.searchParams.title;
					}
					if(_this.searchParams.beginTime != ''){
						params.beginTime = _this.searchParams.beginTime;
					}
					if(_this.searchParams.endTime != ''){
						params.endTime = _this.searchParams.endTime;
					}
					if(_this.searchParams.status != 0){
						params.status = _this.searchParams.status;
					}
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			cm: this.colModel,
			//sm: this.sm,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				//forceFit: true
			},
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					
				},  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex, e){
					//var record = grid.getStore().getAt(rowIndex);
					//_this.view(record.data.lid);
				}
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : CNOA.lang.mainJob.refreshList,
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 161}
				]
			}),
			bbar: this.pagingBar
		});
		
	//查询工具条
		this.gridCt = new Ext.Panel({
			border: false,
			layout: 'fit',
			items: [this.grid],
			region: 'center',
			listeners: {
				afterrender: function(){
					new Ext.Toolbar({
						items: [
							'发起开始时间：',
							{
								xtype: "datefield",
								width: 170,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_beginTime
							},
							' 发起最后时间：',
							{
								xtype: "datefield",
								width: 170,
								format: "Y-m-d",
								editable: false,
								allowBlank: true,
								id: _this.ID_find_endTime
							},
							'-',
							{
								xtype: 'button',
								text: '查询',
								cls: 'x-btn-over',
								listeners: {
									'mouseout': function(btn){
										btn.addClass('x-btn-over');
									}
								},
								handler: function(){
									_this.searchParams = {
										name: Ext.getCmp(_this.ID_find_name).getValue(),
										title: Ext.getCmp(_this.ID_find_title).getValue(),
										beginTime: Ext.getCmp(_this.ID_find_beginTime).getRawValue(),
										endTime: Ext.getCmp(_this.ID_find_endTime).getRawValue(),
										status: Ext.getCmp(_this.ID_find_status).getValue()										
									};
									_this.store.load({params:_this.searchParams});
								}
							},'-',
							{
								xtype: 'button',
								text: '清空',
								handler: function(){
									Ext.getCmp(_this.ID_find_name).setValue('');
									Ext.getCmp(_this.ID_find_title).setValue('');
									Ext.getCmp(_this.ID_find_beginTime).setRawValue('');
									Ext.getCmp(_this.ID_find_endTime).setRawValue('');
									Ext.getCmp(_this.ID_find_status).setValue(-99);
									_this.searchParams = {
										name: '',
										title: '',
										beginTime: 0,
										endTime: 0,
										status: 0
									};
									_this.store.load();
								}
							}
						]
					}).render(this.tbar);
				}
			},
			tbar: new Ext.Toolbar({
				items: [					
					'标题：',
					{
						xtype: 'textfield',
						width: 200,
						id: _this.ID_find_title
					},
					' 编号名称',
					{
						xtype: 'textfield',
						width: 150,
						id: _this.ID_find_name
					},
					'当前状态：',
					{
						xtype: 'combo',
						id: _this.ID_find_status,
						editable: false,
						mode: 'local',
						triggerAction: 'all',
						valueField: 'value',
						displayField: 'display',
						store: new Ext.data.ArrayStore({
							fields: ['value', 'display'],
							data: [[-99, ''], [-1, '已删除'], [0, '未发布'], [1, '办理中'], [2, '已办理'], [3, '已退件'], [4, '已撤销']]
						}),
						value: -99
					}
				]				
			})		
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.gridCt]
		});
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>未发送</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>办理中</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>已办理</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>已退件</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_gray'>已撤销</span>";}
		return h;
	},

	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;
		
		//-1已删除 0:未发布 1:办理中 2已办理 3已退件 4已撤销
		
		var h  = "<a href='javascript:void(0);' onclick='CNOA_flow_flow_dispense_list.showFlow("+value+", "+rd.flowtype+");'>查看</a>";
		return h;
	},
	
	//查看流程(操作)
	showFlow : function(ulid, flowtype){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_VIEWFLOW");
		if(flowtype == 0){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=showdispenseflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_VIEWFLOW", "查看工作流程", "icon-flow");
		}else if(flowtype == 1){
			mainPanel.loadClass("index.php?app=flow&func=flow&action=user&module=commonFlow&task=loadPage&from=showdispenseflow&ulid="+ulid, "CNOA_MENU_FLOW_USER_VIEWFLOW", "查看工作流程", "icon-flow");
		}
		
	}
};


var CNOA_flow_flow_dispense_viewClass, CNOA_flow_flow_dispense_view;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_dispense_viewClass = CNOA.Class.create();
CNOA_flow_flow_dispense_viewClass.prototype = {
	init : function(){
		var _this = this;

		this.ulid = CNOA.flow.flow.dispense_view.ulid;
		this.ID_goto_nextstep = Ext.id();
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.flowPanelLoaded = this.formPanelLoaded = this.progressPanelLoaded = this.eventPanelLoaded = false;

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		this.ID_printArea = Ext.id();

		this.topTpl = new Ext.XTemplate(
			'<table width="100%" border="0" cellspacing="1" cellpadding="0" style="background-color:#CDCDCD">',
			'  <tr>',
			'    <td width="15%" height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程编号:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{name}</td>',
			'    <td width="15%" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程名称:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;[{flowname}]{title}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起人:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{uname}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起日期:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{posttime}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">当前步骤:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{stepText}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程状态:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{statusText}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">申请事由:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{reason}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">特殊备注:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{about}</td>',
			'  </tr>',
			'  <tpl if="attachCount &gt; 0">',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程附件:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;',
			'		<tpl for="attach">',
			'		<a href="#" id="user_flow_atach_{id}" class="attach_down"><img src="'+Ext.BLANK_IMAGE_URL+'" onload="makeFlowAttacBtn(\'user_flow_atach_{id}\', \'{url}\', \'{name}\', \'{size}\');"></a>',
			'		</tpl>',
			'    </td>',
			'  </tr>',
			'  </tpl>',
			'</table>'
		);
		
		/*
		 * 事件信息
		 */
		this.flowPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			style: "margin-bottom:10px;",
			title: "流程信息",
			listeners : {
				afterrender : function(p){
					_this.flowPanelLoaded = true;
					_this.view_loadFlowInfo();
				}
			}
		});
		
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
		
		/*
		 * 事件进度
		 */
		this.gridProgress = new CNOA_flow_flow_flowViewProgressClass(this.ulid);
		this.progressPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "流程进度",
			layout: "fit",
			items: [this.gridProgress.grid],
			listeners : {
				afterrender : function(p){
					var data = {};
					//_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		/*
		 * 事件日志
		 */
		this.gridEvent = new CNOA_flow_flow_flowViewEventClass(this.ulid);
		this.eventPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			layout: "fit",
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "事件日志",
			items: [this.gridEvent.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					///_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		/*
		 * 评阅记录
		 */
		this.gridReader = new CNOA_flow_flow_flowViewReaderClass(this.ulid);
		this.readerPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			layout: "fit",
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "评阅记录",
			items: [this.gridReader.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					///_this.topTpl.overwrite(p.body, data);
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
					items: [this.flowPanel, this.formPanel, this.progressPanel, this.eventPanel, this.readerPanel]
				}
			]
		});
		
		this.exportArray = {i:1,f:1,p:1,e:1,r:1};
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel],
			tbar : new Ext.Toolbar({
				items: [
					'显示：',
					{
						xtype: 'checkbox',
						boxLabel: '流程信息',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.i = 1;
									_this.flowPanel.show();
								}else{
									_this.exportArray.i = 0;
									_this.flowPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '工作表单',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.f = 1;
									_this.formPanel.show();
								}else{
									_this.exportArray.f = 0;
									_this.formPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '流程进度',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.p = 1;
									_this.progressPanel.show();
								}else{
									_this.exportArray.p = 0;
									_this.progressPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '事件日志',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.e = 1;
									_this.eventPanel.show();
								}else{
									_this.exportArray.e = 0;
									_this.eventPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '评阅记录',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.r = 1;
									_this.readerPanel.show();
								}else{
									_this.exportArray.r = 0;
									_this.readerPanel.hide();
								}
							}
						}
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
					},'-',
					{
						//id: this.ID_btn_refreshList,
						handler : function(button, event) {
							_this.gridReader.showSay();
						}.createDelegate(this),
						hidden: Ext.isAir ? true : false,
						iconCls: 'icon-view-form-png',
						text : "我要评阅"
					},'-',{
						iconCls: 'icon-dialog-cancel',
						text : "关闭",
						handler : function(button, event) {
							alert(11);
							_this.closeTab();
						}.createDelegate(this)
					},
					"->",
					{xtype: 'cnoa_helpBtn', helpid: 162}
				]
			})
		});
	},
	
	view_loadFlowInfo : function(){
		var _this = this;
		
		if(!this.flowPanelLoaded || !this.formPanelLoaded){
			return false;
		}

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=show_loadDispenseFlowInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					//基本信息
					_this.topTpl.overwrite(_this.flowPanel.body, result.data.flowInfo);
					
					//工作表单
					_this.formPanel.body.update(result.data.formInfo);
					
					/*
					//工作表单数据
					var elements = _this.formPanel.getEl().query("input[name^=DATA_],textarea[name^=DATA_],select[name^=DATA_]");
					Ext.each(elements, function(v, i){
						Ext.each(result.data.formData, function(vv, ii){
							if(("DATA_"+vv.itemid) == v.name){
								//v.value = vv.data;
							}
						});
						
						v.readOnly = true;
						v.style.border = '1px solid #E1E1E1';
						v.style.backgroundColor = '#F6F6F6';
						
						v.title = "";
					});
					*/
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},

	closeTab : function(){alert("CNOA_MENU_FLOW_USER_VIEWFLOW");
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_VIEWFLOW");
	},
	
	printFlow : function(){
		var exportArray = Ext.encode(this.exportArray);
		window.open(this.baseUrl + "&task=exportFlow&target=printer&ulid="+this.ulid+"&exportArray="+exportArray, '_blank', 'width=740,height='+(screen.availHeight-120)+',left='+((screen.availWidth-740)/2)+',top=60,scrollbars=yes,resizable=yes,status=no');
	}
}

var CNOA_flow_flow_dispense_view_commonflowClass, CNOA_flow_flow_dispense_view_commonflow;

/**
* 主面板-列表
*
*/
CNOA_flow_flow_dispense_view_commonflowClass = CNOA.Class.create();
CNOA_flow_flow_dispense_view_commonflowClass.prototype = {
	init : function(){
		var _this = this;

		this.ulid = CNOA.flow.flow.dispense_view_commonflow.ulid;
		this.ID_goto_nextstep = Ext.id();
		
		this.baseUrl = "index.php?app=flow&func=flow&action=user";
		
		this.flowPanelLoaded = this.progressPanelLoaded = this.eventPanelLoaded = false;

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();
		this.ID_printArea = Ext.id();

		this.topTpl = new Ext.XTemplate(
			'<table width="100%" border="0" cellspacing="1" cellpadding="0" style="background-color:#CDCDCD">',
			'  <tr>',
			'    <td width="15%" height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程编号:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{name}</td>',
			'    <td width="15%" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程名称:&nbsp;</td>',
			'    <td width="35%" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;[{flowname}]{title}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起人:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{uname}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">发起日期:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{posttime}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">当前步骤:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{stepText}</td>',
			'    <td align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程状态:&nbsp;</td>',
			'    <td valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{statusText}</td>',
			'  </tr>',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">申请事由:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;{reason}</td>',
			'  </tr>',
			'  <tpl if="attachCount &gt; 0">',
			'  <tr>',
			'    <td height="24" align="right" valign="middle" class="cnoa_bgc_F6F6F6 cnoa_font_bold">流程附件:&nbsp;</td>',
			'    <td colspan="3" valign="middle" class="cnoa_bgc_FFFFFF">&nbsp;',
			'		<tpl for="attach">',
			'		<a href="#" id="user_flow_atach_{id}" class="attach_down"><img src="'+Ext.BLANK_IMAGE_URL+'" onload="makeFlowAttacBtn(\'user_flow_atach_{id}\', \'{url}\', \'{name}\', \'{size}\');"></a>',
			'		</tpl>',
			'    </td>',
			'  </tr>',
			'  </tpl>',
			'</table>'
		);
		
		/*
		 * 事件信息
		 */
		this.flowPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			style: "margin-bottom:10px;",
			title: "流程信息",
			listeners : {
				afterrender : function(p){
					_this.flowPanelLoaded = true;
					_this.view_loadFlowInfo();
				}
			}
		});
		
		/*
		 * 事件进度
		 */
		this.gridProgress = new CNOA_flow_flow_flowViewProgressClass(this.ulid, true, false);
		this.progressPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "流程进度",
			layout: "fit",
			items: [this.gridProgress.grid],
			listeners : {
				afterrender : function(p){
					var data = {};
					//_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		/*
		 * 事件日志
		 */
		this.gridEvent = new CNOA_flow_flow_flowViewEventClass(this.ulid);
		this.eventPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			style: "margin-bottom:10px;",
			border: false,
			frame: true,
			layout: "fit",
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "事件日志",
			items: [this.gridEvent.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					///_this.topTpl.overwrite(p.body, data);
				}
			}
		});
		
		/*
		 * 评阅记录
		 */
		this.gridReader = new CNOA_flow_flow_flowViewReaderClass(this.ulid);
		this.readerPanel = new Ext.Panel({
			headerStyle: "border-width:0",
			border: false,
			frame: true,
			layout: "fit",
			animCollapse: false,
			collapsible: true,
			titleCollapse: true,
			title: "评阅记录",
			items: [this.gridReader.grid],
			listeners : {
				afterrender : function(p){
					//var data = {};
					///_this.topTpl.overwrite(p.body, data);
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
					items: [this.flowPanel, this.progressPanel, this.eventPanel, this.readerPanel]
				}
			]
		});
		
		this.exportArray = {i:1,p:1,e:1,r:1};
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel],
			tbar : new Ext.Toolbar({
				items: [
					'显示：',
					{
						xtype: 'checkbox',
						boxLabel: '流程信息',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.i = 1;
									_this.flowPanel.show();
								}else{
									_this.exportArray.i = 0;
									_this.flowPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '流程进度',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.p = 1;
									_this.progressPanel.show();
								}else{
									_this.exportArray.p = 0;
									_this.progressPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '事件日志',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.e = 1;
									_this.eventPanel.show();
								}else{
									_this.exportArray.e = 0;
									_this.eventPanel.hide();
								}
							}
						}
					},'-',
					{
						xtype: 'checkbox',
						boxLabel: '评阅记录',
						checked: true,
						listeners: {
							check : function(th, checked){
								if(checked){
									_this.exportArray.r = 1;
									_this.readerPanel.show();
								}else{
									_this.exportArray.r = 0;
									_this.readerPanel.hide();
								}
							}
						}
					},'-',
					{
						text: '导出',
						iconCls: 'icon-download',
						tooltip: "导出为HTML文件",
						menu : {
							items: [
								{
									iconCls:'document-html',
									text:'导出为网页文件(html)',
									handler: function(){
										_this.exportFlow('html');
									}.createDelegate(this)
								},
								{
									iconCls:'document-word',
									text:'导出为Word文档',
									handler: function(){
										_this.exportFlow('word');
									}.createDelegate(this)
								}
							]
						}
					},'-',
					{
						//id: this.ID_btn_refreshList,
						handler : function(button, event) {
							_this.gridReader.showSay();
						}.createDelegate(this),
						hidden: Ext.isAir ? true : false,
						iconCls: 'icon-view-form-png',
						text : "我要评阅"
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
					},'-',{
						iconCls: 'icon-dialog-cancel',
						text : "关闭",
						handler : function(button, event) {
							_this.closeTab();
						}.createDelegate(this)
					},
					"->",
					{xtype: 'cnoa_helpBtn', helpid: 19}
				]
			})
		});
	},
	
	view_loadFlowInfo : function(){
		var _this = this;
		
		if(!this.flowPanelLoaded){
			return false;
		}

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=show_loadFlowInfo",
			method: 'POST',
			params: {ulid: _this.ulid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					//基本信息
					_this.topTpl.overwrite(_this.flowPanel.body, result.data.flowInfo);
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},

	closeTab : function(){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_SHOWFLOW");
	},
	
	exportFlow : function(target){
		var _this = this;

		var ID_filedownload = Ext.id();
		var exportArray = Ext.encode(this.exportArray);

		var loadFormData = function(){
			form.getForm().submit({
				url: _this.baseUrl + "&task=exportFlow&target="+target,
				params: { ulid: _this.ulid, exportArray: exportArray },
				method:'POST',
				waitMsg: CNOA.lang.msgLoadMask,
				success: function(form, action){
					var e = Ext.getCmp(ID_filedownload);
					e.getEl().update("请点击下载：<br/>"+makeDownLoadIcon2(action.result.msg, "img"));
				}.createDelegate(this),
				failure: function(form, action) {
					win.close();
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});
		}

		var form = new Ext.form.FormPanel({
			autoWidth: true,
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			bodyStyle: "padding:10px;",
			listeners: {
				afterrender : function(th){
					loadFormData();
				}
			},
			items: [
				{
					xtype: "displayfield",
					name: "name",
					fieldLabel: '流程名称'
				},
				new Ext.BoxComponent({
					id: ID_filedownload,
					autoEl: {
						tag: 'div',
						style: 'padding-left:16px',
						html: ''
					}
				})
			]
		});
		var win = new Ext.Window({
			title: "导出工作流程",
			width: 320,
			height: 150,
			layout: "fit",
			modal: true,
			maximizable: false,
			resizable: false,
			items: [form],
			buttons: [
				{
					text: CNOA.lang.btnClose,
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	printFlow : function(){
		var exportArray = Ext.encode(this.exportArray);
		window.open(this.baseUrl + "&task=exportFlow&target=printer&ulid="+this.ulid+"&exportArray="+exportArray, '_blank', 'width=740,height='+(screen.availHeight-120)+',left='+((screen.availWidth-740)/2)+',top=60,scrollbars=yes,resizable=yes,status=no');
	},
	
	readedFlow : function(){
		CNOA_flow_flow_view_reader = new CNOA_flow_flow_view_readerClass(this.ulid);
		CNOA_flow_flow_view_reader.show();
	}
}




var sm_flow_flow=1;