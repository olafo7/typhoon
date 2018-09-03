//CNOA_MAIN_DESKTOP_BIRTHDAY
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var me = this;

		this.baseUrl = "index.php?app=hr&func=person&action=waiting&from=getChartData";
		
		var me = this,
			desktopId = "CNOA_MAIN_DESKTOP_AGEBRACKET";
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
			title: '人事年龄(图)',
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
	        html: "<div style='width: 100%; height: 490px;' id='CNOA_AGE_BRACKET_CHART"+rand_ID+"'>",
	        listeners: {
	        	afterrender: function(){
	        		Ext.Ajax.request({
	        			url: _this.baseUrl + "&task=ageBracket",
	        			success : function(response){
	        				var rp = Ext.decode(response.responseText);
	        				var  mychart = new FusionCharts("scripts/fusioncharts/Pie3D.swf", "myChartId"+rand_ID, '100%', '93%', "0", "1");   
	        				mychart.setJSONData({
	        					"chart": {
	        						"caption": "人事年龄(图)"
	        					},
	        					"baseFontSize": 12,
	        					"bgColor": "#FFFFFF",
	        					"canvasBgColor": "#FFFFFF",
	        					"canvasBgAlpha": 0,
	        					"showPercentValues": 1,
	        					"data": rp.data
	        				})
	        				mychart.render("CNOA_AGE_BRACKET_CHART"+rand_ID);
	        			}
	        		})
	        	}
	        }
		})
	    return chart;
	}
}
