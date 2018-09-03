var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		this.baseUrl = "index.php?app=user&func=customers&action=statistic";
		
		var me = this,
			desktopId = "CNOA_MAIN_DESKTOP_USER_CUSTOMERTYPE";
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
			title: '我的客户类型(漏斗图)',
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
	        html: "<div style='width: 100%; height: 490px;' id='CNOA_ID_FUNNEL"+rand_ID+"'>",
	        listeners: {
	        	afterrender: function(){
	        		Ext.Ajax.request({
	        			url: _this.baseUrl + "&task=myCustomersType",
	        			success : function(response){
	        				var rp = Ext.decode(response.responseText);
	        				var  Funnel = new FusionCharts("scripts/fusioncharts/Funnel.swf", "myChartId"+rand_ID, '100%', '93%', "0", "1");   
	        				Funnel.setJSONData({
	        					"chart": {
	        						"caption": "我的客户类型(漏斗图)"
	        					},
	        					"baseFontSize": 12,
	        					"bgColor": "#FFFFFF",
	        					"data": rp.data
	        				})
	        				Funnel.render("CNOA_ID_FUNNEL"+rand_ID);
	        			}
	        		})
	        	}
	        }
		})
	    return chart;
	}
	
}
