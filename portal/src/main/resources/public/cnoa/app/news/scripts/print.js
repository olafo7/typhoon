	var panel = new Ext.Panel({
		hideBorders: true,
		border: false,
		region: "center",
		autoScroll: false,
		html: '<iframe id="notice_frame" scrolling=auto frameborder=0 width=100% height=100% src="index.php?app=news&func=notice&action=index&task=getprint&id='+CNOA.news.notice.id+'"></iframe>',
		tbar: [
			"->",
			{
				text: lang('startPrint'),
				handler:function(){
	  				window.parent.frames["notice_frame"].print();
					if(Ext.isIE){
						WebBrowser.ExecWB(7,1);
					}
				}
			},
			{
				text : lang('close'),
				iconCls: 'icon-dialog-cancel',
				handler : function(button, event) {
					window.close();
				}.createDelegate(this)
			}
		]
	});
	
	var mainPanel;
	Ext.onReady(function() {
		mainPanel = new Ext.Viewport({
			layout:'border',
			border:false,
			items: [panel]
		});
		mainPanel.doLayout();
	})