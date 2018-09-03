var CNOA_wf_set_flow_formdesignClass, CNOA_wf_set_flow_formdesign;
var CNOA_wf_set_flow_formdesignSetDataClass, CNOA_wf_set_flow_formdesignSetData;
var autoHeightV;

CNOA_wf_set_flow_formdesignSetDataClass = CNOA.Class.create();
CNOA_wf_set_flow_formdesignSetDataClass.prototype = {
	init: function(){
		var _this = this;
		
		this.fields = [
			{name: "id"},
			{name: "libraryID"},
			{name: "libraryName"},
			{name: "name"},
			{name : "truename"}
		];

		var editor = new Ext.ux.grid.RowEditor({
			cancelText: lang('cancel'),
			saveText: lang('update'),
			errorSummary: false
		});
		
		this.store = new Ext.data.GroupingStore({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl + '&task=typeList'
			}),
			reader: new Ext.data.JsonReader({
				totalProperty: "total",
				root: "data",
				fields: this.fields
			}),
			sortInfo: {
			    field: 'id',
			    direction: 'DESC'
			},
			listeners: {
				'update': function(thiz, record, operation) {
					var typeData = record.data;
				}
			}
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel();
		
		this.cm = [
			new Ext.grid.RowNumberer(),
			{header: '',dataIndex: 'id',hidden: true},
			{header: lang('controlName'),dataIndex: 'name',width: 200,sortable: true,
				editor: {
					xtype: 'textfield',
					allowBlank: false
				}
			},
			{header: lang('fieldName'),dataIndex: 'libraryName',width: 250,sortable: true}
		];
		
		this.grid = new Ext.grid.GridPanel({
			layout: "fit",
			border:false,
			store: this.store,
			columns: this.cm,
			sm: this.sm,
       		stripeRows : true,
			hideBorders: true,
			plugins: [editor],
			tbar : [
				{
				    handler : function(button, event) {

				    }.createDelegate(this),
				    iconCls: 'icon-roduction',
				    enableToggle: true,
				    pressed: true,
				    allowDepress: false,
				    toggleGroup: "WF_SET_FLOW_FORM_DESIGN",
				    tooltip: lang('pendingProject'),
				    text : lang('masterMeter')
				},
				{
				    handler : function(button, event) {

				    }.createDelegate(this),
				    enableToggle: true,
				    allowDepress: false,
				    toggleGroup: "WF_SET_FLOW_FORM_DESIGN",
				    iconCls: 'icon-roduction',
				    tooltip: lang('displayListAllTrial'),
				    text : lang('detailForm')
				},
				'->',
				lang('formName') + ": ",
				{
					xtype : "textfield",
					width : 200
				}
			]
		});
		
		this.mainPanel = new Ext.Window({
			title: lang('smartDesigner'),
			width: 490,
			height: makeWindowHeight(500),
			layout: "fit",
			modal: true,
			items : this.grid,
			buttons : [
				{
					text : lang('save')
				},
				{
					text : lang('saveAndClose')
				},
				{
					text : lang('close'),
					handler : function(){
						_this.mainPanel.close();
					}
				}
			]
		}).show();
	}
};


