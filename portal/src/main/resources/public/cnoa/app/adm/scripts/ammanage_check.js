//主面板
var CNOA_adm_ammanage_check, CNOA_adm_ammanage_checkClass;
    CNOA_adm_ammanage_checkClass = CNOA.Class.create();
    CNOA_adm_ammanage_checkClass.prototype = {
    		init : function(){
    			var _this = this;
    			
    			var ID_SEARCH_NAME        = Ext.id();
    			var ID_SEARCH_TITLE       = Ext.id();
    			var ID_SEARCH_TYPE        = Ext.id();
    			var ID_SEARCH_MODEL       = Ext.id();
    			var ID_SEARCH_MAN         = Ext.id();
    			var ID_SEARCH_CLASS       = Ext.id();
    			var ID_SEARCH_USER        = Ext.id();

    			this.baseUrl = "index.php?app=adm&func=ammanage&action=check";

    			this.storeBar = {
    				storeType   : "nopass",
					type 		: ""
    			};
    			
				this.storeBar.type =  "myapply";
				this.storeBar.storeType =  "nopass";
				
				
    			this.fields = [
    				{name: "aid"},
    				{name : "mid"},
    				{name: "name"},
    				{name: "title"},
    				{name: "meetingroom"},
    				{name: "plan"},
    				{name: "type"},
    				{name: "mgrman"},
    				{name: "markman"},
    				{name: "meetingroom"},
    				{name: "stime"},
    				{name: "etime"},
    				{name: "checkman"},
    				{name: "checktime"}
    			];
    			
    			this.store = new Ext.data.Store({
    				proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getJsonData", disableCaching: true}),   
    				reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
    				listeners:{
    					exception : function(th, type, action, options, response, arg){
    						var result = Ext.decode(response.responseText);
    						if(result.failure){
    							CNOA.msg.alert(result.msg);
    						}
    					}
    				}
    			});
    			
    			this.store.load();
    			
    			this.sm = new Ext.grid.CheckboxSelectionModel({
    				singleSelect: false
    			});
    			
    			this.pagingBar = new Ext.PagingToolbar({
    				displayInfo:true,emptyMsg: lang('pagingToolbarEmptyMsg'), displayMsg: lang('showDataTotal2'),   
    				store: this.store,
    				pageSize:15,
    				listeners:{
    					"beforechange" : function(th, params){
    						if(_this.storeBar.storeType != ''){
    							params.storeType = _this.storeBar.storeType;
    						}
    						if(_this.storeBar.type != ''){
    							params.name = _this.storeBar.name;
    						}
    					}
    				}
    			});
    			
    			this.colModel = new Ext.grid.ColumnModel([
    				new Ext.grid.RowNumberer(),
    				this.sm,
    				{header: "aid", dataIndex: 'aid', hidden: true},
    				{header: lang('asetNum'), dataIndex: 'name', width: 100, sortable: true, menuDisabled :true, renderer : this.showDetails.createDelegate(this)},
    				{header: lang('stdname'), dataIndex: 'title', width: 100, sortable: true, menuDisabled :true},
    				{header: lang('belongDept'), dataIndex: 'meetingroom', width: 100, sortable: true, menuDisabled :true, renderer : _this.showMeetingroomDetails},
    				{header: lang('assetTye'), dataIndex: 'type', width: 100, sortable: true, menuDisabled :true},
    				{header: lang('proposer'), dataIndex: 'mgrman', width: 100, sortable: true, menuDisabled :true},
    				{header: lang('principal'), dataIndex: 'markman', width: 100, sortable: true, menuDisabled :true},
    				{header: lang('appDate'), dataIndex: 'markman', width: 100, sortable: true, menuDisabled :true},
    				{header: lang('auditStatus'), dataIndex: 'markman', width: 100, sortable: true, menuDisabled :true},
    				{header: lang('opt'), dataIndex: 'aid', width:180, sortable: true, menuDisabled :true, renderer : this.operate.createDelegate(this)},
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
    				tbar:[
    					{
    						text:lang('refresh'),
    						iconCls:'icon-system-refresh',
    						handler:function(button, event){
    							_this.store.reload();
    						}
    					},{
    						iconCls: 'icon-utils-s-delete',
    						text: lang('del'),
    						tooltip: lang('delRecord'),
    						handler: function(btn){
    							_this.editor.stopEditing();   						
    						}
    					},
    					
    					{
    						handler : function(button, event) {
								
    							_this.storeBar.storeType = "nopass";
    							_this.store.load({params: _this.storeBar});
    						}.createDelegate(this),
    						text: lang('notReview'),
    						enableToggle: true,
    						pressed: true,
    						allowDepress: false,
    						iconCls: 'icon-roduction',  
    						toggleGroup: "adm_am_manage_check"
    					},
    					{
    						handler : function(button, event) {
    							_this.storeBar.storeType = "checking";
    							_this.store.load({params: _this.storeBar});
    						}.createDelegate(this),
    						iconCls: 'icon-roduction',
    						enableToggle: true,
    						pressed: false,
    						allowDepress: false,
    						toggleGroup: "adm_am_manage_check",
    						text : lang('inAudit')
    					},
    					{
    						handler : function(button, event) {
    							_this.storeBar.storeType = "pass";
    							_this.store.load({params: _this.storeBar});
    						}.createDelegate(this),
    						enableToggle: true,
    						allowDepress: false,
    						toggleGroup: "adm_am_manage_check",
    						iconCls: 'icon-roduction',
    						text : lang('audited')
    					},    					
    					"->",{xtype: 'cnoa_helpBtn', helpid: 4001}
    				],
    				bbar: this.pagingBar
    			});
    			
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
							{
								handler : function(button, event) {
									_this.storeBar.type = "myapply";
									if(_this.storeBar.storeType == "nopass"){
										__this.store.load({params: _this.storeBar});
									}
									//
								}.createDelegate(this),
								iconCls: 'icon-roduction',
								enableToggle: true,
								pressed: true,
								allowDepress: false,
								toggleGroup: "adm_am_manage_check_type",
								text : lang('myApp')
							},
							{
								handler : function(button, event) {
									_this.storeBar.type = "apply";
									//_this.store.load({params: _this.storeBar});
								}.createDelegate(this),
								iconCls: 'icon-roduction',
								enableToggle: true,
								allowDepress: false,
								toggleGroup: "adm_am_manage_check_type",
								text : lang('appManage')
							},
							{
								handler : function(button, event) {
									_this.storeBar.type = "out";
									//_this.store.load({params: _this.storeBar});
								}.createDelegate(this),
								enableToggle: true,
								allowDepress: false,
								toggleGroup: "adm_am_manage_check_type",
								iconCls: 'icon-roduction',
								text : lang('lendReview')
							},
							{
								handler : function(button, event) {
									_this.storeBar.type = "back";
									//_this.store.load({params: _this.storeBar});
								}.createDelegate(this),
								enableToggle: true,
								allowDepress: false,
								toggleGroup: "adm_am_manage_check_type",
								iconCls: 'icon-roduction',
								text : lang('renturnAudit')
							},
							{
								handler : function(button, event) {
									_this.storeBar.type = "check";
									//_this.store.load({params: _this.storeBar});
								}.createDelegate(this),
								enableToggle: true,
								allowDepress: false,
								toggleGroup: "adm_am_manage_check_type",
								iconCls: 'icon-roduction',
								text : lang('accOfAudit')
							},
							{
								handler : function(button, event) {
									_this.storeBar.type = "clean";
									//_this.store.load({params: _this.storeBar});
								}.createDelegate(this),
								enableToggle: true,
								allowDepress: false,
								toggleGroup: "adm_am_manage_check_type",
								iconCls: 'icon-roduction',
								text : lang('cleanAudit')
							},
							{
								handler : function(button, event) {
									_this.storeBar.type = "change";
									//_this.store.load({params: _this.storeBar});
								}.createDelegate(this),
								enableToggle: true,
								allowDepress: false,
								toggleGroup: "adm_am_manage_check_type",
								iconCls: 'icon-roduction',
								text : lang('changeReview')
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
    			
    			_this.getMeetingRoomListStore.load();
    			_this.getMeetingRoomCheckerStore.load();
    		
    			

    						
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
    			//return "<a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.delay'>延迟</a> / <a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.cancelMeeting'>取消</a> / <a href='javascript:void(0)' onclick='CNOA_meeting_mgr_apply.deleteMeeting'><span class='cnoa_color_red'>删除</span></a>";
    		}

    	}

    Ext.onReady(function() {
    	CNOA_adm_ammanage_check = new CNOA_adm_ammanage_checkClass();
    	Ext.getCmp(CNOA.adm.ammanage.check.parentID).add(CNOA_adm_ammanage_check.mainPanel);
    	Ext.getCmp(CNOA.adm.ammanage.check.parentID).doLayout();
    });
