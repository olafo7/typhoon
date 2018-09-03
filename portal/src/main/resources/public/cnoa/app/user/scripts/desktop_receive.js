var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		this.baseUrl = "index.php?app=user&func=customers&action=statistic";
		
		var me = this,
			desktopId = "CNOA_MAIN_DESKTOP_USER_RECEIVE";
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
			title: '客户公海领用数',
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
		var s_time = new Ext.TimeInterval({showMode: 'month'}),
			searchParams = {
				stime: s_time.getSTime(),
				etime: s_time.getETime()
			};

		
        var chart = new Ext.Panel({
        	border: false,
	        html: "<div style='width: 100%; height: 490px;' id='CNOA_ID_REC_HS"+rand_ID+"'>",
        	tbar: new Ext.Toolbar({
				items:[
					lang('statisticalRange') + '：',
					s_time,
					{
						text: lang('statistics'),
						cls: 'x-btn-over',
						style:'margin: 3px 10px;',
						listeners: {
							'mouseout': function(btn){
								btn.addClass('x-btn-over');
							}
						},
						handler: function(){
							searchParams.stime = s_time.getSTime();
							searchParams.etime = s_time.getETime();
							$("#CNOA_ID_REC_HS"+rand_ID).html();
							Ext.Ajax.request({
								url: _this.baseUrl + "&task=receiveHighSeas",
								params: {stime: searchParams.stime, etime: searchParams.etime},
								success: function(h) {
									var rp = Ext.decode(h.responseText);
									var funnel = new FusionCharts("scripts/fusioncharts/Column3D.swf", "myChartId"+Math.floor(Math.random()*1000+1), "100%", "85%", "0", "1");
									funnel.setJSONData({
										chart: {
											caption: "客户公海领用数"
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
									funnel.render("CNOA_ID_REC_HS"+rand_ID);
								}
							})
						}
					}
				]
			}),
			listeners: {
				afterrender: function(){
					Ext.Ajax.request({
						url: _this.baseUrl + "&task=receiveHighSeas",
						params: {stime: searchParams.stime, etime: searchParams.etime},
						success: function(h) {
							var rp = Ext.decode(h.responseText);
							var funnel = new FusionCharts("scripts/fusioncharts/Column3D.swf", "myChartId"+rand_ID, "100%", "85%", "0", "1");
							funnel.setJSONData({
								chart: {
									caption: "客户公海领用数"
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
							funnel.render("CNOA_ID_REC_HS"+rand_ID);
						}
					})
				}
			}
        })
		
	    return chart;
	}
	
}