CNOA_wf_set_flow_formdesignClass = CNOA.Class.create();
CNOA_wf_set_flow_formdesignClass.prototype = {
	init: function(){
		var _this = this;

		this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=form";
		
		this.ID_toolBar = Ext.id();
		this.ID_btn_columnSet = Ext.id();

		this.rightPanel = new Ext.Panel({
			border: false,
			region: 'west',
			width: 110,
			layout: "fit",
			bodyStyle: "border-right-width:1px;",
			items: [
				{
					xtype: "panel",
					border: false,
					bodyStyle: "background-color:#E7E7E7",
					id: this.ID_toolBar,
					layout: {
						type:'vbox',
						padding:'5',
						align:'stretch'
					},
					defaults:{
						xtype:'button',
						margins: '0 0 10 0',
						height: 25
					},
					items:[
						{
							iconCls: "icon-form-textfield",
							cls: 'btn-blue3',
							text: lang('singleLine'),
							handler: function(){
								WorkFlowForm.newDialog('textfield');
							}
						},
						{
							iconCls: "icon-form-textarea",
							text: lang('multiLine'),
							cls: 'btn-blue3',
							handler: function(){
								WorkFlowForm.newDialog('textarea');
							}
						},
						{
							iconCls: "icon-form-combobox",
							text: lang('dropDownList'),
							cls: 'btn-blue3',
							handler: function(){
								WorkFlowForm.newDialog('select');
							}
						},
						{
							iconCls: "ui-radio-button",
							text: lang('radioButton') + '&nbsp;&nbsp;&nbsp;&nbsp;',
							cls: 'btn-blue3',
							handler: function(){
								WorkFlowForm.newDialog('radio');
							}
						},
						{
							iconCls: "ui-check-box",
							text: lang('checkBox') + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
							cls: 'btn-blue3',
							handler: function(){
								WorkFlowForm.newDialog('checkbox');
							}
						},
						{
							iconCls : "icon-system-theme-setup",
							text : lang('calculatedField') + '&nbsp;&nbsp;&nbsp;',
							cls: 'btn-blue3',
							handler : function(){
								WorkFlowForm.newDialog('calculate');
							}
						},
						{
							iconCls: "icon-form-combobox",
							text: lang('macroControl') + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
							cls: 'btn-blue3',
							handler: function(){
								WorkFlowForm.newDialog('macro');
							}
						},
						{
							iconCls : "icon-application-task",
							text : lang('createDetailForm'),
							cls: 'btn-blue3',
							handler : function(){
								try{
									CNOA.msg.alert(lang('useFollowMethod') + '：<a href="/resources/images/help/createdetailtable.gif" target="_black">'+lang('clikViewCreate')+'</a>');
								}catch(e){

								}
							}
						},
						{
							iconCls: 'icon-form-signature',
							text: lang('signature1') + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
							cls: 'btn-blue3',
							handler: function(){
								WorkFlowForm.newDialog('signature');
							}
						},
						{
							iconCls : "icon-application-task",
							text : lang('selector') + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
							cls: 'btn-blue3',
							handler : function(){
								WorkFlowForm.newDialog('choice');
							}
						},
						{
							iconCls : "icon-datasource",
							text : lang('dateSource') + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
							cls: 'btn-blue3',
							handler : function(){
								WorkFlowForm.newDialog('datasource');
							}
						},
						{
							iconCls : "icon-file-up",
							text : lang('flowAttachment') + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
							cls: 'btn-blue3',
							handler : function(){
								WorkFlowForm.newDialog('attach');
							}
						},
						{
							iconCls : "icon-file-up",
							text : "SQL选择器" + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
							cls: 'btn-blue3',
							handler : function(){
								WorkFlowForm.newDialog('sqlselector');
							}
						}
					]
				}
			],
			tbar: new Ext.Toolbar({
				style: "border-right-width:1px;",
				items: ['<span style="color:#1C1C1C">' + lang('formTool') + '：</span>']
			})
		});

		this.formPanel = new Ext.Panel({
			region: 'center',
			border: false,
			layout: "fit",
			html: "<div id='CNOA_FLOW_FLOW_FORM_DESIGN' style='width:100%;height:100%;'></div>",
			autoScroll: false,
			listeners: {
				afterrender : function(th){
					try {
						setTimeout(function(){
							autoHeightV = setInterval(function(){
								try{var h = $(".edui-toolbar").height() + $(".edui-editor-bottomContainer").height() + 8;}catch(e){}
								try{editor.setHeight(th.getHeight() - h);}catch(e){}
							}, 100);
							
							/*
							editor = new baidu.editor.ui.Editor({
								initialContent: $('.myEditorData').html().replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">")
							});
							editor.render('CNOA_FLOW_FLOW_FORM_DESIGN');
							*/
							
							editor = new UE.ui.Editor();
							editor.render("CNOA_FLOW_FLOW_FORM_DESIGN");
							editor.ready(function(){
								editor.setContent($('.myEditorData').html().replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">"));
								$(".edui-editor").css("width", "100%");
								$(".edui-editor-iframeholder").css("width", "100%");
								setTimeout(function(){
									try{parent.CNOA_wf_set_flow_makeForm.setEditorPage();}catch(e){}
								}, 50);
							})
							editor.addListener('sourcemodechanged', function(t, sourceMode){
								_this.setToolBtns(sourceMode);
							});
							
							
							
						}, 100);
					}catch(e){}

					try {
						parent.CNOA_wf_set_flow_makeForm.editorLoaded();
					}catch (e){}
				},

				destroy : function(){
					clearInterval(autoHeightV);
				}
			}
		});
		
		this.centerPanel = new Ext.Panel({
			border: false,
			layout: "border",
			region: 'center',
			items: [this.formPanel, this.rightPanel],
			tbar: new Ext.Toolbar({
				items: [
					{
						text: lang('save'),
						iconCls: 'icon-order-s-accept',
						id: _this.ID_saveForm,
						handler: function(){
							top.CNOA_wf_set_flow_makeForm.submit({close: false});
						}
					},"-",
					{
						iconCls : "icon-page-view",
						text : lang('formatSetting'),
						cls: 'btn-gray1',
						margins: '10 0 5 0',
						handler : function(){
							_this.format();
						}
					},
					{
						iconCls : "icon-form-tpl",
						margins: '10 0 5 0',
						text : lang('template'),
						cls: 'btn-red1',
						handler : function(){
							_this.importTemplate();
						}
					},
					{
						iconCls : "document-excel-import",
						margins: '10 0 10 0',
						cls: 'btn-blue4',
						text : lang('import2'),
						handler : function(){
							_this.importWin();
						}
					},
					{
						iconCls : "icon-excel",
						text : lang('export2'),
						cls: 'btn-blue4',
						handler : function(){
							_this.exportForm();
						}
					},
					{
						iconCls : "icon-page-view",
						text : lang('preview'),
						cls: 'btn-green1',
						handler : function(){
							_this.preview(editor.getContent());
						}
					},"-",
					{
						text: lang('wfFlowFieldMobileOrder'),
						iconCls: "icon-arrow-switch",
						handler: function(){
							top.CNOA_wf_set_flow_makeForm.makeOrderMobileList();
						}
					}//,"-",
					//'<span style="color:#666">' + lang('tipJianYiIE') + '</span>'
				]
			})
		});
		
		this.viewPort = new Ext.Viewport({
			layout: 'border',
			items: [this.centerPanel]
		});
		
		this.viewPort.doLayout();
	},

	setToolBtns : function(sourceMode){
		var _this = this;
		Ext.getCmp(_this.ID_toolBar).items.each(function(v, i){
			if(sourceMode){
				v.disable();
				top.Ext.getCmp(top.CNOA_wf_set_flow_makeForm.ID_saveForm).disable();
				top.Ext.getCmp(top.CNOA_wf_set_flow_makeForm.ID_closeForm).disable();
			}else{
				v.enable();
				top.Ext.getCmp(top.CNOA_wf_set_flow_makeForm.ID_saveForm).enable();
				top.Ext.getCmp(top.CNOA_wf_set_flow_makeForm.ID_closeForm).enable();
			}
		});
	},

	dataSet : function(){
		CNOA_wf_set_flow_formdesignSetData = new CNOA_wf_set_flow_formdesignSetDataClass();
	},
	
	format : function(){
		var _this = this;

		var form = new Ext.form.FormPanel({
			border: false,
			region : 'north',
			height : 120,
			layout : 'fit',
			bodyStyle: 'border-bottom-width:1px;',
			items : [
				{
					xtype: 'panel',
					style: 'margin: 10px 0px 5px 5px',
					labelAlign: 'right',
					labelWidth : 60,
					layout: "column",
					hideBorders: true,
					border: false,
					items: [
						{
							columnWidth: .5,
							layout: 'form',
							items: [
								{
									xtype : 'textfield',
									fieldLabel : lang('styleName'),
									allowBlank : false,
									anchor: '95%',
									name : 'name'
								},
								{
									xtype : 'combo',
									fieldLabel: lang('font'),
									name: 'font',
									anchor: '95%',
									value : '6',
									store: new Ext.data.SimpleStore({
										fields: ['value', 'name'],
										data: [['1', lang('songType')], ['2', lang('kaiTi')],  ['3', lang('liShu')],  ['4', lang('heiTi')],  ['5', "andale mono"],  ['6', "arial"],  ['7', "arial black"],  ['8', "comic sons ms"],  ['9', "impact"], ['10', "times new roman"], ['11', lang('micresoftYahei')], ['12', '仿宋']]
									}),
									hiddenName: 'font',
									valueField: 'value',
									displayField: 'name',
									mode: 'local',
									triggerAction: 'all',
									forceSelection: true,
									editable: false
								},
								{
									xtype: "colorfield",
									fieldLabel: lang('fontColor'),
									defaultColor: "000000",
									editable:false,
									name: "color"
								},
								{
									layout : 'table',
									border : false,
									layoutConfig: {
										columns: 2
									},
									items : [
										{
											xtype : 'button',
											text : lang('add'),
											style : 'margin:8px 0 2px 10px;',
											handler : function(){
												submtiStyle('add');
											}
										},
										{
											xtype : 'button',
											style : 'margin:8px 0 2px 5px;',
											text : lang('editSave'),
											handler : function(){
												submtiStyle('edit');
											}
										}
									]
								}
							]
						},
						{
							columnWidth: .5,
							layout: 'form',
							items: [
								{
									xtype: 'compositefield',
									fieldLabel: lang('spec'),
									width : 200,
									defaults: {
										xtype: 'checkbox',
										flex: 1
									},
									items: [
										{
											boxLabel: '<B>' + lang('bold') + '</B>',
											name: 'bold'
										},
										{
											boxLabel: '<I>' + lang('italic') + '</I>',
											name: 'italic'
										}
									]
								},
								{
									xtype : 'combo',
									fieldLabel: lang('size'),
									name: 'size',
									anchor: '95%',
									value : '16',
									store: new Ext.data.SimpleStore({
										fields: ['value', 'name'],
										data: [['10', "10px"], ['11', "11px"],  ['12', "12px"],  ['14', "14px"],  ['16', "16px"],  ['18', "18px"],  ['20', "20px"],  ['24', "24px"],  ['36', "36px"]]
									}),
									hiddenName: 'size',
									valueField: 'value',
									displayField: 'name',
									mode: 'local',
									triggerAction: 'all',
									forceSelection: true,
									editable: false
								},
								{
									xtype : 'combo',
									fieldLabel: lang('border'),
									name: 'border',
									anchor: '95%',
									value : 0,
									store: new Ext.data.SimpleStore({
										fields: ['value', 'name'],
										data: [['0', lang('onlyBottomFrame')], ['1', lang('haveBorder')],  ['2', lang('notBorder')]]
									}),
									hiddenName: 'border',
									valueField: 'value',
									displayField: 'name',
									mode: 'local',
									triggerAction: 'all',
									forceSelection: true,
									editable: false
								},
								{
									xtype : 'hidden',
									name : 'sid'
								}
							]
						}
					]
				}
			]
		});
		
		var submtiStyle = function(type){
			if (form.getForm().isValid()) {
			    form.getForm().submit({
			        url: _this.baseUrl+"&task=submitStyle",
			        method: 'POST',
					params : {type : type},
			        success: function(th, action) {
						if(action.result.success){
							CNOA.msg.notice(action.result.msg);
							form.getForm().reset();
							form.getForm().findField('color').setValue('000000');
							store.reload();
						}else{
							CNOA.msg.alert(action.result.msg);
						}
			        }.createDelegate(this),
			        failure: function(form, action) {
			            CNOA.msg.alert(action.result.msg, function(){
			            }.createDelegate(this));
			        }.createDelegate(this)
			    });
			}
		};
		
		var fields = [
			{name : "sid"},
			{name : "name"},
			{name : "font"},
			{name : "fontName"},
			{name : "size"},
			{name : "color"},
			{name : "border"},
			{name : "bold"},
			{name : "italic"},
			{name : "underline"},
			{name : "format"}
			
		];
		
		var store = new Ext.data.Store({
			autoLoad : true,
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + '&task=getStyleList&from=set'
			}),
			reader:new Ext.data.JsonReader({totalProperty:"total", root:"data", fields: fields})
		});
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		
		var dsc = Ext.data.Record.create([{name: 'name'}]);
		
		var colModel = new Ext.grid.ColumnModel([
			sm,
			{header: "", dataIndex: 'sid', hidden : true, sortable: false, menuDisabled: true},
			{header: lang('styleName'), dataIndex: 'name', width: 140, sortable: false, menuDisabled: true},
			{header: lang('font'), dataIndex: 'fontName', width: 110, sortable: false, menuDisabled: true},
			{header: lang('format'), dataIndex: 'format', width: 120, sortable: false, menuDisabled: true},
			{header: lang('color'), dataIndex: 'color', width: 40, sortable: false, menuDisabled: true, renderer : function(value){
				return '<span style="background-color:#'+value+';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
			}},
			{header: lang('size'), dataIndex: 'size', width: 40, sortable: false, menuDisabled: true, renderer : function(value){
				return value+"px";
			}},
			{header: lang('border'), dataIndex: 'border', width: 80, sortable: false, menuDisabled: true, renderer : function(value){
				var l = "";
				switch(value){
					case "0" :
						l = lang('onlyBottomFrame');
						break;
					case "1" :
						l = lang('haveBorder');
						break;
					case "2" :
						l = lang('notBorder');
						break;
				}
				return l;
			}},
			{header: lang('opt'), dataIndex: 'sid', width: 40, sortable: false, menuDisabled: true, renderer : function(value){
				return "<a href='javascript:void(0)' onclick='CNOA_wf_set_flow_formdesign.deleteData("+value+")'>"+lang('del')+"</a>";
			}}
		]);
		
		var grid = new Ext.grid.GridPanel({
			region : "center",
			layout : "fit",
			border : false,
			stripeRows : true,
			store: store,
			cm: colModel,
			sm : sm,
			autoScroll : true,
			listeners : {
				cellclick : function(thiz, rowIndex, columnIndex, e){
					if(columnIndex != 8){
						var data = thiz.getStore().getAt(rowIndex).data;
						var rec = {
							data : {
								sid	   : data.sid,
								name   : data.name,
								font   : data.font,
								color  : data.color,
								size   : data.size,
								border : data.border,
								bold   : data.bold == 1 ? "on" : " ",
								italic : data.italic == 1 ? "on" : " ",
								underline : data.underline == 1 ? "on" : " "
							}
						}
						form.getForm().loadRecord(rec);
					};
				}
			}
		});
		
		var win = new CNOA.wf.wfplugs.window({
			title: lang('setGlobalFormat'),
			width: 650,
			height: 400,
			modal: true,
			autoScroll: true,
			layout : 'border',
			bodyStyle: 'background-color:#FFF;',
			items : [form, grid],
			buttons : [
				{
					text : lang('close'),
					handler : function(){
						win.close();
					}
				}
			]
		}).show();
		
		this.deleteData = function(sid){
			var _this = this;
			CNOA.msg.cf(lang('areYouDelete'), function(btn){
				if(btn == "yes"){
					Ext.Ajax.request({
					    url: _this.baseUrl + "&task=deleteStyle",
					    method: 'POST',
						params : {sid : sid},
					    success: function(r) {
					        var result = Ext.decode(r.responseText);
					        if(result.success === true){
								CNOA.msg.notice(result.msg);
								store.reload();
					        }else{
					            CNOA.msg.alert(result.msg, function(){});
					        }
					    }
					});
				}
			});
		}
	},
	
	preview : function(html){
		var _this = this;
		
		var openPreviewWindow = function(html){
			var box = top.Ext.getBody().getBox();
			new top.Ext.Window({
				title: lang('preview'),
				width: box.width - 100,
				height: box.height - 100,
				modal: true,
				maximizable: true,
				resizable: true,autoScroll: true,
				bodyStyle: 'background-color:#EDEDED;padding:10px;margin:0;',
				listeners: {
					afterrender: function(win){
						if(!window.WdatePicker){loadScript('/cnoa/scripts/datePicker/datePicker.js');}
						//if(typeof(WdatePicker) != 'function'){loadScript('./scripts/datePicker/datePicker.js');}
						//loadScript2("./scripts/datePicker/WdatePicker.js");
						win.body.update(html);
	
						top.CNOA_wf_form_checker.formInitForView(win.id);

						var pageset = editorConfig.pageset;
						var page = pageset.pageSize;
						var width = 794;
						var height = 1123;
						//设置纸张大小
						switch(page){
							case 'a1page' :
								if(pageset.pageDir == 'lengthways'){
									width = 2245;
									height = 3174;
								}else{
									width = 3174;
									height = 2245;
								}
							break;
							case 'a2page' :
								if(pageset.pageDir == 'lengthways'){
									width = 1587;
									height = 2245;
								}else{
									width = 2245;
									height = 1587;
								}
							break;
							case 'a3page' :
								if(pageset.pageDir == 'lengthways'){
									width = 1123;
									height = 1587;
								}else{
									width = 1587;
									height = 1123;
								}
							break;
							case 'a4page' :
								if(pageset.pageDir == 'lengthways'){
									width = 794;
									height = 1123;
								}else{
									width = 1123;
									height = 794;
								}
							break;
							case 'a5page' :
								if(pageset.pageDir =='lengthways'){
									width = 559;
									height = 794;
								}else{
									width = 794;
									height = 559;
								}
							break;
						}
						
						//设置边距大小
						var up = Math.ceil(pageset.pageUp*3.4);
						var down = Math.ceil(pageset.pageDown*3.4);
						var left = Math.ceil(pageset.pageLeft*3.4);
						var right = Math.ceil(pageset.pageRight*3.4);
					
						var padding = up+'px '+right+'px '+down+'px '+left+'px';
							top.$("#wf_previewtd").css('padding', padding);
							top.$("#wf_previewcontent").width(width-left-right);
					}
				}
			}).show();
		};
		//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
		//提交给后台
		/*Ext.Ajax.request({
			url: _this.baseUrl + "&task=makePreviewHtml",
			method: 'POST',
			params: {task: "makePreviewHtml", html: html, pageset: Ext.encode(editorConfig.pageset)},
			success: function(r) {
				openPreviewWindow(r.responseText);
			}
		});*/
        openPreviewWindow(html);
	},
	
	//导出窗口
	exportForm : function(){
		var _this = this;
		//提交给后台
		Ext.Ajax.request({
			url: _this.baseUrl + "&task=exportFormHtml",
			method: 'POST',
			params: {task: "makeExportHtml", step: '1', html: editor.getContent()},
			success: function(r) {
				ajaxDownload(_this.baseUrl + "&task=exportFormHtml&flowId="+form_id+"&file=" + r.responseText);
			}
		});
	},
	
	//导入窗口
	importWin : function(){
		var _this = this;
		Ext.MessageBox.show({
			title: lang('importCode'),
			msg: lang('putHTMLhere'),
			width:560,defaultTextHeight:360,
			multiline: true,
			buttons: {cancel:lang('cancel'), ok : lang('ok')},
			fn : importData
		});
		
		function importData(btn, text){
			if(btn == "ok"){
				editor.setContent(text);
			}
		}
	},
	
	//模版
	importTemplate: function(){
		var _this = this;
		
		var tplStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + "&task=getTplList"
			}),
			reader: new Ext.data.JsonReader({
				root: 'data',
				fields: [
					{name: 'code'},
					{name: 'tplName'}
				]
			})
		});
		tplStore.load();
			
		var tplGrid = new Ext.grid.GridPanel({
			store: tplStore,
			border: false,
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
			columns:[
				new Ext.grid.RowNumberer(),
				{header: 'code', hidden: true, dataIndex: 'code'},
				{header: lang('template'), sortable: true, dataIndex: 'tplName', width: 200}
			]
		});
		
		var getTplHtml = function(opt){
			var selected = tplGrid.getSelectionModel().getSelected();
			if(selected){
				Ext.Ajax.request({
					url: _this.baseUrl + "&task=getTplHtml",
					method: 'POST',
					params: {code: selected.data.code},
					success: function(val){
						if(val.responseText == ''){
							CNOA.msg.alert(lang('tempFileNotExist'));
						}else{
							if(opt=='view'){
								_this.preview(val.responseText);
							}else if(opt=='insert'){
								editor.setContent(val.responseText);
								tplWin.close();
							}
						}
					}
				});
			}
		};
		
		var tplWin = new Ext.Window({
			title: lang('formTemp'),
			width: 400,
			height: 300,
			layout: 'fit',
			items: tplGrid,
			modal: true,
			buttons: [
				{
					text: lang('preview'),
					handler: function(){
						getTplHtml('view');
					}
				},
				{
					text: lang('ok'),
					handler: function(th){
						getTplHtml('insert');
					}
				},
				{
					text: lang('cancel'),
					handler: function(th){
						tplWin.close();
					}
				}
			]
		}).show();
	}
};

var oFCKeditor;
Ext.onReady(function() {
	CNOA_wf_set_flow_formdesign = new CNOA_wf_set_flow_formdesignClass();
});
