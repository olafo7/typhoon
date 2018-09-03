///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;
		this.baseUrl = "http://m.weather.com.cn/data/22.html";
		this.id = "CNOA_MAIN_DESKTOP_MAIN_WEATHER";
		if(portalsID) this.id += portalsID;
		
		var tools = [], draggable = false;
		tools.push({
			id:'gear',
			qtip: lang('setMyCity'),
			handler: function(e, target, panel){
				_this.setting();
			}
		});
		tools.push({
			id:'refresh',
			handler: function(e, target, panel){
				_this.getWeather(_this.mainPanel);
			}
		});
		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_UID == 1)){
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
			title: lang('weatherForecast'),
			height: 240,
			iconCls: "icon-weather",
			autoScroll: false,
			draggable: draggable,
			tools: tools,
			listeners : {
				afterrender : function(p){
					_this.getWeather(p);
				}
			}
		});
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	},
	
	setting : function(){
		var _this = this;
		
		var prov = '';
		
		var areaStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: "index.php?app=main&func=common&action=commonJob&act=getWeatherCityList&task=area"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name:"number"},
					{name:"name"}
				]
			}),
			listeners:{
				load:function(th, record){
					areaCombo.setValue(th.getAt(0).get('number'));
				}
			}
		});
		
		var areaCombo = new Ext.form.ComboBox({
			triggerAction:'all',
			mode:'local',
			editable:false,
			width:225,
			fieldLabel:lang('area'),
			allowBlank:false,
			name:"area",
			hiddenName:'area',
			store:areaStore,
			valueField:'number',
			displayField:'name'
		});
		
		var cityStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: "index.php?app=main&func=common&action=commonJob&act=getWeatherCityList&task=city"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name:"number"},
					{name:"name"}
				]
			}),
			listeners:{
				load:function(th, record){
					cityCombo.setValue(th.getAt(0).get('number'));
				},
				datachanged:function(th){
					areaStore.load({params:{prov:prov,city:th.getAt(0).get('number')}});
				}
			}
		});
		
		var cityCombo = new Ext.form.ComboBox({
			triggerAction:'all',
			mode:'local',
			editable:false,
			width:225,
			fieldLabel:lang('city'),
			allowBlank:false,
			name:"city",
			hiddenName:'city',
			store:cityStore,
			valueField:'number',
			displayField:'name',
			listeners:{
				select:function(th,record){
					areaCombo.setValue("");
					areaStore.load({params:{prov:prov,city:record.data.number}});
				}
			}
		});
		
		var provCombo = new Ext.form.ComboBox({
			triggerAction:'all',
			mode:'local',
			width:225,
			editable:false,
			fieldLabel:lang('province'),
			allowBlank:false,
			store:new Ext.data.SimpleStore({
				fields: ['number', 'name'],
				data: [[10101,'北京'],[10102,'上海'],[10103,'天津'],[10104,'重庆'],[10105,'黑龙江'],[10106,'吉林'],[10107,'辽宁'],[10108,'内蒙古'],[10109,'河北'],[10110,'山西'],[10111,'陕西'],[10112,'山东'],[10113,'新疆'],[10114,'西藏'],[10115,'青海'],[10116,'甘肃'],[10117,'宁夏'],[10118,'河南'],[10119,'江苏'],[10120,'湖北'],[10121,'浙江'],[10122,'安徽'],[10123,'福建'],[10124,'江西'],[10125,'湖南'],[10126,'贵州'],[10127,'四川'],[10128,'广东'],[10129,'云南'],[10130,'广西'],[10131,'海南'],[10132,'香港'],[10133,'澳门'],[10134,'台湾']]
			}),
			valueField:'number',
			displayField:'name',
			name:'prov',
			hiddenName:'prov',
			listeners:{
				select:function(th, record, index){
					cityCombo.setValue("");
					areaCombo.setValue("");
					prov = record.data.number;
					cityStore.load({params:{prov:prov}});
				}
			}
		});
		
		var form = new Ext.form.FormPanel({
			border: false,
			labelWidth: 60,
			bodyStyle: "padding: 10px",
			items: [provCombo, cityCombo, areaCombo]
		});
		
		var win = new Ext.Window({
			title:lang('setMyWeather'),
			width:330,
			height:200,
			modal:true,
			layout:"fit",
			items:form,
			resizable:true,
			buttons:[
				//汇报
				{
					text:lang('ok'),
					iconCls:'icon-order-s-accept',
					handler:function() {
						_this.submit(win, form);
					}.createDelegate(this)
				},
				//关闭
				{
					text:lang('cancel'),
					iconCls:'icon-dialog-cancel',
					handler:function(){
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
		
	},
	
	submit : function(win, form){
		var _this = this;
		
		if (form.getForm().isValid()) {
			var values = form.getForm().getValues();
			_this.mainPanel.getEl().mask(lang('waiting'));
			_this.mainPanel.body.update("");
			Ext.Ajax.request({
				url: "index.php?app=main&func=common&action=commonJob&act=setWeather",
				method: 'POST',
				params: {city: values.city, prov:values.prov, area:values.area},
				success: function(r) {
					win.close();
					var result = Ext.decode(r.responseText);
					_this.renderWeather(result.data);
				}
			});
		}
	},
	
	getWeather : function(p){
		var _this = this;
		
		p.getEl().mask(lang('waiting'));
		p.body.update("");
		
		Ext.Ajax.request({  
			url: "index.php?app=main&func=common&action=commonJob&act=getWeather",
			method: 'GET',
			success: function(r) {
				var result = Ext.decode(r.responseText);
				_this.renderWeather(result.data);
			}
		});
	},
	
	renderWeather : function(data){
		var _this = this;
		
		var tpl = new Ext.XTemplate(
			'<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:12px;background:url(resources/images/cnoa/weather_bg.gif)">',
			'<tr>',
			'<td height="25" colspan="3" style="font-weight:bold;font-size:14px;color:#333">&nbsp;&nbsp;{city}</td>',
			'</tr>',
			'<tr>',
			'<td height="50" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">',
			'<tr>',
			'<td width="11%" height="58" rowspan="2" align="center"><img src="resources/images/weather/{picture}" onload="setPng(this,60,60);" width="60" height="60" /></td>',
			'<td width="89%" height="30" valign="bottom" style="font-size:12px;color:#333;font-weight:bold;">天气：{weat1}&nbsp;&nbsp;&nbsp;气温：{temp1}&nbsp;&nbsp;&nbsp;风力风向：{wind1}</td>',
			'</tr>',
			'<tr>',
			'<td height="15" style="font-size:12px;color:#333">{ganmao}</td>',
			'</tr>',
			'</table></td>',
			'</tr>',
			'<tr>',
			'<td colspan="3">&nbsp;</td>',
			'</tr>',
			'<tr>',
			'<td height="26" align="center">'+lang('today')+' ({weat1})</td>',
			'<td width="33%" align="center" style="border-left:1px solid #FFF;border-right:1px solid #FFF;">'+lang('tomorrow')+' ({weat2})</td>',
			'<td width="33%" align="center">'+lang('tDATomorrow')+' ({weat3})</td>',
			'</tr>',
			'<tr>',
			'<td height="20" align="center"><img src="resources/images/weather/{pict1}" width="16" height="16" onload="setPng(this,20,16);" /></td>',
			'<td width="33%" align="center" style="border-left:1px solid #FFF;border-right:1px solid #FFF;"><img src="resources/images/weather/{pict2}" width="16" height="16" onload="setPng(this,20,16);" /></td>',
			'<td width="33%" align="center"><img src="resources/images/weather/{pict3}" width="16" height="16" onload="setPng(this,20,16);" /></td>',
			'</tr>',
			'<tr>',
			'<td height="20" align="center">{temp1}</td>',
			'<td width="33%" align="center" style="border-left:1px solid #FFF;border-right:1px solid #FFF;">{temp2}</td>',
			'<td width="33%" align="center">{temp3}</td>',
			'</tr>',
			'<tr>',
			'<td height="40" align="center">{wind1}</td>',
			'<td width="33%" align="center" style="border-left:1px solid #FFF;border-right:1px solid #FFF;">{wind2}</td>',
			'<td width="33%" align="center">{wind3}</td>',
			'</tr>',
			'</table>'
		);

		tpl.overwrite(_this.mainPanel.body, data);
		_this.mainPanel.getEl().unmask();
	}
}
//var DESKTOPAPP_OBJ = new DESKTOPAPP_CLASS();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
