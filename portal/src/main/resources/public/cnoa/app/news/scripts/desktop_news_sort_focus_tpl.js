///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=news&func=news&action=view&task=view";
		
		this.id = "CNOA_MAIN_DESKTOP_NEWS_NEWS_SORT_{NEWSSORTID}";
		if(portalsID) this.id += portalsID;
		this.sort = '{NEWSSORTID}';

		var items = Ext.decode('{NEWSITEMS}');

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
		
		var mkFocusHtml = function(items){
			if(items.length == 0){
				return '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle" class="cnoa_color_gray">'+lang('nodata')+'</td></tr></table>';
			}
			var html  = '<div class="focus">';
				html += '    <div class="num">';
				for (var i=0; i<items.length; i++){
					if(i==0){
						html += '<a class="cur">'+(i+1)+'</a>';
					}else{
						html += '<a>'+(i+1)+'</a>';
					}
				}
				html += '    </div>';
				html += '	<ul>';
				for (var i=0; i<items.length; i++){
					if(i==0){
						var cls = ' style="display:block;"';
					}else{
						var cls = '';
					}
					html += '<li'+cls+'><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td height="203" width="270" align="center" valign="middle"><a href="javascript:void(0);" onclick="CNOA_MAIN_DESKTOP_NEWS_NEWS_SORT_'+_this.sort+'_OBJ.view('+items[i].lid+')"><img src="'+items[i].pic+'" /></a></td></tr></table></li>';
				}
				html += '    </ul>';
				html += '</div>';
			return html;
		}
		
		var mkContentHtml = function(data){
			if((data == undefined)){
				return "";
			}
			if(!data.title && !data.content){
				return;
			}
			return '<div style="font-size:16px;font-weight:bold;text-align:center">'+data.title+'</div><div style="margin:10px;height:145px;overflow-y:hidden;">'+data.content+'</div><div style="text-align:right"><a href="javascript:void(0);" onclick="CNOA_MAIN_DESKTOP_NEWS_NEWS_SORT_'+_this.sort+'_OBJ.view('+data.lid+')">'+lang('more')+'..</a></div>';
		}
		
		this.detailPanel = new Ext.Panel({
			region: "center",
			border: false,
			html: mkContentHtml(items[0])
		});

		this.playerPanel = new Ext.Panel({
			region : "west",
			width: 270,
			border: false,
			html: mkFocusHtml(items),
			listeners : {
				afterrender: function(){
					$(function(){
						var sw = 0, myTime = 0;
						$("#" + _this.id + " .focus .num a").mouseover(function(){
							sw = $("#" + _this.id + " .num a").index(this);
							myShow(sw);
						});
						function myShow(i){
							$("#" + _this.id + " .focus .num a").eq(i).addClass("cur").siblings("a").removeClass("cur");
							$("#" + _this.id + " .focus ul li").eq(i).stop(true,true).fadeIn(600).siblings("li").fadeOut(600);

							_this.detailPanel.body.update(mkContentHtml(items[i]));
						}
						//滑入停止动画，滑出开始动画
						$("#" + _this.id + " .focus").hover(function(){
							if(myTime){
							   clearInterval(myTime);
							}
						},function(){
							myTime = setInterval(function(){
							  myShow(sw)
							  sw++;
							  if(sw==items.length){sw=0;}
							} , 3000);
						});
						//自动开始
						myTime = setInterval(function(){
						   myShow(sw)
						   sw++;
						   if(sw==items.length){sw=0;}
						} , 3000);
					})

				}
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
		};

		this.mainPanel = new Ext.ux.Portlet({
			id: this.id,
			title:"{NEWSSORTNAME}",
			height: 250,
			layout: "border",
			draggable: draggable,
			tools: tools,
			items: [this.playerPanel, this.detailPanel]
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

	view : function(lid){
		mainPanel.closeTab("CNOA_MENU_NEWS_VIEW");
		mainPanel.loadClass(this.baseUrl + "&lid="+lid, "CNOA_MENU_NEWS_VIEW", lang('viewMsg'), "icon-page-view");
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

