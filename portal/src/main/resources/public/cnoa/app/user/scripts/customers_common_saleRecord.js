//销售记录
var CNOA_user_customers_recordListClass, CNOA_user_customers_recordAddEditClass, CNOA_user_customers_recordViewClass;
var CNOA_user_customers_recordAddEdit, CNOA_user_customers_recordView;

/**
 * 查看销售记录
 */
CNOA_user_customers_recordListClass = CNOA.Class.create();
CNOA_user_customers_recordListClass.prototype = {
	init: function(cid, from){
		var _this = this,
			search = {};
		var ID_sale_Record = Ext.id();
		this.baseUrl = "index.php?app=user&func=customers&action=index&module=salesRecord";
		var fields = [
			{name: "rid"},
			{name: "cname"},
			{name: "lname"},
			{name: "gname"},
			{name: "gdname"},
			{name: "price"},
			{name: "dealprice"},
			{name: "num"},
			{name: "amount"},
			{name: "date"},
			{name: "status"},
			{name: "checkdate"},
			{name: "checkname"},
			{name: "addname"},
			{name: "fahuocheckman"},
			{name: "fahuocheckdate"},
			{name: "fahuocontent"}
		],

		store = new Ext.data.Store({
			autoLoad: !cid,
			//baseParams: {stime:(new Date().format('Y-m'))+'-1', etime:new Date().format('Y-m-t')}, //默认为当月
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getSalesRecordList&cid="+cid, disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
			listeners: {
				'load': function(store, records){
					var saleroom = store.reader.jsonData.saleroom;
					tv_saleroom.update('<b style="color:red">' + lang('totalSales') + '：'+ saleroom + '</b>');
				}
			}
		});
		
		var sm = new Ext.grid.CheckboxSelectionModel({}),
		
		formatCell = function(fVal, sVal){
			if(!sVal) sVal = '----';
			if(!fVal) fVal = '----';
			return "<span>"+fVal+"</span><br /><span>"+sVal+"</span>";
		},
		gdname = function(value, cellmeta, record){
			var gname = record.data.gname == "gd" ? "<span style='color:red;'>" + lang('goods') + "</span>" : "<span style='color:#800000;'>" + lang('server') + "</span>";
			return formatCell(value, gname);
		},
		status = function(value){
			return value == "yes" ? "<span style='color:red;'>" + lang('confirmed') + "</span>" : "<span style='color:green;'>" + lang('WQR') + "</span>";
		},
		price = function(value, cellmeta, record){
			return formatCell(value, record.data.dealprice);
		},
		amount = function(value, cellmeta, record){
			return formatCell(value, record.data.date);
		},
		checkname = function(value, cellmeta, record){
			return formatCell(value, record.data.checkdate);
		},
		fahuocheckman = function(value, cellmeta, record){
			return formatCell(value, record.data.fahuocheckdate);
		},
		makeOperate = function(value, cellmeta, record){
			var rd = record.data;
			return "<a href='javascript:void(0);' onclick='CNOA_user_customers_"+from+"RecordList.viewRecord("+rd.rid+");'  class='gridview'>" + lang('view') + "</a>";
//			 / <a href='javascript:void(0);' onclick='CNOA_user_customers_recordList.viewExcel("+rd.rid+");'>缺货信息</a>
		},
		
		colModel = new Ext.grid.ColumnModel({
			defaults: {
				width: 100,
				sortable: false,
				menuDisabled :true
			},
			columns:[
				new Ext.grid.RowNumberer(),
				sm,
				{header: "rid", dataIndex: 'rid', hidden: true},
				{header: lang('productName1') + "/" + lang('goodsType'), dataIndex: 'gdname', width:120, renderer: gdname},
				{header: lang('belongCustomer'), dataIndex: 'cname'},
				{header: lang('linkMan'), dataIndex: 'lname'},
				{header: lang('unitPrice') + "/" + lang('cJdiscount'), dataIndex: 'price', renderer: price},
				{header: lang('quantity'), dataIndex: 'num'},
				{header: lang('totalSales') + "/" + lang('saleDate'), dataIndex: 'amount', width: 120, renderer: amount},
				{header: lang('auditStatus'), dataIndex: 'status', renderer: status},
				{header: lang('cWAuditPeople'), dataIndex: 'checkname', renderer: checkname},
				{header: lang('fahuoReviewer'), dataIndex: 'fahuocheckman', renderer: fahuocheckman},
				{header: lang('addPeople1'), dataIndex: 'addname'},
				{header: lang('opt'), dataIndex: 'noIndex', width: 120, renderer: makeOperate}
			]
		}),

		pagingBar = new Ext.PagingToolbar({
            plugins: [new Ext.grid.plugins.ComboPageSize()],
            style: "border-left-width:1px;",
            displayInfo: true, emptyMsg:lang('pagingToolbarEmptyMsg'), displayMsg:lang('showDataTotal2'),  
            store: store,
            pageSize: 15
        }),
		
		s_time = new Ext.TimeInterval({style:'margin-left:20px;',showMode: 'null'}),
		tv_saleroom = new Ext.form.DisplayField({style:'margin-left:20px;', html:''});

		this.grid = new Ext.grid.GridPanel({
			store: store,
			loadMask : {msg: lang('waiting')},
			cm: colModel,
			sm: sm,
			bbar: pagingBar,
			border: false,
			tbar : new Ext.Toolbar({
				items: [
					{
						text : lang('refresh'),
						iconCls: 'icon-system-refresh',
						handler : function(button, event) {
							store.reload();
						}
					},
					{
						text : lang('add'),
						iconCls: 'icon-utils-s-add',
						id: ID_sale_Record,
						handler : function(button, event) {
							CNOA_user_customers_recordAddEdit = new CNOA_user_customers_recordAddEditClass('add', 0, cid);
							CNOA_user_customers_recordAddEdit.show();
//							//使用添加流程
//							CNOA.msg.alert(lang('unBindingEngine'));
						}
					},{
						text : lang('modify'),
						iconCls: 'icon-utils-s-edit',
						id: this.ID_btn_edit_RD,
						handler : function(button, event) {
							var rows = _this.grid.getSelectionModel().getSelections();
							if (rows.length == 0) {
								CNOA.miniMsg.alertShowAt(button, lang('mustSelectOneRow'));
							} else {
								var rid = rows[0].get("rid");
								_this.editRecord(rid);
							}
						}
					},{
						text : lang('del'),
						iconCls: 'icon-utils-s-delete',
						handler : function(button, event) {
							var rows = _this.grid.getSelectionModel().getSelections();
							if (rows.length == 0) {
								CNOA.miniMsg.alertShowAt(button, lang('mustSelectOneRow'));
							} else {
								CNOA.msg.cf(lang('confirmToDelete'), function(btn) {
									if (btn == 'yes') {
										_this.deleteRecord(rows[0].get("rid"));
									}
								});
							}
						}
					},
					tv_saleroom
				]
			}),
			listeners:{
				'render': function(){
					getFlowId4Engine('saleRecord', function(send,view){
						Ext.getCmp(ID_sale_Record).setHandler(function(){
							send();
						});
					});
				}
			}
		});

		var search = {},
			tf_linkman = new Ext.form.TextField({width: 100}),
			tf_gdname = new Ext.form.TextField({width: 100}),
			tf_cname = new Ext.form.TextField({width: 100});
		
		searchBar = new Ext.Toolbar({
			items: [
				lang('productName1') + ':',
				tf_gdname,
				lang('belongCustomer') + ':',
				tf_cname,
				lang('linkMan') + ':',
				tf_linkman,
				s_time,{
					text: lang('search'),
					style: 'margin: 3px 5px;',
					handler: function(){
						search = {
							linkman: tf_linkman.getValue(),
							gdname: tf_gdname.getValue(),
							cname: tf_cname.getValue(),
							stime: s_time.getSTime(),
							etime: s_time.getETime()
						}
						store.reload({params:search});
					}
				},{
					text: lang('clear'),
					handler: function(){
						tf_linkman.setValue("");
						tf_gdname.setValue("");
						tf_cname.setValue("");
						search = {};
						s_time.clearValue();
						store.reload({params:search});
					}
				}
			]
		});

		this.mainPanel = new Ext.Panel({
			plain: false,
			hideBorders: true,
			layout: "fit",
			border: false,
			items: _this.grid,
			tbar: searchBar
		});
	},
	viewExcel : function(rid){
		var _this = this;
		var html = "";
		var win = new Ext.Window({
			width: 800,
			height: 300,
			autoScroll: false,
			modal: true,
			resizable: true,
			html : '<div style="width:100%;height:100%;"><iframe scrolling=auto frameborder=0 width=100% height=100% id="CNOA_USER_CUSTOMERS_FAHUO"></iframe></div>',
			title: lang('importQHXX'),
			listeners : {
				show : function(th){
					var ifr = Ext.getDom("CNOA_USER_CUSTOMERS_FAHUO");
					var ifrdoc = ifr.contentWindow.document;
				    ifrdoc.open();
				    ifrdoc.write("<BODY>");
				    ifrdoc.write(urldecode(html));
				    ifrdoc.write("</BODY>");
				    ifrdoc.close();
				}
			},
			buttons: [
				{
					text : lang('close'),
					handler : function(){
						win.close();
					}
				}
			]
		});
		Ext.Ajax.request({
			url: this.baseUrl + "&task=getFahuoContent",
			method: 'POST',
			params: {rid: rid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					html = result.msg;
					if(html == ""){
						html = lang('notAddQHXX');
					}
					win.show();
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	},
	
	//修改销售记录
	editRecord : function(rid){
		CNOA_user_customers_recordAddEdit = new CNOA_user_customers_recordAddEditClass('edit', rid, this.cid);
		CNOA_user_customers_recordAddEdit.show();
	},
	
	//删除销售记录
	deleteRecord : function(rid){
		var me = this;
		Ext.Ajax.request({
			url: this.baseUrl + "&task=deleteSalesRecord",
			method: 'POST',
			params: {rid: rid},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(result.msg, lang('customerMgr'));
					me.grid.store.reload();
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	},
	
	viewRecord : function(rid){
		mainPanel.closeTab("CNOA_MENU_USER_CUSTOMERS_VIEW_RECORD");
		mainPanel.loadClass(this.baseUrl + "&task=loadPage&from=recordView&rid="+rid, "CNOA_MENU_USER_CUSTOMERS_VIEW_RECORD", lang('viewSalesRecord'), "icon-page-view");
	}
}

/**
 * 添加/修改销售记录
 */
CNOA_user_customers_recordAddEditClass = CNOA.Class.create();
CNOA_user_customers_recordAddEditClass.prototype = {
	init : function(tp, rid, cid){
		var _this = this,
			title = tp == "edit" ? "销售记录" : "添加销售记录"
		
		this.baseUrl = "index.php?app=user&func=customers&action=index&module=salesRecord";
		this.action = tp == "edit" ? "edit" : "add";
		this.rid = rid;
		
		//添加短信组件
		this.smsSender = new Ext.form.SMSSender({
			from: "customers",
			fieldLabel: lang('smsNotice')
		});
		
		//客户ID
		this.cid = cid;

		this.ID_formPanel_goods = Ext.id();
		this.ID_formPanel_service = Ext.id();
		this.ID_FAHUOCONTENT_VALUE = Ext.id();
		this.ID_STOCKOUT_VIEWER = Ext.id();
		
		
		/* 客户信息START */
		var cidStore = new Ext.data.Store({
			autoLoad: true,
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getCustomerName&cid="+this.cid, disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: [{name:"cid"},{name:"name"}]})
		}),
		
		fahuoStore = new Ext.data.Store({
			autoLoad: true,
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getFahuoType", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: [{name:"id"},{name:"name"}]})
		});
		
		linkmanStore = new Ext.data.Store({
			autoLoad: !!parseInt(cid),
			baseParams: {cid: cid},
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getLinkman", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: [{name:"lid"},{name:"linkman", mapping: "name"}]})
		});
		
		var ID_checkUid = Ext.id(),
			tf_config = {width: 143,reacOnly: true,allowBlank: false,enableKeyEvents: true,listeners: {
				'keyup' : function(th, e){
					var num = parseFloat(tf_num.getValue()),
						price = parseFloat(tf_price.getValue()),
						dealprice = parseFloat(tf_dealprice.getValue());
					if(num && price && dealprice){
						var total = num*price*dealprice;
						if(parseFloat(total) == total){
							total = Math.round(total * 1000000) / 1000000;    
						}
						tf_amount.setValue(total);
					}
				}
			}},
			tf_num = new Ext.form.NumberField(Ext.apply({
				name: "num",
				fieldLabel: lang('quantity')
			}, tf_config)),
			tf_price = new Ext.form.NumberField(Ext.apply({
				name: "price",
				fieldLabel: lang('unitPrice')
			}, tf_config)),
			tf_dealprice = new Ext.form.NumberField(Ext.apply({
				name: "dealprice",
				fieldLabel: lang('cJdiscount')
			}, tf_config)),
			tf_amount = new Ext.form.TextField({
				width: 143,
				name: "amount",
				fieldLabel: lang('totalTurnover'),
				regex: /^([0-9]{1,}\.[0-9]{1,}|[0-9]{1,})$/i,
				enableKeyEvents: true,
				listeners: {
					'keyup' : function(th, e){
						var num = parseFloat(tf_num.getValue()),
							price = parseFloat(tf_price.getValue());
						if(num && price){
							var total = th.getValue();
							total = parseFloat(total.split(',').join('')); //防止输入逗号
							dealprice = parseFloat(total/(num*price));
							tf_dealprice.setValue(dealprice);
						}
					}
				}
			});
		var baseField = [
			{
				xtype: "fieldset",
				title: lang('clientXinxi'),
				width: 528,
				items: [
					{
						xtype:"combo",
						fieldLabel: lang('belongCustomer'),
						name: 'cid',
						store: cidStore,
						hiddenName: 'cid',
						valueField: 'cid',
						displayField: 'name',
						mode: 'local',
						allowBlank: false,
						width: 230,
						triggerAction: 'all',
						forceSelection: true,
						editable: false,
						value: "",
						listeners: {
							change : function(th, newV, oldV){
								linkmanStore.load({params:{cid:newV}});
							},
							select : function(th, record, index){
								_this.formPanel.getForm().findField("lid").setValue('');
							}
						}
					},
					{
						xtype:"combo",
						fieldLabel: lang('linkMan'),
						name: 'lid',
						store: linkmanStore,
						hiddenName: 'lid',
						valueField: 'lid',
						displayField: 'linkman',
						mode: 'local',
						allowBlank: false,
						width: 230,
						triggerAction: 'all',
						forceSelection: true,
						editable: false
					}
				]
			},
			{
				xtype: "fieldset",
				title: lang('goodsXinxi'),
				width: 528,
				items: [
					{
						xtype: 'radiogroup',
						fieldLabel: lang('goodsType'),
						width: 170,
						items: [
							{boxLabel: lang('goods'), name: 'gname', inputValue: "gd", checked: true},
							{boxLabel: lang('server'), name: 'gname', inputValue: "sv"}
						],
						listeners: {
							change : function(th, check){
								var g = Ext.getCmp(_this.ID_formPanel_goods);
								var s = Ext.getCmp(_this.ID_formPanel_service);
								
								if(th.getValue() == "gd"){
									g.show();
									s.hide();
									
									tf_num.allowBlank = false;
									tf_num.validationEvent = true;
									tf_price.allowBlank = false;
									tf_price.validationEvent = true;
									tf_dealprice.allowBlank = false;
									tf_dealprice.validationEvent = true;
								}else{
									s.show();
									g.hide();
									
									tf_num.allowBlank = true;
									tf_num.validationEvent = false;
									tf_num.clearInvalid();
									tf_price.allowBlank = true;
									tf_price.validationEvent = false;
									tf_price.clearInvalid();
									tf_dealprice.allowBlank = true;
									tf_dealprice.validationEvent = false;
									tf_dealprice.clearInvalid();
								}
							}
						}
					},{
						xtype: "cnoa_textfield",
						name: "gdname",
						fieldLabel: lang('productName1'),
						width: 355
					},{
						xtype: "panel",
						border: false,
						id: this.ID_formPanel_goods,
						layout: "form",
						items: [
							tf_num,
							{
								border: false,
								layout: "table",
								layoutConfig: {
									columns: 2
								},
								defaults: {
									border: false,
									width: 255,
									layout: "form"
								},
								items: [
									{
										items: [tf_price]
									},
									{
										items: [tf_dealprice]
									}
								]
							}
						]
					},{
						xtype: "textarea",
						id: this.ID_formPanel_service,
						width: 398,
						reacOnly: true,
						hidden: true,
						name: "about",
						fieldLabel: lang('serviceItem')
					},{
						border: false,
						layout: "table",
						layoutConfig: {
							columns: 2
						},
						defaults: {
							border: false,
							width: 255,
							layout: "form"
						},
						items: [
							{
								items: [
									{
										xtype: "datefield",
										width: 143,
										name: "date",
										allowBlank : false,
										format: "Y-m-d",
										fieldLabel: lang('saleDate'),
										value: new Date().format('Y-m-d')
									}
								]
							},
							{
								items: [tf_amount]
							}
						]
					},{
						xtype: "flashfile",
						fieldLabel: lang('addAttach'),
						name: "attach",
						style: "margin-top:5px;",
						inputPre: "filesUpload"
					}
				]
			},
			{
				xtype: "fieldset",
				title: lang('queHuanXX'),
				width: 528,
				layout: "table",
				autoWidth: true,
				layoutConfig: {
					columns: 2
				},
				items: [
					{
						xtype : "button",
						cls: "cnoa-margin-left-5",
						height: 20,
						text: lang('import2') + "Excel",
						handler : function(){
							_this.showImportWindow();
						}
					},
					{
						xtype : "button",
						cls: "cnoa-margin-left-5",
						height: 20,
						text: lang('view'),
						disabled : true,
						id : _this.ID_STOCKOUT_VIEWER,
						handler : function(){
							_this.showImportToDbWindow(_this.formatHtml, "add");
						}
					},
					{
						xtype : "hidden",
						name : "fahuocontent",
						id : _this.ID_FAHUOCONTENT_VALUE
					}
				]
			},
			{
				xtype: "fieldset",
				title: lang('fahuoXX'),
				width: 528,
				items: [
					{
						xtype : "datefield",
						name : "fahuonotice",
						fieldLabel : lang('daoHuoTX'),
						format : "Y-m-d",
						width : 145
					},
					{
						xtype : "textfield",
						name : "transport",
						fieldLabel : lang('tuoYunDept'),
						width : 145
					},
					{
						xtype:"combo",
						fieldLabel: lang('yunShuWay'),
						name: 'fahuotype',
						store: fahuoStore,
						hiddenName: 'fahuotype',
						valueField: 'id',
						displayField: 'name',
						mode: 'local',
						width: 230,
						triggerAction: 'all',
						forceSelection: true,
						editable: false
					},
					{
						xtype : "textarea",
						name : "fahuonotes",
						fieldLabel : lang('remark'),
						width: 380,
						height: 110
					}
				]
			},
			{
				xtype: "fieldset",
				title: lang('auditXinxi'),
				width: 528,
				items: [
					{
						xtype: "hidden",
						name: "checkuid",
						id: ID_checkUid
					},
					{
						xtype: "textfield",
						width: 143,
						readOnly: true,
						name: 'checkname',
						fieldLabel: lang('cWAuditPeople'),
						listeners: {
							'render': function(th){
								th.to = ID_checkUid;
							},
							'focus': function(th){
								people_selector('user', th, false, false);
							}
						}
					},
					_this.smsSender
				]
			}
		];

		this.formPanel = new Ext.form.FormPanel({
			border: false,
			labelWidth: 100,
			labelAlign: 'right',
			autoWidth: true,
			waitMsgTarget: true,
			fileUpload: true,
			autoScroll : true,
			bodyStyle: "padding:5px;",
			items: baseField
		});
		/* 客户信息OVER */

		this.mainPanel = new Ext.Window({
			layout: "fit",
			width: 575,
			height: makeWindowHeight(468),
			resizable: false,
			autoDistory: true,
			modal: true,
			maskDisabled : true,
			items: [this.formPanel],
			plain: true,
			title: title,
			buttonAlign: "right",
			buttons: [
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler: function() {
						_this.submitForm({close: true});
					}
				},
				//应用
				{
					text : lang('apply'),
					hidden: this.action=='edit'?false:true,
					iconCls: 'icon-btn-save',
					handler : function() {
						_this.submitForm({close: false});
					}
				},
				//关闭
				{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function() {
						_this.close();
					}
				}
			]
		});
	},	
	
	showImportWindow : function(){
		var _this = this;
		
		this.UPLOAD_WINDOW_FORMPANEL = new Ext.FormPanel({
			fileUpload: true,
			autoHeight: true,
			autoScroll: false,
			waitMsgTarget: true,
			hideBorders: true,
			border: false,
			bodyStyle: "padding: 5px;",
			buttonAlign: "right",
			items: [
				{
					xtype: 'fileuploadfield',
					name: 'face',
					allowBlank: false,
					buttonCfg: {
						text: lang('browseQHXX') + "(Excel)"
					},
					hideLabel : true,
					width: 370
				},
				{
					xtype: 'displayfield',
					hideLabel: true,
					value: lang('must0307Excel') + "[<a href='resources/customers_import_demo.jpg' target='_blank'>" + lang('model') + "</a>]"
				},
				{
					xtype: 'displayfield',
					hideLabel: true,
					value: lang('attention') + "<span class='cnoa_color_red'>" + lang('recommendedUse') + "<a href='http://cnoa.cn/download/s/messager' target='_blank' ext:qtip='" + lang('clickToDownload') + "' style='font-weight:bold;'>[" + lang('oaClient') + "]</a>" + lang('enterTheOA') + "</span>"
				}
			],
			buttons: [
				{
					text: lang('import2'),
					cls: 'btn-blue4',
					iconCls: 'document-excel-import',
					handler: function(){
						if (this.UPLOAD_WINDOW_FORMPANEL.getForm().isValid()) {
							this.UPLOAD_WINDOW_FORMPANEL.getForm().submit({
								url: _this.baseUrl + "&other=stockout&task=getStockoutExcel",
								waitMsg: lang('waiting'),
								params: {},
								success: function(form, action) {
									CNOA.msg.notice(action.result.msg, lang('customerMgr'));
									_this.formatHtml = action.result.msg;
									_this.showImportToDbWindow(_this.formatHtml, "add");
									Ext.getCmp(_this.ID_STOCKOUT_VIEWER).enable();
									Ext.getCmp(_this.ID_FAHUOCONTENT_VALUE).setValue(_this.formatHtml);
									_this.UPLOAD_WINDOW.close();
								},
								failure: function(form, action) {
									CNOA.msg.alert(action.result.msg);
								}
							});
						}
					}
				},{
					text: lang('close'),
					handler: function(){
						_this.UPLOAD_WINDOW.close();
					}
				}
			]
		});

		this.UPLOAD_WINDOW = new Ext.Window({
			width: 398,
			height: 190,
			autoScroll: false,
			modal: true,resizable: false,
			title: lang('importQHXX'),
			items: [
				this.UPLOAD_WINDOW_FORMPANEL
			],
			listeners:{
				close : function(){
					_this.html = "";
				}
			}
		}).show();
	},
	
	showImportToDbWindow : function(html, type){
		var _this = this;
		
		if(html == ""){
			html = lang('notUploadEXCELdoc');
		}
		var win = new Ext.Window({
			width: 800,
			height: 300,
			autoScroll: false,
			modal: true,
			resizable: true,
			html : '<div style="width:100%;height:100%;"><iframe scrolling=auto frameborder=0 width=100% height=100% id="CNOA_USER_CUSTOMERS_FAHUO"></iframe></div>',
			title: lang('importQHXX'),
			maximizable : true,
			listeners : {
				show : function(th){
					var ifr = Ext.getDom("CNOA_USER_CUSTOMERS_FAHUO");
					var ifrdoc = ifr.contentWindow.document;
				    ifrdoc.open();
				    ifrdoc.write("<BODY>");
				    ifrdoc.write(urldecode(html));
				    ifrdoc.write("</BODY>");
				    ifrdoc.close();
				}
			},
			buttons: [
				{
					text : lang('close'),
					handler : function(){
						win.close();
					}
				}
			]
		});
		
		if(type == "add"){
			win.show();
		}
	},
	
	show : function(){
		var _this = this;
		
		_this.mainPanel.show();

		if(_this.action == "edit"){
			_this.loadFormData(_this.rid);
		}
	},
	
	close : function(){
		this.mainPanel.close();
	},
	
	loadFormData : function(id){
		var me = this;
		this.formPanel.getForm().load({
			url: me.baseUrl+"&task=loadEditFormData",
			params: {rid: id},
			method:'POST',
			waitMsg: lang('waiting'),
			success: function(form, action){
				var editLinkmanid = action.result.data.lid;
				me.cid = action.result.data.cid;
				
				me.formatHtml = action.result.data.fahuocontent;
				me.showImportToDbWindow(me.formatHtml, "edit");
				Ext.getCmp(me.ID_STOCKOUT_VIEWER).enable();
				
				if(me.action == "edit"){
					var widget = me.formPanel.getForm().findField('lid');
					widget.store.load({params:{cid:me.cid}, callback:function(){
						widget.setValue(editLinkmanid);
					}});
				}
			},
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg, function(){
					me.close();
				});
			}
		});
	},

	submitForm : function(config){
		var _this = this;

		if (_this.formPanel.getForm().isValid()) {
			_this.formPanel.getForm().submit({
				url: _this.baseUrl+"&task="+_this.action+"SalesRecord",
				waitTitle: lang('notice'),
				method: 'POST',
				waitMsg: lang('notice'),
				params: {rid : _this.rid},
				success: function(form, action) {
					if(config.close) _this.close();
					//提交手机短信
					try{
						_this.smsSender.submit();
					}catch(e){}
					
					try{
						CNOA_user_customers_customerRecordList.grid.store.reload();
					}catch (e){}
					
					try{
						CNOA_user_customers_collectRecordList.grid.store.reload();
					}catch (e){}
						
					try{
						CNOA_user_customers_recordView.refresh();
					}catch (e){}
					CNOA.msg.notice(action.result.msg, lang('customerMgr'));
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}
			});
		}else{
			CNOA.msg.alert(lang('xinxiNotComplete'));
		}
	}
};

/**
 * 查看销售记录
 */
CNOA_user_customers_recordViewClass = CNOA.Class.create();
CNOA_user_customers_recordViewClass.prototype = {
	init: function(rid){
		var _this = this;
		
		this.rid = rid;
		this.cid = 0;
		this.ID_BTN_EDIT = Ext.id();
		
		this.baseUrl = "index.php?app=user&func=customers&action=index&module=salesRecord";
		
		this.mainPanel = new Ext.Panel({
			layout: "fit",
			border: false,
			bodyStyle: "padding: 10px",
			autoScroll : true,
			tbar : new Ext.Toolbar({
				items: [
					{
						text : lang('refresh'),
						iconCls: 'icon-system-refresh',
						handler : function(button, event) {
							_this.refresh();
						}
					},
					{
						text : lang('modify'),
						id: this.ID_BTN_EDIT,
						iconCls: 'icon-utils-s-edit',
						handler : function(button, event) {
							CNOA_user_customers_recordAddEdit = new CNOA_user_customers_recordAddEditClass("edit", _this.rid, _this.cid);
							CNOA_user_customers_recordAddEdit.show();
						}
					},{
						text : lang('del'),
						iconCls: 'icon-utils-s-delete',
						handler : function(button, event) {
							CNOA.miniMsg.cfShowAt(button, lang('sureDel'), function(){
								_this.deleteRecord();
							});
						}
					}
				]
			}),
			listeners: {
				afterrender : function(panel){
					_this.refresh();
				}
			}
		});
	},
	
	refresh : function(){
		var me = this;
		
		Ext.Ajax.request({  
			url: me.baseUrl + "&task=viewSalesRecord",
			params: {rid: me.rid},
			method: 'POST',
			success: function(r) {
				var result = Ext.decode(r.responseText);
				me.cid = result.cid;
				makeListView(result, me.mainPanel.body);
				//设置修改按钮
				if(result.status == 'yes'){
					Ext.getCmp(me.ID_BTN_EDIT).hide();
				}
			}
		});
	},
	
	deleteRecord : function(){
		var me = this;
		
		Ext.Ajax.request({
			url: me.baseUrl + '&task=deleteSalesRecord',
			method: 'POST',
			params: { rid: me.rid },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(result.msg, lang('customerMgr'));
					me.closeTab();
					try{
						CNOA_user_customers_customerRecordList.grid.store.reload();
					}catch(e){}
					try{
						CNOA_user_customers_collectRecordList.grid.store.reload();
					}catch(e){}
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	},
	
	closeTab : function(){
		mainPanel.closeTab(CNOA.user.customers.recordView.parentID.replace("docs-", ""));
	}
}