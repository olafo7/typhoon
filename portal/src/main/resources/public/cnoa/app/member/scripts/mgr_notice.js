var CNOA_member_mgr_notice, CNOA_member_mgr_noticeClass;

CNOA_member_mgr_noticeClass = CNOA.Class.create();
CNOA_member_mgr_noticeClass.prototype = {
	init : function(){
		var me = this;
		me.baseUrl = "index.php?app=member&func=mgr&action=notice";
		me.showType = 'birth';
		
		me.noticeListPanel = me.getNoticeListPanel();
		me.mainPanel = me.noticeListPanel;
	},
	
	getNoticeListPanel: function(){
		var me = this;
		
		me.ID_all = Ext.id();
		me.ID_birth = Ext.id();
		me.ID_marry = Ext.id();
		me.ID_clear = Ext.id();
		
		var fields = [
			{name: 'Code'},
			{name: 'CardNO'},
			{name: 'Name'},
			{name: 'BirthDate'},
			{name: 'UpdateDate'},
			{name: 'InsertDate'},
			{name: 'FeeTotal'},
			{name: 'status'}
		],
		//数据
		store = new Ext.data.Store({
			autoLoad: true,
			proxy:new Ext.data.HttpProxy({url: me.baseUrl+"&task=getNoticeList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})
		}),
		
		sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		}),
		
		formatStatus = function(val){
			switch(parseInt(val)){
				case 1:
					return '<b style="color:#0000ff">已处理</b>';
				case 2:
					return '<b style="color:#0DB368">已通知</b>';
				default:
					return '<b style="color:#ff0000">未通知</b>';
			}
		},
		
		renderCard = function(val){
			return '<a onclick="CNOA_member_mgr_notice.viewMember(\''+val+'\')">'+val+'</a>';
		},
		
		//模型
		colModel = new Ext.grid.ColumnModel({
			defaults: {
				sortable: true,
				menuDisabled :true
			},
			columns: [
				new Ext.grid.RowNumberer({width:30}),
				sm,
				{header: 'Code', dataIndex: 'Code', hidden: true},
				{header: '会员卡号', dataIndex: 'CardNO', renderer: renderCard},
				{header: '会员名称', dataIndex: 'Name'},
				{header: '生日', dataIndex: 'BirthDate', width:120},
				{header: '结婚日期', dataIndex: 'UpdateDate', width:120},
				{header: '入会日期', dataIndex: 'InsertDate', width:120},
				{header: '积分', dataIndex: 'FeeTotal'},
				{header: '提醒状态', dataIndex: 'status', renderer: formatStatus}
			]
		});
		
		return new Ext.grid.GridPanel({
			border:false,
			hideBorders: true,
			loadMask : {msg: lang('loading')},
			store: store,
			cm: colModel,
			sm: sm,
			tbar: new Ext.Toolbar({
				defaults: {
					cls: 'x-btn-over',
					style: 'margin-left:5px;',
					listeners: {
						'mouseout': function(btn){
							btn.addClass('x-btn-over');
						}
					}
				},
				items: [
					{
					text: '本月生日会员',
					cls: 'x-btn-click',
					id: me.ID_birth,
					handler: function(btn){
						me.reloadStore('birth',btn);
					}
				},{
					text: '本月结婚纪念日会员',
					id: me.ID_marry,
					handler: function(btn){
						me.reloadStore('marry', btn);
					}
				},{
					text: '清零提醒',
					id: me.ID_clear,
					handler: function(btn){
						me.reloadStore('clear', btn);
					}
				},{
					text: '已处理',
					style: 'margin-left:35px;',
					handler: function(){
						me.updateStatus('deal');
					}	
				},{
					text: '发送短信',
					iconCls: 'icon-sms-send',
					handler: function(){
						me.sendNote();
					}	
				}]
			})
		});
	},
	
	reloadStore: function(showType, btn){
		var me = this,
			col = me.noticeListPanel.getColumnModel(),
			BirthDateCol = col.findColumnIndex('BirthDate'),
			UpdateDateCol = col.findColumnIndex('UpdateDate'),
			InsertDateCol = col.findColumnIndex('InsertDate');
			
		me.showType = showType;
		
		Ext.getCmp(me.ID_birth).removeClass('x-btn-click');
		Ext.getCmp(me.ID_marry).removeClass('x-btn-click');
		Ext.getCmp(me.ID_clear).removeClass('x-btn-click');
		btn.addClass('x-btn-click');
			
		switch(showType){
			case 'birth':
				col.setHidden(BirthDateCol, false);
				col.setHidden(UpdateDateCol, true);
				col.setHidden(InsertDateCol, true);
				break;
			case 'marry':
				col.setHidden(BirthDateCol, true);
				col.setHidden(UpdateDateCol, false);
				col.setHidden(InsertDateCol, true);
				break;
			case 'clear':
				col.setHidden(BirthDateCol, true);
				col.setHidden(UpdateDateCol, true);
				col.setHidden(InsertDateCol, false);
				break;
		}
		me.noticeListPanel.getStore().reload({params:{showType:showType}});
	},
	
	sendNote: function(){
		var me = this,
		
		display = new Ext.form.DisplayField({
			xtype: "displayfield",
			value: "当前已输入0个字。[如果联系人的手机号码为空，则不会发送短信]"
		}), 
		
		content = new Ext.form.TextArea({
			xtype: "textarea",
			width: 370,
			height: 110,
			allowBlank: false,
			enableKeyEvents: true,
			fieldLabel: '短信内容',
			name: "text",
			listeners: {
				keyup : function(th, e){
					makeCount(th);
				},
				change : function(th, e){
					makeCount(th);
				}
			}
		}),
		
		makeCount = function(th){
			var l = th.getValue().length;
			display.setValue("当前已输入<span class='cnoa_color_red'>"+l+"</span>个字");
		};
		
		var win = new Ext.Window({
			title: '发送手机短信',
			width: 500,
			height: makeWindowHeight(250),
			modal: true,
			id: "xxxxxxxxx",
			layout: "form",
			labelWidth: 60,
			labelAlign: "right",
			autoScroll: false,
			padding: 10,
			items: [
				content,
				display
			],
			buttons: [
				{
					text: "发送",
					handler: function(){
						win.getEl().mask("请稍等，发送中...");
						me.updateStatus('note', content.getValue(), win);
					}
				},{
					text: "关闭",
					handler: function(){
						win.close();
					}
				}
			]
		}).show();	
	},
	
	updateStatus: function(opt, content, win){
		var me = this,
			selections = me.noticeListPanel.getSelectionModel().getSelections(),
			cardNO = [];
		
		for(var i=0; i<selections.length; i++){
			cardNO.push(selections[i].get('CardNO'));
		}
		
		if(cardNO.length>0){
			cardNO = cardNO.join(',');
			Ext.Ajax.request({
				url: me.baseUrl+'&task=updateStatus',
				params: {cardNO:cardNO, opt:opt, showType: me.showType, content:content},
				success: function(response){
					var result = Ext.decode(response.responseText);

					if(result.success === true){
						CNOA.msg.notice2(result.msg);
						me.noticeListPanel.getStore().reload();
						win.close();
					}else{
						CNOA.msg.alert(result.msg, function(){
							win.getEl().unmask();
						});
					}
				}
			});
		}
	},
	
	viewMember : function(cardNO){
		var item = mainPanel.getItem('docs-CNOA_MENU_MEMBER_MGR_INFO');
		if(item){
			var store = item.get(0).get(0).store;
			store.reload({params:{cardNO:cardNO}});
		}
		mainPanel.loadClass("index.php?app=member&func=mgr&action=info&task=loadPage&cardNO="+cardNO, "CNOA_MENU_MEMBER_MGR_INFO", "会员信息", "icon-member-info");
	}
}

CNOA_member_mgr_notice = new CNOA_member_mgr_noticeClass();
Ext.getCmp(CNOA.member.mgr.notice.parentID).add(CNOA_member_mgr_notice.mainPanel);
Ext.getCmp(CNOA.member.mgr.notice.parentID).doLayout();