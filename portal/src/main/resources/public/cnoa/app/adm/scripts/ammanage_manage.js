
/**
 * 全局变量
 * 
 */
var CNOA_adm_ammanage_manage, CNOA_adm_ammanage_manageClass;
CNOA_adm_ammanage_manageClass = CNOA.Class.create();
CNOA_adm_ammanage_manageClass.prototype = {
		init : function(){
			var _this = this;
			
			var ID_SEARCH_NAME       	= Ext.id();
			var ID_SEARCH_NUMBER     	= Ext.id();
			var ID_SEARCH_TYPE       	= Ext.id();
			var ID_SEARCH_MODEL      	= Ext.id();
			var ID_SEARCH_ADMIN         = Ext.id();
			var ID_SEARCH_USERDEP       = Ext.id();
			var ID_SEARCH_USER        	= Ext.id();
			
			
			this.formData             = "";
			
			this.baseUrl = "index.php?app=adm&func=ammanage&action=manage";
			
			this.getMeetingRoomEquipmentStore = new Ext.data.Store({
				proxy: new Ext.data.HttpProxy({
					url: _this.baseUrl+"&task=meetingroomEquipment"
				}),
				reader: new Ext.data.JsonReader({
					root:'data',
					fields: [
						{name : "eid"},
						{name : "name"}
					]
				}),
				listeners : {
					load : function(){
						if(_this.type == "edit"){
							Ext.each(_this.formData.equipment.eids, function(item, index, allItems){
								if (index == 0){
									_this.formPanel.getForm().findField("equipment[0]").setValue(item);
									//_this.formPanel.getForm().findField("quantity[0]").setValue(item);
								}else{
									_this.formPanel.getForm().findField("equipment["+index+"]").setValue(item);
									//_this.formPanel.getForm().findField("quantity["+index+"]").setValue(item);
								}
							});
							Ext.each(_this.formData.equipment.quantity, function(item, index, allItems){
								if (index == 0){
									//_this.formPanel.getForm().findField("equipment[0]").setValue(item);
									_this.formPanel.getForm().findField("quantity[0]").setValue(item);
								}else{
									//_this.formPanel.getForm().findField("equipment["+index+"]").setValue(item);
									_this.formPanel.getForm().findField("quantity["+index+"]").setValue(item);
								}
							});
						}
					}
				}
			});
			
			this.storeBar = {
				storeType   : "waiting",
				name        : "",
				number      : "",
				type        : "",
				user        : "",
				admin       : "",
				model       : "",
				userdep  	: ""
			};
			
			this.fields = [
				{name: "id"},
				{name : "number"},
				{name: "name"},
				{name: "dep"},
				{name: "type"},
				{name: "time"},
				{name: "admin"},
				{name: "status"},
				{name: "oldvalue"},
				{name: "maker"},
				{name: "model"},
				{name: "salvage"},
				{name: "year_limit"},
				{name: "add"},
				{name: "add"},
				{name: "count"}
			];

			this.store = new Ext.data.Store({
				proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getJsonData", disableCaching: true}),   
				reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
				listeners:{
					exception : function(th, type, action, options, response, arg){
						var result = Ext.decode(response.responseText);
						
					}
				}
			});
			
			this.store.load();
			
			this.sm = new Ext.grid.CheckboxSelectionModel({
				singleSelect: false
			});
			
			this.pagingBar = new Ext.PagingToolbar({
				displayInfo: true, emptyMsg: lang('pagingToolbarEmptyMsg'), displayMsg: lang('showDataTotal2'),   
				store: this.store,
				pageSize: 15,
				listeners: {
					"beforechange" : function(th, params){
						if(_this.storeBar.storeType != ''){
							params.storeType = _this.storeBar.storeType;
						}
						if(_this.storeBar.name != ''){
							params.name = _this.storeBar.name;
						}
						if(_this.storeBar.title != ''){
							params.title = _this.storeBar.title;
						}
						if( _this.storeBar.stime != ''){
							params.stime = _this.storeBar.stime;
						}
						if(_this.storeBar.etime != ''){
							params.etime = _this.storeBar.etime;
						}
						if(_this.storeBar.meetingroom != ''){
							params.meetingroom = _this.storeBar.meetingroom;
						}
					}
				}
			});
			
			this.colModel = new Ext.grid.ColumnModel([
				new Ext.grid.RowNumberer(),
				this.sm,
				{header: "aid", dataIndex: 'aid', hidden: true},
				{header: lang('asetNum'), dataIndex: 'name', width: 70, sortable: true, menuDisabled :true, renderer : this.showDetails.createDelegate(this)},
				{header: lang('stdname'), dataIndex: 'title', width: 90, sortable: true, menuDisabled :true},
				{header: lang('inDepartment'), dataIndex: 'meetingroom', width: 90, sortable: true, menuDisabled :true, renderer : _this.showMeetingroomDetails},
				{header: lang('assetTye'), dataIndex: 'type', width: 90, sortable: true, menuDisabled :true},
				{header: lang('addDate'), dataIndex: 'mgrman', width: 85, sortable: true, menuDisabled :true},
				{header: lang('principal'), dataIndex: 'markman', width: 70, sortable: true, menuDisabled :true},
				{header: lang('assetStatus'), dataIndex: 'markman', width: 70, sortable: true, menuDisabled :true},
				{header: lang('assetCost'), dataIndex: 'markman', width: 70, sortable: true, menuDisabled :true},
				{header: lang('manufacture'), dataIndex: 'markman', width: 70, sortable: true, menuDisabled :true},
				{header: lang('residualRate'), dataIndex: 'markman', width: 70, sortable: true, menuDisabled :true},
				{header: lang('expecteYear'), dataIndex: 'markman', width: 70, sortable: true, menuDisabled :true},
				{header: lang('wayIncrease'), dataIndex: 'markman', width: 70, sortable: true, menuDisabled :true},
				{header: lang('quantity'), dataIndex: 'markman', width: 70, sortable: true, menuDisabled :true},
				{header: lang('useHuman'), dataIndex: 'stime', width: 70, sortable: true, menuDisabled :true, renderer : this.formatTime.createDelegate(this)},				
				{header: lang('opt'), dataIndex: 'aid', width: 270, sortable: true, menuDisabled :true, renderer : this.operate.createDelegate(this)},
				{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
			]);
			
			this.list = new Ext.grid.GridPanel({
				region : "center",
				border:false,
				store:this.store,
				loadMask : {msg: lang('waiting')},
				cm: this.colModel,
				sm: this.sm,
				hideBorders: true,
				listeners:{
					"rowdblclick":function(button, event){
						var rows = _this.list.getSelectionModel().getSelections();
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, lang('mustSelectOneRow'));
						} else {
							_this.type = "edit";
							_this.edit_id = rows[0].get("aid");
							_this.taskwindow()
						}
					}
				},
				tbar:[
					{
						text:lang('refresh'),
						iconCls:'icon-system-refresh',
						handler:function(button, event){
							_this.store.reload();
						}
					},
					{
						text: lang('increaseAsset'),
						iconCls: 'icon-utils-s-add',
						handler:function(){
							_this.window(0, "add");
						}
					},
					{
						handler : function(button, event) {
							_this.storeBar.storeType = "waiting";
							_this.store.load({params: _this.storeBar});
						}.createDelegate(this),
						iconCls: 'icon-roduction',
						enableToggle: true,
						pressed: false,
						allowDepress: false,
						toggleGroup: "project_proj_approve_type",
						//tooltip: "显示",
						text : lang('assetChange'),
						handler:function(){
							_this.window(0, "change");
						}
					},
					{					
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "project_proj_approve_type",
						iconCls: 'icon-roduction',
						//tooltip: "显示",
						text : lang('assetDisposal')						
					},
					{
						handler : function(button, event) {
							_this.storeBar.storeType = "unpass";
							_this.store.load({params: _this.storeBar});
						}.createDelegate(this),
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "project_proj_approve_type",
						iconCls: 'icon-roduction',
						//tooltip: "显示",
						text : lang('assetBorrow')
					},
					{
						handler : function(button, event) {
							_this.storeBar.storeType = "unpass";
							_this.store.load({params: _this.storeBar});
						}.createDelegate(this),
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "project_proj_approve_type",
						iconCls: 'icon-roduction',
						//tooltip: "显示",
						text : lang('renturnItems')
					},
					{
						handler : function(button, event) {
							_this.storeBar.storeType = "cancel";
							_this.store.load({params: _this.storeBar});
						}.createDelegate(this),
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "project_proj_approve_type",
						iconCls: 'icon-roduction',
						//tooltip: "显示",
						text : lang('assetRepair')
					},
					{
						handler : function(button, event) {
							_this.storeBar.storeType = "history";
							_this.store.load({params: _this.storeBar});
						}.createDelegate(this),
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "project_proj_approve_type",
						iconCls: 'icon-roduction',
						//tooltip: "显示",
						text : lang('assetInquiry')
					},
					"<span class='cnoa_color_gray'>" + lang('doubleClickEdit') + "</span>",
					"->",{xtype: 'cnoa_helpBtn', helpid: 4001}
				],
				bbar: this.pagingBar
			});
			
		this.getTypeStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + "&task=getTypeStore",
				disableCaching: true
			}),
			reader: new Ext.data.JsonReader({
				totalProperty: "total",
				root: "data",
				fields: [{
					name: "id"
				}, {
					name: "name"
				}]
			})
		});
		this.getTypeStore.load();
			
		this.getUserdepStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + "&task=getUserdepStore",
				disableCaching: true
			}),
			reader: new Ext.data.JsonReader({
				totalProperty: "total",
				root: "data",
				fields: [{
					name: "id"
				}, {
					name: "name"
				}]
			})
		});
		this.getUserdepStore.load();
			this.mainPanel = new Ext.Panel({
				collapsible:false,
				hideBorders: true,
				border: false,
				layout:'border',
				autoScroll: false,
				items: [this.list],
				tbar:new Ext.Toolbar({
					style: 'border-left-width:1px;',
					items:[
						lang('asetNum') + ':',
						{
							xtype: "textfield",
							width: 60,
							name : 'number',
							id:ID_SEARCH_NUMBER
						},
						lang('stdname') + ':',
						{
							xtype: "textfield",
							width: 60,
							name : 'name',
							id:ID_SEARCH_NAME
						},
						lang('assetClass') + ':',
						{
							xtype: "combo",
							width: 60,
							name : 'type',
							id:ID_SEARCH_TYPE,
							store: _this.getTypeStore,
							hiddenName : 'name',
							valueField : 'id',
							displayField: 'name',
							mode: 'local',
							width: 60,
							triggerAction: 'all',
							forceSelection: true,
							editable: false
						},
						lang('assetType') + ':',
						{
							xtype: "textfield",
							width: 60,
							name : 'model',
							id:ID_SEARCH_MODEL
						},
						lang('principal') + ':',
						{
							xtype: "textfield",
							width: 60,
							name : 'admin',
							id:ID_SEARCH_ADMIN
						},
						lang('department') + ':',
						{
							xtype: "combo",
							width: 60,
							name : 'user_dep',
							id:ID_SEARCH_USERDEP,
							store: _this.getUserdepStore,
							hiddenName : 'name',
							valueField : 'id',
							displayField: 'name',
							mode: 'local',
							width: 60,
							triggerAction: 'all',
							forceSelection: true,
							editable: false
						},
						lang('useHuman') + ':',
						{
							xtype: "textfield",
							width: 60,
							name : 'user',
							id:ID_SEARCH_USER
							
						},
						{
							xtype: "button",
							text: lang('search'),
							style: "margin-left:5px",
							cls: "x-btn-over",
							listeners: {
								"mouseout" : function(btn){
									btn.addClass("x-btn-over");
								}
							},
							handler: function(){
								_this.storeBar.name        = Ext.getCmp(ID_SEARCH_NAME).getValue();
								_this.storeBar.number       = Ext.getCmp(ID_SEARCH_NUMBER).getValue();
								_this.storeBar.type       = Ext.getCmp(ID_SEARCH_TYPE).getRawValue();
								_this.storeBar.model       = Ext.getCmp(ID_SEARCH_MODEL).getRawValue();
								_this.storeBar.admin = Ext.getCmp(ID_SEARCH_ADMIN).getValue();
								_this.storeBar.userdep       = Ext.getCmp(ID_SEARCH_USERDEP).getRawValue();
								_this.storeBar.user = Ext.getCmp(ID_SEARCH_USER).getValue();
								_this.store.load({params:_this.storeBar});
							}
						},
						{
							xtype: "button",
							text: lang('clear'),
							handler:function(){
								Ext.getCmp(ID_SEARCH_NAME).setValue("");
								Ext.getCmp(ID_SEARCH_NUMBER).setValue("");
								Ext.getCmp(ID_SEARCH_TYPE).setValue("");
								Ext.getCmp(ID_SEARCH_MODEL).setValue("");
								Ext.getCmp(ID_SEARCH_ADMIN).setValue("");
								Ext.getCmp(ID_SEARCH_USERDEP).setValue("");
								Ext.getCmp(ID_SEARCH_USER).setValue("");
								
								_this.storeBar.name        = "";
								_this.storeBar.number       = "";
								_this.storeBar.type       = "";
								_this.storeBar.model       = "";
								_this.storeBar.admin 		= "";
								_this.storeBar.userdep 		= "";
								_this.storeBar.user 		= "";
								_this.store.load({params:_this.storeBar});
							}
						}
					]
				})
			});
		},
		
		window : function(aid, type){
			var _this = this;
			var ID_operatorpersonGroup = Ext.id();
			var ID_MEETING_ROOM_DETAIL = Ext.id();
			var ID_SEARCH_S_DATE       = Ext.id();
			var ID_SEARCH_E_DATE       = Ext.id();
			var ID_SEARCH_S_TIME       = Ext.id();
			var ID_SEARCH_E_TIME       = Ext.id();
			var ID_SEARCH_CONTAIN1     = Ext.id();
			var ID_SEARCH_CONTAIN2     = Ext.id();
			var ID_START_TIME_PANEL    = Ext.id();
			var ID_END_TIME_PANEL      = Ext.id();
			var ID_PERIOD_TIME_PANEL   = Ext.id();
			var ID_EXPLANER_BUTTON     = Ext.id();
			this.ID_MARKER_TEXT         = Ext.id();
			
			this.content_start         = 1;
			
			var meetingroomType        = new Ext.data.Store({
				proxy: new Ext.data.HttpProxy({
					url: _this.baseUrl+"&task=meetingroomType"
				}),
				reader: new Ext.data.JsonReader({
					root:'data',
					fields: [
						{name : "tid"},
						{name : "name"}
					]
				})
			});
			
			meetingroomType.load();
			if(type=="add"){
			var baseField = [
				{
					xtype: "fieldset",
					title: lang('addAssetXX'),
					anchor: "99%",
					autoHeight: true,
					//layout:'column',
					defaults: {
						xtype: "textfield"
					},											
					items: [
						{   
						    xtype : "panel",
				            layout: 'table',
				            autoWidth : true,
							border: false,
							layoutConfig : {
								columns : 2
							},
							defaults: {
								border: false
							},
							items:[
							       {
							    	    xtype: "panel",
										width: 372,
										layout: "form",
										items:
										  [{
											xtype: 'textfield',
											name: "title",
											fieldLabel: lang('asetNum'),
											inputType: "text",
											allowBlank: false,
											width: 200
										  }]
									},{
										xtype: "panel",
										width: 672,
										layout: "form",
										items: [
											{
												xtype: 'textfield',
												name: "author",
												fieldLabel: lang('stdname'),
												allowBlank: false,
												width: 200
											}
										       ]
									   }
									]

						},{   
						    xtype : "panel",
				            layout: 'table',
				            autoWidth : true,
							border: false,
							layoutConfig : {
								columns : 2
							},
							defaults: {
								border: false
							},
							items:[
							       {
							    	    xtype: "panel",
										width: 372,
										layout: "form",
										items:
										  [{
											xtype: 'textfield',
											name: "title",
											fieldLabel: lang('assetType'),
											inputType: "text",
											allowBlank: false,
											width: 200
										  }]
									},{
										xtype: "panel",
										width: 672,
										layout: "form",
										items: [
											{
												xtype: "panel",
				    							layout: "form",
				    							border: false,
				    							items: [
				    								new CNOA.form.ComboBox({
				    									fieldLabel: lang('assetClass'),
				    									name: 'type',
				    									store: meetingroomType,
				    									hiddenName: 'type',
				    									valueField: 'name',
				    									displayField:'name',
				    									mode: 'local',
				    									width: 200,
				    									allowBlank:false,
				    									triggerAction:'all',
				    									editable: true,
				    									listeners:{
				    										select : function(th, record, index){
				    										}.createDelegate(this)
				    									}
				    								})
				    							]
											}
										       ]
									   }
									]

								},{   
					 		    xtype : "panel",
					            layout: 'table',
					            autoWidth : true,
								border: false,
								layoutConfig : {
									columns : 2
								},
								defaults: {
									border: false
								},
								items:[
								       {
								    	    xtype: "panel",
											width: 372,
											layout: "form",
											items:
											  [{
												xtype: 'textfield',
												name: "title",
												fieldLabel: lang('manufacture'),
												inputType: "text",
												allowBlank: false,
												width: 200
											  }]
										},{
											xtype: "panel",
											width: 672,
											layout: "form",
											items: [
												{
													xtype: "panel",
													layout: "table",
													fieldLabel: lang('factoryDate') + "<span class='cnoa_color_red'>*</span>",
													border:false,
													items:[
														{
															xtype: "datefield",
															width:125,
															allowBlank: false,
															name: "startdate",
															editable:false,
															format: "Y-m-d"
														},
														{
															xtype: "timefield",
															width:75,
															allowBlank: false,
															increment:5,
															name:"starttime",
															editable:false,
															format: "H:i",
															editable:true
														}
													]
												}
											       ]
										   }
										]

							},{   
							    xtype : "panel",
					            layout: 'table',
					            autoWidth : true,
								border: false,
								layoutConfig : {
									columns : 2
								},
								defaults: {
									border: false
								},
								items:[
								       {
								    	    xtype: "panel",
											width: 372,
											layout: "form",
											items:
											  [{
												xtype: 'textfield',
												name: "title",
												fieldLabel: lang('netValue'),
												inputType: "text",
												allowBlank: false,
												width: 200
											  }]
										},{
											xtype: "panel",
											width: 672,
											layout: "form",
											items: [
												{
													xtype: 'numberfield',
													name: "author",													
													fieldLabel: lang('usefulYear'),
													allowBlank: false,
													inputType: "text",
													width: 200
												}
											       ]
										   }
										]

						 },{   
							    xtype : "panel",
					            layout: 'table',
					            autoWidth : true,
								border: false,
								layoutConfig : {
									columns : 2
								},
								defaults: {
									border: false
								},
								items:[
								       {
								    	    xtype: "panel",
											width: 372,
											layout: "form",
											items:
											  [{
												xtype: 'textfield',
												name: "title",
												fieldLabel: lang('cost1'),
												inputType: "text",
												allowBlank: false,
												width: 200
											  }]
										},{
											xtype: "panel",
											width: 672,
											layout: "form",
											items: [
												{
													xtype: 'textfield',
													name: "author",
													fieldLabel: lang('netValue1'),
													allowBlank: false,
													inputType: "text",
													width: 200
												}
											       ]
										   }
										]

						 },{   
							    xtype : "panel",
					            layout: 'table',
					            autoWidth : true,
								border: false,
								layoutConfig : {
									columns : 2
								},
								defaults: {
									border: false
								},
								items:[
								       {
								    	    xtype: "panel",
											width: 372,
											layout: "form",
											items:
											  [{
											   xtype: "numberfield",
						    				   readOnly:false,   				  										
												name: "title",
												fieldLabel: lang('quantity'),
												inputType: "text",
												allowBlank: false,
												width: 200
											  }]
										},{
											xtype: "panel",
											width: 672,
											layout: "form",
											items: [
												{
													xtype: "panel",
													layout: "table",
													fieldLabel: lang('useDate') + "<span class='cnoa_color_red'>*</span>",
													border:false,
													items:[
														{
															xtype: "datefield",
															width:125,
															allowBlank: false,
															name: "startdate",
															editable:false,
															format: "Y-m-d"
														},
														{
															xtype: "timefield",
															width:75,
															allowBlank: false,
															increment:5,
															name:"starttime",
															editable:false,
															format: "H:i",
															editable:true
														}
													]
												}
											       ]
										   }
										]

							},{   
							    xtype : "panel",
					            layout: 'table',
					            autoWidth : true,
								border: false,
								layoutConfig : {
									columns : 2
								},
								defaults: {
									border: false
								},
								items:[
								       {
								    	    xtype: "panel",
											width: 372,
											layout: "form",
											items:
											  [{
												xtype: 'textfield',
												name: "title",
												fieldLabel: lang('principal'),
												inputType: "text",
												allowBlank: false,
												width: 200
											  }]
										},{
											xtype: "panel",
											width: 672,
											layout: "form",
											items: [
												{
													xtype: "panel",
					    							layout: "form",
					    							border: false,
					    							items: [
					    								new CNOA.form.ComboBox({
					    									fieldLabel: lang('useDept'),
					    									name: 'type',
					    									store: meetingroomType,
					    									hiddenName: 'type',
					    									valueField: 'name',
					    									//helpTip:"",
					    									displayField:'name',
					    									mode: 'local',
					    									width: 200,
					    									allowBlank:false,
					    									triggerAction:'all',
					    									editable: true,
					    									listeners:{
					    										select : function(th, record, index){
					    										}.createDelegate(this)
					    									}
					    								})
					    							]
												}
											       ]
										   }
										]

						 },{   
							    xtype : "panel",
					            layout: 'table',
					            autoWidth : true,
								border: false,
								layoutConfig : {
									columns : 3
								},
								defaults: {
									border: false
								},
								items:[
								        {
								    	    xtype: "panel",
											width: 336,
											layout: "form",
											items:
											  [{
												xtype: 'textfield',
												name: "title",
												fieldLabel: lang('monthOldValue'),
												inputType: "text",
												allowBlank: false,
												width: 200
											  }]
										},{
											xtype: "btnForPoepleSelectorSingle",
											style: "margin-left:0px",
											text: lang('computing')										
						 			    },{
											xtype: "panel",
											width: 672,
											layout: "form",
											items: [
												{
													xtype: "panel",
					    							layout: "form",
					    							border: false,
					    							items: [
					    								new CNOA.form.ComboBox({
					    									fieldLabel: lang('useStatus'),
					    									name: 'type',
					    									//store: meetingroomType,
					    									hiddenName: 'type',
					    									valueField: 'name',
					    									//helpTip:"",
					    									displayField:'name',
					    									mode: 'local',
					    									width: 200,
					    									allowBlank:false,
					    									triggerAction:'all',
					    									editable: true,
					    									listeners:{
					    										select : function(th, record, index){
					    										}.createDelegate(this)
					    									}
					    								})
					    							]
												}
											       ]
										   }
										]

						 }, {
							xtype: "panel",
							layout: "form",
							border: false,
							items: [
								new CNOA.form.ComboBox({
									fieldLabel: lang('wayIncrease'),
									name: 'mgruid',
									allowBlank:false,
									store: _this.getMeetingRoomMgrerStore,
									hiddenName: 'mgruid',
									valueField: 'mgruid',
									helpTip: lang('assetIncreaseWay'),
									displayField: 'name',
									mode: 'local',
									width: 230,
									allowBlank:false,
									triggerAction:'all',
									forceSelection: true,
									editable: false,
									listeners:{
										select : function(th, record, index){
										}.createDelegate(this)
									}
								})
							]
						},{
							xtype:"textarea",
							fieldLabel: lang('remark'),
							name:"otherattend",
							width: 480,
							height: 100		
						},
						
						new Ext.BoxComponent({
							autoEl: {
								tag: 'div',
								style: "margin-left:125px;margin-top:5px;margin-bottom:5px;color:#676767;",
								html: lang('reminCanFillAgain')
							}
						}),{
							xtype: "flashfile",
							fieldLabel: lang('attach'),
							name: "attach",
							inputPre: "filesUpload"
						}
						//this.smsSender
					]
				}
			]}
          
			if(type=="change"){
				var baseField = [
					{
						xtype: "fieldset",
						title: lang('assetBasicXX'),
						anchor: "99%",
						autoHeight: true,
						//layout:'column',
						defaults: {
							xtype: "textfield"
						},											
						items: [
							{   
							    xtype : "panel",
					            layout: 'table',
					            autoWidth : true,
								border: false,
								layoutConfig : {
									columns : 2
								},
								defaults: {
									border: false
								},
								items:[
								       {
								    	    xtype: "panel",
											width: 372,
											layout: "form",
											items:
											  [{
												xtype: 'textfield',
												name: "title",
												fieldLabel: lang('asetNum'),
												inputType: "text",
												allowBlank: false,
												width: 200
											  }]
										},{
											xtype: "panel",
											width: 672,
											layout: "form",
											items: [
												{
													xtype: 'textfield',
													name: "author",
													//readOnly: true,
													fieldLabel: lang('stdname'),
													allowBlank: false,
													//inputType: "text",
													width: 200
												}
											       ]
										   }
										]

							},{   
							    xtype : "panel",
					            layout: 'table',
					            autoWidth : true,
								border: false,
								layoutConfig : {
									columns : 2
								},
								defaults: {
									border: false
								},
								items:[
								       {
								    	    xtype: "panel",
											width: 372,
											layout: "form",
											items:
											  [{
												xtype: 'textfield',
												name: "title",
												fieldLabel: lang('assetType'),
												inputType: "text",
												allowBlank: false,
												width: 200
											  }]
										},{
											xtype: "panel",
											width: 672,
											layout: "form",
											items: [
												{
													xtype: "panel",
					    							layout: "form",
					    							border: false,
					    							items: [
					    								new CNOA.form.ComboBox({
					    									fieldLabel: lang('assetClass'),
					    									name: 'type',
					    									store: meetingroomType,
					    									hiddenName: 'type',
					    									valueField: 'name',
					    									//helpTip:"",
					    									displayField:'name',
					    									mode: 'local',
					    									width: 200,
					    									allowBlank:false,
					    									triggerAction:'all',
					    									editable: true,
					    									listeners:{
					    										select : function(th, record, index){
					    										}.createDelegate(this)
					    									}
					    								})
					    							]
												}
											       ]
										   }
										]

									},{   
						 		    xtype : "panel",
						            layout: 'table',
						            autoWidth : true,
									border: false,
									layoutConfig : {
										columns : 2
									},
									defaults: {
										border: false
									},
									items:[
									       {
									    	    xtype: "panel",
												width: 372,
												layout: "form",
												items:
												  [{
													xtype: 'textfield',
													name: "title",
													fieldLabel: lang('manufacture'),
													inputType: "text",
													allowBlank: false,
													width: 200
												  }]
											},{
												xtype: "panel",
												width: 672,
												layout: "form",
												items: [
													{
														xtype: "panel",
														layout: "table",
														fieldLabel:lang('factoryDate') + "<span class='cnoa_color_red'>*</span>",
														border:false,
														items:[
															{
																xtype: "datefield",
																width:125,
																allowBlank: false,
																name: "startdate",
																editable:false,
																format: "Y-m-d"
															},
															{
																xtype: "timefield",
																width:75,
																allowBlank: false,
																increment:5,
																name:"starttime",
																editable:false,
																format: "H:i",
																editable:true
															}
														]
													}
												       ]
											   }
											]

								},{   
								    xtype : "panel",
						            layout: 'table',
						            autoWidth : true,
									border: false,
									layoutConfig : {
										columns : 2
									},
									defaults: {
										border: false
									},
									items:[
									       {
									    	    xtype: "panel",
												width: 372,
												layout: "form",
												items:
												  [{
													xtype: 'textfield',
													name: "title",
													fieldLabel: lang('netValue'),
													inputType: "text",
													allowBlank: false,
													width: 200
												  }]
											},{
												xtype: "panel",
												width: 672,
												layout: "form",
												items: [
													{
														xtype: 'textfield',
														name: "author",													
														fieldLabel: lang('usefulYear'),
														allowBlank: false,
														inputType: "text",
														width: 200
													}
												       ]
											   }
											]

							 },{   
								    xtype : "panel",
						            layout: 'table',
						            autoWidth : true,
									border: false,
									layoutConfig : {
										columns : 2
									},
									defaults: {
										border: false
									},
									items:[
									       {
									    	    xtype: "panel",
												width: 372,
												layout: "form",
												items:
												  [{
													xtype: 'textfield',
													name: "title",
													fieldLabel: lang('cost1'),
													inputType: "text",
													allowBlank: false,
													width: 200
												  }]
											},{
												xtype: "panel",
												width: 672,
												layout: "form",
												items: [
													{
														xtype: 'textfield',
														name: "author",
														fieldLabel: lang('netValue1'),
														allowBlank: false,
														inputType: "text",
														width: 200
													}
												       ]
											   }
											]

							 },{   
								    xtype : "panel",
						            layout: 'table',
						            autoWidth : true,
									border: false,
									layoutConfig : {
										columns : 2
									},
									defaults: {
										border: false
									},
									items:[
									       {
									    	    xtype: "panel",
												width: 372,
												layout: "form",
												items:
												  [{
													xtype: 'textfield',
													name: "title",
													fieldLabel: lang('quantity'),
													inputType: "text",
													allowBlank: false,
													width: 200
												  }]
											},{
												xtype: "panel",
												width: 672,
												layout: "form",
												items: [
													{
														xtype: "panel",
														layout: "table",
														fieldLabel:lang('useDate') + "<span class='cnoa_color_red'>*</span>",
														border:false,
														items:[
															{
																xtype: "datefield",
																width:125,
																allowBlank: false,
																name: "startdate",
																editable:false,
																format: "Y-m-d"
															},
															{
																xtype: "timefield",
																width:75,
																allowBlank: false,
																increment:5,
																name:"starttime",
																editable:false,
																format: "H:i",
																editable:true
															}
														]
													}
												       ]
											   }
											]

								},{   
								    xtype : "panel",
						            layout: 'table',
						            autoWidth : true,
									border: false,
									layoutConfig : {
										columns : 2
									},
									defaults: {
										border: false
									},
									items:[
									       {
									    	    xtype: "panel",
												width: 372,
												layout: "form",
												items:
												  [{
													xtype: 'textfield',
													name: "title",
													fieldLabel: lang('principal'),
													inputType: "text",
													allowBlank: false,
													width: 200
												  }]
											},{
												xtype: "panel",
												width: 672,
												layout: "form",
												items: [
													{
														xtype: "panel",
						    							layout: "form",
						    							border: false,
						    							items: [
						    								new CNOA.form.ComboBox({
						    									fieldLabel: lang('useDept'),
						    									name: 'type',
						    									store: meetingroomType,
						    									hiddenName: 'type',
						    									valueField: 'name',
						    									//helpTip:"",
						    									displayField:'name',
						    									mode: 'local',
						    									width: 200,
						    									allowBlank:false,
						    									triggerAction:'all',
						    									editable: true,
						    									listeners:{
						    										select : function(th, record, index){
						    										}.createDelegate(this)
						    									}
						    								})
						    							]
													}
												       ]
											   }
											]

							 },{   
								    xtype : "panel",
						            layout: 'table',
						            autoWidth : true,
									border: false,
									layoutConfig : {
										columns : 2
									},
									defaults: {
										border: false
									},
									items:[
									        {
									    	    xtype: "panel",
												width: 372,
												layout: "form",
												items:
												  [{
													xtype: 'textfield',
													name: "title",
													fieldLabel: lang('useHuman'),
													inputType: "text",
													allowBlank: false,
													width: 200
												  }]
											},{
												xtype: "panel",
												width: 672,
												layout: "form",
												items: [
													{
														xtype: "panel",
						    							layout: "form",
						    							border: false,
						    							items: [
						    								new CNOA.form.ComboBox({
						    									fieldLabel: lang('useStatus'),
						    									name: 'type',
						    									//store: meetingroomType,
						    									hiddenName: 'type',
						    									valueField: 'name',
						    									//helpTip:"",
						    									displayField:'name',
						    									mode: 'local',
						    									width: 200,
						    									allowBlank:false,
						    									triggerAction:'all',
						    									editable: true,
						    									listeners:{
						    										select : function(th, record, index){
						    										}.createDelegate(this)
						    									}
						    								})
						    							]
													}
												       ]
											   }
											]

							 }, {
								xtype: "panel",
								layout: "form",
								border: false,
								items: [
									new CNOA.form.ComboBox({
										fieldLabel: lang('wayIncrease'),
										name: 'mgruid',
										allowBlank:false,
										store: _this.getMeetingRoomMgrerStore,
										hiddenName: 'mgruid',
										valueField: 'mgruid',
										helpTip:lang('assetIncreaseWay'),
										editable: true,
										displayField:'name',
										mode: 'local',
										width: 230,
										allowBlank:false,
										triggerAction:'all',
										//forceSelection: true,
										//editable: false,
										listeners:{
											select : function(th, record, index){
											}.createDelegate(this)
										}
									})
								]
							},{
								xtype:"textarea",
								fieldLabel: lang('remark'),
								name:"otherattend",
								width: 480,
								height: 100		
							},
							
							new Ext.BoxComponent({
								autoEl: {
									tag: 'div',
									style: "margin-left:125px;margin-top:5px;margin-bottom:5px;color:#676767;",
									html: lang('reminCanFillAgain')
								}
							}),{
								xtype: "flashfile",
								fieldLabel: lang('attach'),
								name: "attach",
								inputPre: "filesUpload"
							}
							//this.smsSender
						]
					}
			]}
          
			this.formPanel = new Ext.form.FormPanel({
				border: false,
				labelWidth: 120,
				labelAlign: 'right',
				region : "center",
				autoScroll : true,
				waitMsgTarget: true,
				bodyStyle: "padding:5px 10px 10px 10px;",
				items:[
					{
						xtype: "panel",
						border: false,
						bodyStyle: "padding:10px",
						layout: "form",
						region: "center",
						autoScroll: true,
						items: [baseField]
					}
				]
			});
			
			var fields = [
				{name : "mid"},
				{name : "name"},
				{name : "address"},
				{name : "contain"},
				{name : "descripts"}
			];
			
			var searchEmptyRoomStore = new Ext.data.Store({
				proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getEmptyMeetingroom", disableCaching: true}),   
				reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
				listeners:{
					exception : function(th, type, action, options, response, arg){
						var result = Ext.decode(response.responseText);
						if(result.failure){
							CNOA.msg.alert(result.msg);
						}
					}
				}});
			
			var sm = new Ext.grid.CheckboxSelectionModel({
				singleSelect:false
			});

			var colModel = new Ext.grid.ColumnModel([		
			]);

			var grid = new Ext.grid.GridPanel({
				region : "north",
				height : 0,
				store : searchEmptyRoomStore,
				loadMask : {msg: lang('waiting')},
				cm : colModel,
				sm : sm,
				
				viewConfig: {
				}
			});
			if(type=="add"){
			this.applyWin = new Ext.Window({				
				title:lang('increaseAsset'),			
				width:820,
				height: makeWindowHeight(550),
				modal:true,
				layout:"border",
				//resizable:false,
				border:false,
				items:[grid,_this.formPanel],
				buttons:[
					{
						text: lang('zhijieADD'),
						handler:function(){
							_this.submit(aid, type);
						}
					},{
						text:lang('purchasingRequistion'),
						//id : show-btn,
						handler:function(){
							_this.submitwindow(aid, type);
						}
					},{
						text:lang('import2'),
						handler:function(){
							_this.submit(aid, type);
						}
					},
					{
						text:lang('cancel'),
						handler:function(){
							_this.applyWin.close();
						}
					}					
				]				
			}).show();}
			if(type=="change"){
				this.applyWin = new Ext.Window({				
					title:lang('assetChange'),			
					width:820,
					height:makeWindowHeight(550),
					modal:true,
					layout:"border",
					//resizable:false,
					border:false,
					items:[grid,_this.formPanel],
					buttons:[
						{
							text:lang('assetChange'),
							handler:function(){
								_this.submit(aid, "change");
															}
						},
						{
							text:lang('cancel'),
							handler:function(){
								_this.applyWin.close();
							}
						}					
					]			
				}).show();
            }
			if(type == "edit"){
				_this.loadFormData(aid);
				_this.type = "edit";
			}
		},
		submit : function(aid, type){
			var _this = this;
			var f = _this.formPanel.getForm();		
			if (_this.formPanel.getForm().isValid()) {
				_this.formPanel.getForm().submit({
					url: _this.baseUrl+"&task=submit&from="+type,
					waitMsg: lang('waiting'),
					method: 'POST',
					params : { aid : aid },
					success: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							_this.applyWin.close();
							//_this.submitwindow.close();
							_this.store.reload();
						});
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}.createDelegate(this)
				});
			}
			
		},
			
		showDetails : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			return "<a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.showDetailsWin("+record.data.aid+")'>"+value+"</a><br />"+record.data.plan+"人";
		},

			
		showMeetingroomDetails : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			return "<a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.showMeetingroomDetailsWin("+record.data.mid+")'>"+value+"</a>";
		},
		
		formatTime : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			return record.data.stime + "<br />" + record.data.etime;
		},
		
		formatCheck : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			return record.data.checkman + "<br />" + record.data.checktime;
		},
		
		showMeetingroomDetailsWin : function(mid){
			var _this = this;
			var src = _this.baseUrl+"&task=viewMeetingRoomDetails&mid="+mid;

			

			var win = Ext.id();
			var panel = new Ext.Panel({
				border:false,
				html: '<div style="width:100%;height:100%;"><iframe scrolling=auto frameborder=0 width=100% height=100% id="'+win+'"></iframe></div>',
				listeners:{
					afterrender:function(){
						Ext.getDom(win).contentWindow.location.href = src;
					}
				}
			});
	
		},
		
		operate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
			var _this = this;
			if(_this.storeBar.storeType == "cancel" || _this.storeBar.storeType == "waiting"){
				return "<a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.window("+value+", \"edit\")'>" + lang('modify') + "</a> / <a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.deleteMeeting("+value+")'><span class='cnoa_color_red'>" + lang('del') + "</span></a>";
			}else{
				return "<a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.cancelMeeting("+value+")'>" + lang('cancel') + "</a> / <a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.deleteMeeting("+value+")'><span class='cnoa_color_red'>" + lang('del') + "</span></a>";
			}
			//return "<a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.delay'>延迟</a> / <a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.cancelMeeting'>取消</a> / <a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.deleteMeeting'><span class='cnoa_color_red'>" + lang('del') + "</span></a>";
		}
	}
Ext.onReady(function() {
	CNOA_adm_ammanage_manage = new CNOA_adm_ammanage_manageClass();
	Ext.getCmp(CNOA.adm.ammanage.manage.parentID).add(CNOA_adm_ammanage_manage.mainPanel);
	Ext.getCmp(CNOA.adm.ammanage.manage.parentID).doLayout();
});

