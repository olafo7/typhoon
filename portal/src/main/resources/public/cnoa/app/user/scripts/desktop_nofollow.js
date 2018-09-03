var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		this.baseUrl = "index.php?app=user&func=customers&action=statistic";
		
		var me = this,
			desktopId = "CNOA_MAIN_DESKTOP_USER_NOFOLLOW";
		if(portalsID) desktopId += portalsID;
		this.chart = this.chartPanel();
		
		var tools = [], draggable = false;
		tools.push({
			id:'refresh',
			handler: function(){
				me.store.reload();
			}
		});
		
		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_JOBTYPE == "superAdmin")){
			draggable = true;
			tools.push({
				id:'close',
				handler: function(e, target, panel){
					panel.ownerCt.remove(panel, true);
					CNOA_main_common_index.closeDesktopApp(desktopId);
				}
			});
		};
		
		this.mainPanel = new Ext.ux.Portlet({
			xtype: "portlet",
			id: desktopId,
			title: '到期未跟进客户',
			layout: "fit",
			height: 505,
			draggable: draggable,
			tools: tools,
			items: [me.chart]
		});
	},
	chartPanel: function(){
		var _this = this;
		var rand_ID = Math.floor(Math.random()*1000+1);
        var chart = new Ext.Panel({
        	border: false,
	        html: "<div style='width: 100%; height: 490px;' id='CNOA_ID_NOFLLOW"+rand_ID+"'>",
			listeners: {
				afterrender: function(){
					Ext.Ajax.request({
						url: _this.baseUrl + "&task=getFollowData",
						success: function(h) {
							var rp = Ext.decode(h.responseText);
							var funnel = new FusionCharts("scripts/fusioncharts/Column3D.swf", "myChartId"+rand_ID, "100%", "85%", "0", "1");
							funnel.setJSONData({
								chart: {
									caption: "到期未跟进客户"
								},
								bgColor: "#FFFFFF",//背景色
								canvasBgColor: "#FFFFFF",//画布背景
								canvasBorderThickness: 0,//边框厚度
	        					canvasBgAlpha: 0,//透明度
								baseFontSize: 12,//字体大小
								formatNumberScale: 0,//单位自动加上K/M
								decimalPrecision: 2,//小数位数
								data: rp.data
							});
							funnel.render("CNOA_ID_NOFLLOW"+rand_ID);
						}
					})
				}
			}
        })
		
	    return chart;
	}
	
}
