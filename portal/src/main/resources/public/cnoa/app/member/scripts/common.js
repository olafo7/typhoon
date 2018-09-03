Ext.namespace("CNOA.member.mgr.common");
CNOA.member.mgr.common.sendNote = function(cardNO, baseUrl){
	cardNO = cardNO.join(',');
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
					send(content.getValue());
				}
			},{
				text: "关闭",
				handler: function(){
					win.close();
				}
			}
		]
	}).show();
	
	//发送
	var send = function(content){
		Ext.Ajax.request({
			url: baseUrl+'&task=sendNote',
			params: {cardNO: cardNO, content: content},
			success: function(response){
				var result = Ext.decode(response.responseText);

				if(result.success === true){
					CNOA.msg.notice2(result.msg);
					//me.noticeListPanel.getStore().reload();
					win.close();
				}else{
					CNOA.msg.alert(result.msg, function(){
						win.getEl().unmask();
					});
				}
			}
		});
	}
}



