///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "http://www.blogweather.com.cn/diy/1679blueheart.swf?dw_pkid=7c9e6679-7425-40de-944b-e07fc1f90ae7";
		this.baseUrl = "http://www.blogweather.com.cn/fee_love.swf?bw_pkid=W49319";
		this.baseUrl = "http://www.ik68.cn/swf/0001-Green_.swf";
		this.baseUrl = "http://www.265.com/weather.htm";
		this.baseUrl = "http://tianqi.nihao020.com/auto.asp?type=2";
		this.baseUrl = "http://m.weather.com.cn/m/pn12/weather.htm";
		this.baseUrl = "http://www.soso.com/tb.q";
		this.id = "CNOA_MAIN_DESKTOP_MAIN_WEATHER";
		if(portalsID) this.id += portalsID;

		var tools = [], draggable = false;
		tools.push({
			id: 'gear',
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
		var cityCombo = new Ext.form.ComboBox({
			triggerAction: 'all',
			mode: 'local',
			editable: false,
			width: 225,
			fieldLabel: lang('city'),
			allowBlank: false,
			name: "city",
			store: new Ext.data.ArrayStore({
				fields: ['city'],
				data: _this.getData('city', lang('beiJing'))
			}),
			valueField: 'city',
			displayField: 'city',
			value: lang('beiJing')
		});
		var provCombo = new Ext.form.ComboBox({
			triggerAction: 'all',
			mode: 'local',
			width: 225,
			editable: false,
			fieldLabel: lang('province'),
			allowBlank: false,
			store: new Ext.data.ArrayStore({
				fields: ['prov'],
				data: _this.getData('prov')
			}),
			valueField: 'prov',
			displayField: 'prov',
			value: lang('beiJing'),
			listeners: {
				select : function(th){
					cityCombo.setValue("");
					cityCombo.store.loadData(_this.getData('city', th.getValue()));
				}
			}
		});
		var form = new Ext.form.FormPanel({
			border: false,
			labelWidth: 60,
			bodyStyle: "padding: 10px",
			items: [provCombo, cityCombo]
		});
		
		var win = new Ext.Window({
			title: lang('setMyWeather'),
			width: 330,
			height: 140,
			modal: true,
			layout: "fit",
			items: [
				form
			],
			resizable: true,
			buttons : [
				//汇报
				{
					text : lang('ok'),
					iconCls: 'icon-order-s-accept',
					handler : function() {
						_this.submit(win, form);
					}.createDelegate(this)
				},
				//关闭
				{
					text : lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						win.close();
					}.createDelegate(this)
				}
			]
		}).show();
	},
	
	submit : function(win, form){
		var _this = this;
		
		if (form.getForm().isValid()) {
			var city = form.getForm().findField("city").getValue();
			_this.mainPanel.getEl().mask(lang('waiting'));
			_this.mainPanel.body.update("");
			
			Ext.Ajax.request({
				url: "index.php?app=main&func=common&action=commonJob&act=setWeather",
				method: 'POST',
				params: {"city": city},
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
			'<td width="11%" height="58" rowspan="2" align="center"><img src="resources/images/weather/{picture}" onload="setPng(this,60,50);" width="60" height="50" /></td>',
			'<td width="89%" height="30" valign="bottom" style="font-size:12px;color:#333">{temp1}&nbsp;&nbsp;&nbsp;{wind1}</td>',
			'</tr>',
			'<tr>',
			'<td height="15" style="font-size:12px;color:#333">' + lang('colIndex') + '：{ganmao}</td>',
			'</tr>',
			'</table></td>',
			'</tr>',
			'<tr>',
			'<td colspan="3">&nbsp;</td>',
			'</tr>',
			'<tr>',
			'<td height="26" align="center">' + lang('today') + '</td>',
			'<td width="33%" align="center" style="border-left:1px solid #FFF;border-right:1px solid #FFF;">' + lang('tomorrow') + '</td>',
			'<td width="33%" align="center">' + lang('tDATomorrow') + '</td>',
			'</tr>',
			'<tr>',
			'<td height="20" align="center"><img src="resources/images/weather/{pict1}" width="16" height="16" onload="setPng(this,20,16);" />{weat1}</td>',
			'<td width="33%" align="center" style="border-left:1px solid #FFF;border-right:1px solid #FFF;"><img src="resources/images/weather/{pict2}" width="16" height="16" onload="setPng(this,20,16);" />{weat2}</td>',
			'<td width="33%" align="center"><img src="resources/images/weather/{pict3}" width="16" height="16" onload="setPng(this,20,16);" />{weat3}</td>',
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
		//try{
		//	pngfix();
		//}catch(e){}
	},
	
	getData : function(type, prov){
		var p2c = {
			'安徽':['淮南','马鞍山','淮北','铜陵','滁州','巢湖','池州','宣城','亳州','宿州','阜阳','六安','蚌埠','合肥','芜湖','安庆','黄山'],
			'澳门':['澳门'],
			'北京':['北京'],
			'重庆':['奉节','重庆','涪陵'],
			'福建':['莆田','浦城','南平','宁德','福州','龙岩','三明','泉州','漳州','厦门'],
			'甘肃':['张掖','金昌','武威','兰州','白银','定西','平凉','庆阳','甘南','临夏','天水','嘉峪关','酒泉','陇南'],
			'广东':['南雄','韶关','清远','梅州','肇庆','广州','河源','汕头','深圳','汕尾','湛江','阳江','茂名','佛冈','梅县','电白','高要','珠海','佛山','江门','惠州','东莞','中山','潮州','揭阳','云浮'],
			'广西':['桂林','河池','柳州','百色','贵港','梧州','南宁','钦州','北海','防城港','玉林','贺州','来宾','崇左'],
			'贵州':['毕节','遵义','铜仁','安顺','贵阳','黔西南布依族苗族自治州','六盘水'],
			'海南':['海口','三亚','屯昌','琼海','儋州','文昌','万宁','东方','澄迈','定安','临高','白沙黎族自治县昌','乐东黎族自治县','陵水黎族自治县','保亭黎族苗族自治县','琼中黎族苗族自治县'],
			'河北':['邯郸','衡水','石家庄','邢台','张家口','承德','秦皇岛','廊坊','唐山','保定','沧州'],
			'河南':['安阳','三门峡','郑州','南阳','周口','驻马店','信阳','开封','洛阳','平顶山','焦作','鹤壁','新乡','濮阳','许昌','漯河','商丘','济源'],
			'黑龙江':['大兴安岭','黑河','齐齐哈尔','绥化','鹤岗','佳木斯','伊春','双鸭山','哈尔滨','鸡西','漠河','大庆','七台河','牡丹江','绥芬河'],
			'湖北':['襄樊','荆门','黄冈','恩施土家族苗族自治州','武汉','麻城','黄石','鄂州','孝感','咸宁','随州','仙桃','天门','潜江','神农架','枣阳'],
			'湖南':['张家界','岳阳','怀化','长沙','邵阳','益阳','郴州','桑植','沅陵','南岳','株洲','湘潭','衡阳','娄底','常德'],
			'吉林':['辽源','通化','白城','松原','长春','吉林市','桦甸','延边朝鲜族自治州','集安','白山','四平'],
			'江苏':['无锡','苏州','盱眙','赣榆','东台','高邮','镇江','泰州','宿迁','徐州','连云港','淮安','南京','扬州','盐城','南通','常州'],
			'江西':['庐山','玉山','贵溪','广昌','萍乡','新余','宜春','赣州','九江','景德镇','南昌','鹰潭','上饶','抚州'],
			'辽宁':['葫芦岛','盘锦','辽阳','铁岭','阜新','朝阳','锦州','鞍山','沈阳','本溪','抚顺','营口','丹东','瓦房店','大连'],
			'内蒙古':['呼伦贝尔','兴安盟','锡林郭勒盟','巴彦淖尔盟','包头','呼和浩特','锡林浩特','通辽','赤峰','乌海','鄂尔多斯','乌兰察布盟'],
			'宁夏':['石嘴山','银川','吴忠','固原'],
			'青海':['海北藏族自治州','海南藏族自治州','西宁','玉树藏族自治州','黄南藏族自治州','果洛藏族自治州','海西蒙古族藏族自治州','海东'],
			'山东':['德州','滨州','烟台','聊城','济南','泰安','淄博','潍坊','青岛','济宁','日照','泰山','枣庄','东营','威海','莱芜','临沂','菏泽'],
			'山西':['长治','晋中','朔州','大同','吕梁','忻州','太原','阳泉','临汾','运城','晋城','五台山'],
			'陕西':['榆林','延安','西安','渭南','汉中','商洛','安康','铜川','宝鸡','咸阳'],
			'上海':['上海'],
			'四川':['甘孜藏族自治州','阿坝藏族羌族自治州','成都','绵阳','雅安','峨眉山','乐山','宜宾','巴中','达州','遂宁','南充','泸州','自贡','攀枝花','德阳','广元','内江','广安','眉山','资阳','凉山彝族自治州'],
			'台湾':['台北'],
			'天津':['天津','塘沽区'],
			'西藏':['那曲','日喀则','拉萨','山南','阿里','昌都','林芝'],
			'香港':['香港'],
			'新疆':['昌吉回族自治州','克孜勒苏柯尔克孜自治州','伊犁哈萨克自治州','阿拉尔','克拉玛依','博尔塔拉蒙古自治州','乌鲁木齐','吐鲁番','阿克苏','石河子','喀什','和田','哈密','奇台'],
			'云南':['昭通','丽江','曲靖','保山','大理白族自治州','楚雄彝族自治州','昆明','瑞丽','玉溪','临沧','思茅','红河哈尼族彝族自治州','文山壮族苗族自治州','西双版纳傣族自治州','德宏傣族景颇族自治州','怒江傈傈族自治州','迪庆藏族自治州'],
			'浙江':['湖州','嵊州','平湖','石浦','宁海','洞头','舟山','杭州','嘉兴','金华','绍兴','宁波','衢州','丽水','台州','温州']
		};

		if(type == 'prov'){
			var a = [];
			for(var i in p2c){
				var p = [i];
				a.push(p);
			}
			return a;
		}
		if(type == 'city'){
			var cys = p2c[prov];
			var a = [];
			for(var i=0;i<cys.length;i++){
				a.push([cys[i]]);
			}
			return a;
		}
	}
}
//var DESKTOPAPP_OBJ = new DESKTOPAPP_CLASS();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
