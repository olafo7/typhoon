///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DESKTOPAPP_CLASS = CNOA.Class.create();
var DESKTOPAPP_SORT_VIDEO = 1;
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=news&func=news&action=view";
		
		this.id = "CNOA_MAIN_DESKTOP_NEWS_NEWS_SORT_{NEWSSORTID}";
		if(portalsID) this.id += portalsID;
		this.lid = "{NEWSITEMLID}";
		//this.sort = "{NEWSITEMLID}";

		//获取权限
		Ext.Ajax.request({
			url: this.baseUrl + "&task=viewNews&lid=0",
			method: 'GET',
			success: function(r){
				var sText = r.responseText;
				if(sText != ''){
					var jsData = Ext.decode(sText);
					_this.checkPermit(jsData.noPermit);
				}
			}.createDelegate(this)
		});	
		
		this.playerPanel = new Ext.FlashComponent({
			url: "./resources/flvplayer.swf",
			wmode: "transparent",
			allowFullScreen: true,
			vars: {
				file: "{NEWSITEMFLV}",
				image: "{NEWSITEMIMAGE}"
			}
		});

		var tools = [], draggable = false;

		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_JOBTYPE == "superAdmin")){
			draggable = true;

			tools.push({
				id:'close',
				handler: function(e, target, panel){
					panel.ownerCt.remove(panel, true);

					_this.closeAction();
				}
			});


		}

		this.mainPanel = new Ext.ux.Portlet({
			id: this.id,
			title:"{NEWSSORTNAME}-{NEWSITEMTITLE}>>"+'<a id="newsdetail" style="color: #6379A4;" href="javascript:void(0)">[详细]</a>',
			height: 505,
			draggable: draggable,
			tools: tools,
			items: [this.playerPanel],
			listeners: {
				afterrender: function(){
					$("#newsdetail").click(function(){
						_this.view();
					})
				}
			}
			// ,
			// tbar : new Ext.Toolbar({
			// 	style: 'border-width:0;',
			// 	items:[
			// 		"{NEWSITEMTITLE}",
			// 		"->",
			// 		{
			// 			text: lang('detail'),
			// 			handler: function(){
			// 				_this.view();
			// 			}
			// 		}
			// 	]
			// })
		});
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},

	view : function(){
		mainPanel.closeTab("CNOA_MENU_NEWS_VIEW");
		mainPanel.loadClass(this.baseUrl + "&task=view&lid="+this.lid, "CNOA_MENU_NEWS_VIEW", lang('viewMsg'), "icon-page-view");
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
