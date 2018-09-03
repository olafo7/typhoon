//定义全局变量：
var CNOA_doc_send_monit_listClass, CNOA_doc_send_monit_list;


/**
* 主面板-列表
*
*/
CNOA_doc_send_monit_listClass = CNOA.Class.create();
CNOA_doc_send_monit_listClass.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = "index.php?app=doc&func=send&action=monit";

		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		
		//查询
		this.ID_find_name		= Ext.id();
		this.ID_find_title		= Ext.id();
		this.ID_find_beginTime	= Ext.id();
		this.ID_find_endTime	= Ext.id(); 
		this.ID_find_status		= Ext.id();
		this.ID_find_buildUser	= Ext.id();
		this.ID_find_getUser	= Ext.id();
		
		this.searchParams = {
			name: '',
			title: '',
			beginTime: '',
			endTime: '',
			status: 0,
			buildUser: '',
			doing: 1
		};
		
		this.fields = [
			{name:"lid"},
			{name:"ulid"},
			{name:"name"},
			{name:"title"},
			{name:"level"},
			{name:"uid"},
			{name:"uname"},
			{name:"step", mapping: "stepText"},
			{name:"posttime"},
			{name:"allowOperate"},
			{name:"status"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
			listeners : {
				exception : function(th, type, action, options, response, arg){
					var result = Ext.decode(response.responseText);
					if(result.failure){
						CNOA.msg.alert(result.msg);
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
			{header: "ulid", dataIndex: 'ulid', hidden: true},
			{header: lang('flowNumber'), dataIndex: 'name', width: 160, sortable: true, menuDisabled :true},
			{header: lang('title'), dataIndex: 'title', width: 200, sortable: true, menuDisabled :true},
			{header: lang('initiator'), dataIndex: 'uname', width: 120, sortable: true, menuDisabled :true},
			{header: lang('importantGrade'), dataIndex: 'level', width: 60, sortable: false,menuDisabled :true},
			{header: lang('currentStep'), dataIndex: 'step', width: 70, sortable: false,menuDisabled :true},
			{header: lang('status'), dataIndex: 'status', width: 50, sortable: false,menuDisabled :true, renderer:this.makeStatus.createDelegate(this)},
			{header: lang('launchingDate'), dataIndex: 'posttime', width: 110, sortable: false,menuDisabled :true},
			{header: lang('opt'), dataIndex: 'ulid', width: 160, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){					
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
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			region: "center",
			tbar : new Ext.Toolbar({
				items: [
					{
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},{
						handler : function(button, event) {
							_this.doSearch();
						}.createDelegate(this),
						iconCls: 'icon-hr-search',
						text : lang('search')
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 57}
				]
			}),
			bbar: this.pagingBar
		});

		this.centerPanel = new Ext.Panel({
			region: "center",
			layout: "card",
			activeItem: 0,
			items: [this.grid]
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel]//, this.bottomPanel
		});
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;

		var h  = "";
		
			h += "<a href='javascript:void(0);' onclick='CNOA_doc_api_flow.showFlow("+value+");' ext:qtip='" + lang('viewDetail') + "'>" + lang('view') + "</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_doc_api_flow.viewFlowEvent("+value+", \""+rd.name + "[" +rd.title+"]\");' ext:qtip='" + lang('checkMarkRecord') + "'>" + lang('markRecord') + "</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_doc_api_flow.showReaderWindow("+value+");' ext:qtip='" + lang('checkRecord') + "'>" + lang('reviewRecord') + "</a>";
			h += "&nbsp;&nbsp;";
			h += "<br />";
			h += rs == 1 ? "<a href='javascript:void(0);' onclick='CNOA_doc_send_monit_list.urge("+value+");' ext:qtip='" + lang('cuibanPerson') + "'>" + lang('reminder') + "</a>" : "<span class='cnoa_color_gray'>" + lang('reminder') + "</span>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_doc_api_flow.dispenseFlow("+value+");' ext:qtip='" + lang('othersView') + "'>" + lang('distribute') + "</a>";
			h += "&nbsp;&nbsp;";
		return h;
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>" + lang('unsent') + "</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>" + lang('checkIn') + "</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>" + lang('yibanli') + "</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>" + lang('retirePieces') + "</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_gray'>" + lang('undone') + "</span>";}
		return h;
	},
	
	//催办流程
	urge : function(ulid){
		var _this = this;

		var urgeDoc = function(say){
			Ext.Ajax.request({
				url: _this.baseUrl + "&task=urgeDoc",
				method: 'POST',
				params: {ulid: ulid, say: say},
				success: function(r) {
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						CNOA.msg.alert(result.msg, function(){
							//_this.close();
						});
					}else{
						CNOA.msg.alert(result.msg, function(){
							//_this.close();
						});
					}
				}
			});
		};
		Ext.MessageBox.show({
			title: lang('reminder'),
			msg: lang('enterCuiBan'),
			width:400,
			buttons: Ext.MessageBox.OKCANCEL,
			multiline: true,
			fn: function(btn, say){
				if(btn == "ok"){
					urgeDoc(say);
				}
			}
		});
	},
	
	doSearch : function(){
		var _this = this;
		
		var pnlSearch = new Ext.form.FormPanel({
			border: false,
			labelWidth: 80,
			bodyStyle: 'padding: 20px',
			defaults:{
				style:{
					marginBottom: '5px'
				}
			},
			items: [
				{
					xtype: 'textfield',
					fieldLabel: lang('flowNumber'),
					width: 200,
					id: _this.ID_find_title
				},
				{
					xtype: 'textfield',
					fieldLabel: lang('title'),
					width: 200,
					id: _this.ID_find_name
				},
				{
					xtype: 'combo',
					fieldLabel: lang('nowStatus'),
					id: _this.ID_find_status,
					editable: false,
					width: 200,
					mode: 'local',
					triggerAction: 'all',
					valueField: 'value',
					displayField: 'display',
					store: new Ext.data.ArrayStore({
						fields: ['value', 'display'],
						data: [[-99, '　'], [-1, lang('AlreadyDel')], [0, lang('unpubilsh')], [1, lang('checkIn')], [2, lang('yibanli')], [3, lang('retirePieces')], [4, lang('undone')]]
					}),
					value: -99
				},
				{
					xtype: "datefield",
					fieldLabel: lang('InStartTime'),
					width: 200,
					format: "Y-m-d",
					editable: false,
					allowBlank: true,
					id: _this.ID_find_beginTime
				},
				{
					xtype: "datefield",
					fieldLabel: lang('InFinalTime'),
					width: 200,
					format: "Y-m-d",
					editable: false,
					allowBlank: true,
					id: _this.ID_find_endTime
				},
				{
					xtype: 'hidden',
					id: _this.ID_find_buildUser
				},
				{
					xtype: "triggerForPeople",
					fieldLabel: lang('initiator'),
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					width: 200,
					id: _this.ID_find_getUser,								
					listeners: {
						"selected":function(th, uid, uname){
							Ext.getCmp(_this.ID_find_buildUser).setValue(uid);
						}
					}
				}
			]
		});
		
		var winSearch = new Ext.Window({
			title: lang('search'),
			modal: true,
			layout: 'fit',			
			width: 350,
			height: 286,
			items: [pnlSearch],
			buttons: [
				{
					text: lang('search'),
					iconCls: 'icon-order-s-accept',
					handler : function(){
						var form	= pnlSearch.getForm();
						if(!form.isValid()){
							CNOA.msg.alert(lang('trueInformation'));
							return;
						}

						_this.searchParams.name			= Ext.getCmp(_this.ID_find_title).getValue();
						_this.searchParams.title		= Ext.getCmp(_this.ID_find_name).getValue();
						_this.searchParams.beginTime	= Ext.getCmp(_this.ID_find_beginTime).getRawValue();
						_this.searchParams.endTime		= Ext.getCmp(_this.ID_find_endTime).getRawValue();
						_this.searchParams.status		= Ext.getCmp(_this.ID_find_status).getValue();
						_this.searchParams.buildUser	= Ext.getCmp(_this.ID_find_buildUser).getValue();
						_this.searchParams.doing		= "all";
						_this.store.load({params : _this.searchParams});
						
						winSearch.close();
					}
				},{
					text: lang('cancel'),
					iconCls: 'icon-order-s-close',
					handler : function(){
						winSearch.close();
					}
				}
			]
		}).show();
	}
}

Ext.onReady(function() {
	CNOA_doc_send_monit_list = new CNOA_doc_send_monit_listClass();
	Ext.getCmp(CNOA.doc.send.monit_list.parentID).add(CNOA_doc_send_monit_list.mainPanel);
	Ext.getCmp(CNOA.doc.send.monit_list.parentID).doLayout();
});