/**
 * 流程设计
 * 此文件已经废弃，程序中并未发现此文件的调用
 */
var CNOA_wf_set_flow_msofficeClass, CNOA_wf_set_flow_msoffice;
CNOA_wf_set_flow_msofficeClass = CNOA.Class.create();
CNOA_wf_set_flow_msofficeClass.prototype = {
	init : function(){
		var _this = this;
		
		this.flowId = CNOA.wf.set.flow.flowId;
		this.flowType = CNOA.wf.set.flow.flowType;
		this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow&flowId="+_this.flowId+"&flowType="+_this.flowType+"&"+getSessionStr();
		this.baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
	
		/*
		this.webofficeHtml  = '<object id="CNOA_WEBOFFICE" height="768" width="100%" style="left: 0px; TOP: 0px" classid="clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5" codebase="resources/weboffice/v6.0.5.0.cab#version=6,0,5,0">';
		this.webofficeHtml += '<param name="_ExtentX" value="6350">';
		this.webofficeHtml += '<param name="_ExtentY" value="6350">';
		this.webofficeHtml += '要显示模板内容，请切换到IE浏览器下查看';
		this.webofficeHtml += '</object>';
		*/

		this.webofficeHtml = CNOA.webObject.getWebOffice("100%", 768, 'left: 0px; TOP: 0px');
		
		this.ID_officeHtml	= Ext.id();
		this.ID_dealStep	= Ext.id();
		this.ID_dealStepUid = Ext.id();
		this.ID_getDealName = Ext.id();
		
		this.baseField = [
			{
				xtype: "fieldset",
				title: lang('flowStep'),
				autoHeight: true,
				items: [
					{
						xtype: "textarea",
						height: 35,
						anchor: "-20",
						style: "margin-top:4px;",
						id: _this.ID_dealStep,
						readOnly: true,
						fieldLabel: lang('step'),
						name: 'uname'
					},
					{
						xtype: 'hidden',
						id: _this.ID_getDealName,
						name: 'getname'
					},
					{
						xtype: "hidden",
						id: _this.ID_dealStepUid,
						name: "uid"
					},
					{
						xtype: 'button',
						text: lang('setFlowStep'),
						style: 'margin-left: 130px;',
						handler: function(button, event){
							this.setFlowStepWindow();
						}.createDelegate(this)
					},
					new Ext.BoxComponent({
						autoEl: {
							tag: 'div',
							style: "margin-left:130px;margin-top:5px;margin-bottom:5px;color:#676767;",
							html: lang('hostAccordingOther')
						}
					})
				]
			},
			{
				xtype: "fieldset",
				title: lang('templateContent'),
				items: [
					{
						xtype: 'panel',
						id: _this.ID_officeHtml,
						html: this.webofficeHtml,
						listeners: {
							afterrender : function(){
								try{
									var webObj = document.getElementById("CNOA_WEBOFFICE");
									webObj.OptionFlag &= 0xff7f;
									webObj.setCurrUserName(CNOA_USER_TRUENAME);
									if(_this.flowType == 1){
										webObj.LoadOriginalFile(_this.baseURI + _this.baseUrl + "&task=ms_loadTemplateFile&flowId="+CNOA.wf.set.flow.flowId, "doc");
									}else{
										webObj.LoadOriginalFile(_this.baseURI + _this.baseUrl + "&task=ms_loadTemplateFile&flowId="+CNOA.wf.set.flow.flowId, "xls");
									}
									webObj.ShowToolBar = false;
								}catch(e){}
							}
						}
					}
				]
			}
		];
		
		this.form = new Ext.form.FormPanel({
			labelWidth: 120,
			labelAlign: 'right',
			region : "center",
			waitMsgTarget: true,
			layout: "border",
			items:[
				{
					xtype: "panel",
					bodyStyle: "padding:10px;",
					border: false,
					autoScroll : true,
					region: "center",
					items: [this.baseField]
				}
			],
			tbar: [
				lang('editTemp') + "： ",
				{
					text: lang('save'),
					iconCls : "icon-btn-save",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submitMsOfficeData(_this.flowType);
					}
				},"-",
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					style: "margin-left:5px",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.closeTab();
					}
				}
			]
		});
		
		this.mainPanel = new Ext.Panel({
			layout: 'border',
			border: false,
			hideBorders: true,
			style: 'background-color:#fff;',
			autoScroll: true,
			items: [this.form]
		});
		this.loadFormData();
	},
	
	loadFormData : function(){
		var _this = this;
		
		this.form.getForm().load({
			url: _this.baseUrl+"&task=ms_loadStepDealData",
			method:'POST',
			success: function(form, action){		
			},
			failure: function(form, action){
				CNOA.msg.alert(action.result.msg, function(){
					
				});
			}.createDelegate(this)
		});
	},
	
	setFlowStepWindow : function(){
		var _this = this;
		
		this.ID_dealUid = Ext.id();
		
		this.fields = [
			{name:"stepId", mapping: 'stepId'},
			{name:"uname", mapping: 'uname'}
		];
		
		this.stepStore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=ms_loadStepDealList"}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: _this.fields})
		});
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "stepId", dataIndex: 'stepId', width: 20, sortable: false, hidden: true},
			{header: lang('truename'), dataIndex: 'uname', width: 200, sortable: true},
			{header: lang('opt'), dataIndex: 'stepId', width: 100, sortable: true, renderer: this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 50, menuDisabled: true,resizable: false}
		]);
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			loadMask: {msg: lang('waiting')},
			cm: _this.colModel,
			store: _this.stepStore,
			hideBorders: true,
			border: false,
			enableDragDrop: true,
			dropConfig: {
				appendOnly:true
			},
			ddGroup: "GridDD",
			listeners: {
				afterrender : function(grid){
					//定义拖动
					var ddrow = new Ext.dd.DropTarget(grid.getEl(), {
						ddGroup : 'GridDD',
						copy    : false,
						notifyDrop : function(dd, e, data) {
							// 选中了多少行
							//debugger
							var rows = data.selections;
							// 拖动到第几行
							var index = dd.getDragData(e).rowIndex;
							if (typeof(index) == "undefined") {
								return;
							}
							// 修改store
							for(i = 0; i < rows.length; i++) {
								var rowData = rows[i];
								if(!this.copy) _this.stepStore.remove(rowData);
								
								if(index== 0){
									rowData.data.orderNum -=1 ;
								}else if(index == _this.stepStore.data.items.length){
									rowData.data.id = _this.stepStore.data.items[index-1].data.id+1;
								}else{
									rowData.data.id = (_this.stepStore.data.items[index-1].data.id + _this.stepStore.data.items[index].data.id)/2
								}
								_this.stepStore.insert(index, rowData);
							}
						}
					});
				}
			},
			tbar: new Ext.Toolbar({
				items: [
					{
						xtype: "hidden",
						name: "uid",
						id: _this.ID_dealUid
					},
					{
						xtype: "btnForPoepleSelector",
						iconCls: 'icon-utils-s-add',
						autoWidth: true,
						dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
						text: lang('addHost'),
						listeners: {
							"selected" : function(th, data){
								var names = new Array();
								var uids = new Array();
								if (data.length>0){
									for (var i=0;i<data.length;i++){
										names.push(data[i].uname);
										uids.push(data[i].uid);
										var _rs = new Ext.data.Record({
											stepId: 0,
											uid: data[i].uid,
											uname : data[i].uname
										});
										_this.stepStore.add(_rs);
									}
								}
							}
						}
					},"-",
					/*{
						iconCls: 'icon-utils-s-delete',
						text: lang('del'),
						tooltip: '删除记录(按住Ctrl或Shift键可以一次选择多条记录进行删除)',
						handler: function(btn){
							_this.ms_deleteDealStep();
						}
					},"-",*/
					new Ext.BoxComponent({
						autoEl: {
							tag: 'div',
							html: '<span class=cnoa_color_gray>'+lang('dragcolumnPerson')+'</span>'
						}
					})
				]
			})
		});
		
		this.win = new Ext.Window({
			title: lang('setProcessStep'),
			width: 400,
			height: 300,
			border: false,
			modal:true,
			layout:"fit",
			items: this.grid,
			buttons:[
				{
					text:lang('ok'),
					iconCls: 'icon-btn-save',
					handler:function(){
						_this.saveDealStepInfo();
					}
				},
				{
					text:lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						_this.win.close()
					}
				}
			],
			listeners : {
				'close' : function(){
					Ext.getCmp(_this.ID_officeHtml).show();
				},
				'show' : function(){
					Ext.getCmp(_this.ID_officeHtml).hide();
				}
			}
		}).show();
		_this.loadStepDealer();
	},
	
	loadStepDealer: function(){
		var _this = this;
		
		var dl = Ext.getCmp(_this.ID_dealStepUid).getValue();
		var dname = Ext.getCmp(_this.ID_getDealName).getValue();
		dname = dname.split(",");
		dl = dl.split(",");
		if (dl.length>0){
			for (var i=0;i<dl.length;i++){
				var _rs = new Ext.data.Record({
					stepId: 0,
					uid: dl[i],
					uname: dname[i]
				});
				_this.stepStore.add(_rs);
				_this.grid.getView().refresh();
			}
		}
	},
	
	saveDealStepInfo : function(){
		var _this = this;
		
		var orders = new Array();
		var unames = new Array();
		var getnames = new Array();
		Ext.each(_this.stepStore.data.items, function(v, i){
			orders.push(v.data.uid);
			unames.push("["+v.data.uname+"]");
			getnames.push(v.data.uname);
		});
		if(unames != ""){
			Ext.getCmp(_this.ID_dealStep).setValue(lang('startPeople')+unames.join("->")+lang('endpeople'));
			Ext.getCmp(_this.ID_getDealName).setValue(getnames.join(","));
			Ext.getCmp(_this.ID_dealStepUid).setValue(orders.join(","));
			_this.win.close();
		}else{
			CNOA.msg.alert(lang('pleaseAddFlowDeal'));
		}
	},
	
	submitMsOfficeData : function(type){
		var _this = this;
		
		var saveOfficeFile = function(){
			try{
				var webObj = document.getElementById("CNOA_WEBOFFICE");
				var returnValue;
				webObj.HttpInit();
				webObj.HttpAddPostString("flowId", _this.flowId);
				if(type == 1){
					webObj.HttpAddPostCurrFile("msOffice", "");
				}else{
					webObj.HttpAddPostCurrFile("msOffice", "");
				}
				returnValue = webObj.HttpPost(_this.baseURI + _this.baseUrl + "&task=ms_submitMsOfficeData");
				CNOA.msg.alert(lang('saved'), function(){});
			}catch(e){
				alert("异常\r\nError:"+e+"\r\nError Code:"+e.number+"\r\nError Des:"+e.description);
			}
		};

		if (this.form.getForm().isValid()) {
			this.form.getForm().submit({
				url: _this.baseUrl + "&task=ms_submitStepDealData",
				waitMsg: lang('waiting'),
				params: {flowId : this.flowId, type : type},
				method: 'POST',	
				success: function(form, action) {
					saveOfficeFile();
					_this.closeTab();
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}.createDelegate(this)
			});
		}
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		return "<a href='javascript:void(0);' onclick='CNOA_wf_set_flow_msoffice.ms_deleteDealStep("+rowIndex+");'>" + lang('del') + "</a>";
	},
	
	ms_deleteDealStep : function(rowIndex){
		this.grid.getStore().removeAt(rowIndex);
		this.grid.getView().refresh();
	},
	
	closeTab : function(){
		var sid = CNOA.wf.set.flow.parentID.replace("docs-", "");
		mainPanel.closeTab(sid);
	}
}

