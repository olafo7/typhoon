///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function CnDateofDate(DateGL) {
	var CnData = new Array(0x16, 0x2a, 0xda, 0x00, 0x83, 0x49, 0xb6, 0x05, 0x0e, 0x64, 0xbb, 0x00, 0x19, 0xb2, 0x5b, 0x00, 0x87, 0x6a, 0x57, 0x04, 0x12, 0x75, 0x2b, 0x00, 0x1d, 0xb6, 0x95, 0x00, 0x8a, 0xad, 0x55, 0x02, 0x15, 0x55, 0xaa, 0x00, 0x82, 0x55, 0x6c, 0x07, 0x0d, 0xc9, 0x76, 0x00, 0x17, 0x64, 0xb7, 0x00, 0x86, 0xe4, 0xae, 0x05, 0x11, 0xea, 0x56, 0x00, 0x1b, 0x6d, 0x2a, 0x00, 0x88, 0x5a, 0xaa, 0x04, 0x14, 0xad, 0x55, 0x00, 0x81, 0xaa, 0xd5, 0x09, 0x0b, 0x52, 0xea, 0x00, 0x16, 0xa9, 0x6d, 0x00, 0x84, 0xa9, 0x5d, 0x06, 0x0f, 0xd4, 0xae, 0x00, 0x1a, 0xea, 0x4d, 0x00, 0x87, 0xba, 0x55, 0x04);
	var CnMonth = new Array();
	var CnMonthDays = new Array();
	var CnBeginDay;
	var LeapMonth;
	var Bytes = new Array();
	var I;
	var CnMonthData;
	var DaysCount;
	var CnDaysCount;
	var ResultMonth;
	var ResultDay;
	var yyyy = DateGL.getFullYear();
	var mm = DateGL.getMonth() + 1;
	var dd = DateGL.getDate();
	if (yyyy < 100) yyyy += 1900;
	if ((yyyy < 1997) || (yyyy > 2020)) {
		return 0;
	}
	Bytes[0] = CnData[(yyyy - 1997) * 4];
	Bytes[1] = CnData[(yyyy - 1997) * 4 + 1];
	Bytes[2] = CnData[(yyyy - 1997) * 4 + 2];
	Bytes[3] = CnData[(yyyy - 1997) * 4 + 3];
	if ((Bytes[0] & 0x80) != 0) {
		CnMonth[0] = 12;
	} else {
		CnMonth[0] = 11;
	}
	CnBeginDay = (Bytes[0] & 0x7f);
	CnMonthData = Bytes[1];
	CnMonthData = CnMonthData << 8;
	CnMonthData = CnMonthData | Bytes[2];
	LeapMonth = Bytes[3];
	for (I = 15; I >= 0; I--) {
		CnMonthDays[15 - I] = 29;
		if (((1 << I) & CnMonthData) != 0) {
			CnMonthDays[15 - I]++;
		}
		if (CnMonth[15 - I] == LeapMonth) {
			CnMonth[15 - I + 1] = -LeapMonth;
		} else {
			if (CnMonth[15 - I] < 0) {
				CnMonth[15 - I + 1] = -CnMonth[15 - I] + 1;
			} else {
				CnMonth[15 - I + 1] = CnMonth[15 - I] + 1;
			}
			if (CnMonth[15 - I + 1] > 12) {
				CnMonth[15 - I + 1] = 1;
			}
		}
	}
	DaysCount = parseInt((Date.parse(DateGL) - Date.parse(DateGL.getFullYear() + "/1/1")) / 86400000);
	if (DaysCount <= (CnMonthDays[0] - CnBeginDay)) {
		if ((yyyy > 1901) && (CnDateofDate(new Date((yyyy - 1) + "/12/31")) < 0)) {
			ResultMonth = -CnMonth[0];
		} else {
			ResultMonth = CnMonth[0];
		}
		ResultDay = CnBeginDay + DaysCount;
	} else {
		CnDaysCount = CnMonthDays[0] - CnBeginDay;
		I = 1;
		while ((CnDaysCount < DaysCount) && (CnDaysCount + CnMonthDays[I] < DaysCount)) {
			CnDaysCount += CnMonthDays[I];
			I++;
		}
		ResultMonth = CnMonth[I];
		ResultDay = DaysCount - CnDaysCount;
	}
	if (ResultMonth > 0) {
		return ResultMonth * 100 + ResultDay;
	} else {
		return ResultMonth * 100 - ResultDay;
	}
}

function CnYearofDate(DateGL) {
	var YYYY = DateGL.getFullYear();
	var MM = DateGL.getMonth() + 1;
	var CnMM = parseInt(Math.abs(CnDateofDate(DateGL)) / 100);
	if (YYYY < 100) YYYY += 1900;
	if (CnMM > MM) YYYY--;
	YYYY -= 1864;
	return (function(){
		var Tiangan = new Array("甲", "乙", "丙", "丁", "戊", "己", "庚", "辛", "壬", "癸");
		var Dizhi = new Array("子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥");
		return Tiangan[YYYY % 10] + Dizhi[YYYY % 12];
	})() + "年";
}

function CnMonthofDate(DateGL) {
	var CnMonthStr = new Array("零", "正", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二");
	var Month;
	Month = parseInt(CnDateofDate(DateGL) / 100);
	if (Month < 0) {
		return "闰" + CnMonthStr[ - Month] + "月";
	} else {
		return CnMonthStr[Month] + "月";
	}
}

function CnDayofDate(DateGL) {
	var CnDayStr = new Array("零", "初一", "初二", "初三", "初四", "初五", "初六", "初七", "初八", "初九", "初十", "十一", "十二", "十三", "十四", "十五", "十六", "十七", "十八", "十九", "二十", "廿一", "廿二", "廿三", "廿四", "廿五", "廿六", "廿七", "廿八", "廿九", "三十");
	var Day;
	Day = (Math.abs(CnDateofDate(DateGL))) % 100;
	return CnDayStr[Day];
}

function CnEra(YYYY) {
	var Tiangan = new Array("甲", "乙", "丙", "丁", "戊", "己", "庚", "辛", "壬", "癸");
	var Dizhi = new Array("子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥");
	return Tiangan[YYYY % 10] + Dizhi[YYYY % 12];
}

function CnDateofDateStr(DateGL) {
	if (CnMonthofDate(DateGL) == "零月") return "请调整您的计算机日期!";
	else return (function() {
		var YYYY = DateGL.getFullYear();
		var MM = DateGL.getMonth() + 1;
		var CnMM = parseInt(Math.abs(CnDateofDate(DateGL)) / 100);
		if (YYYY < 100) YYYY += 1900;
		if (CnMM > MM) YYYY--;
		YYYY -= 1864;
		return (function(){
			var Tiangan = new Array("甲", "乙", "丙", "丁", "戊", "己", "庚", "辛", "壬", "癸");
			var Dizhi = new Array("子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥");
			return Tiangan[YYYY % 10] + Dizhi[YYYY % 12];
		})() + "年";
	})() + " " + CnMonthofDate(DateGL) + CnDayofDate(DateGL);
}

var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var me = this;
		this.info = null;

		this.baseUrl = "index.php?app=att&func=person&action=register";
		
		this.id = "DESKTOPAPP";
		if(portalsID) this.id += portalsID;
		
		this.tpl = function(info){
			return ['<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#C9C9C9">',
			'  <tr bgcolor="#FFFFFF">',
			'    <td height="26" colspan="4">&nbsp;<b>' + lang('personAttNew') + '</b></td>',
			'  </tr>',
			me.getPersonAtt(),
			'  <tr bgcolor="#FFFFFF">',
			'    <td height="26" colspan="4">&nbsp;<b>' + lang('statistcsMonth') + '</b></td>',
			'  </tr>',
			'  <tr bgcolor="#FFFFFF">',
			'    <td height="24" bgcolor="#F2F2F2">&nbsp;' + lang('late') + '</td>',
			'    <td>&nbsp;<span class="' + (info.belate > 0 ? 'cnoa_color_red' : '') + '">' + info.belate + '</span> ' + lang('bout') + '</td>',
			'    <td bgcolor="#F2F2F2">&nbsp;' + lang('leaveEarly') + '</td>',
			'    <td>&nbsp;<span class="' + (info.leaved > 0 ? 'cnoa_color_red' : '') + '">' + info.leaved + '</span> ' + lang('bout'),
			'  </tr>',
			'  <tr bgcolor="#FFFFFF">',
			'    <td height="24" bgcolor="#F2F2F2">&nbsp;' + lang('unRegister') + '</td>',
			'    <td>&nbsp;<span class="' + (info.cktimes > 0 ? 'cnoa_color_red' : '') + '">' + info.cktimes + '</span> ' + lang('bout') + '</td>',
			'    <td bgcolor="#F2F2F2">&nbsp;' + lang('leave') + '</td>',
			'    <td>&nbsp;<span class="' + (info.c_leave > 0 ? 'cnoa_color_red' : '') + '">' + info.c_leave + '</span> ' + lang('bout'),
			'  </tr>',
			'  <tr bgcolor="#FFFFFF">',
			'    <td height="24" bgcolor="#F2F2F2">&nbsp;' + lang('egress') + '</td>',
			'    <td>&nbsp;<span class="' + (info.c_egression > 0 ? 'cnoa_color_red' : '') + '">' + info.c_egression + '</span> ' + lang('bout'),
			'    <td bgcolor="#F2F2F2">&nbsp;' + lang('evection') + '</td>',
			'    <td>&nbsp;<span class="' + (info.c_evection > 0 ? 'cnoa_color_red' : '') + '">' + info.c_evection + '</span> ' + lang('bout'),
			
			'  </tr>',
			'</table>'].join("");
		};
		
		this.clock = new Ext.FlashComponent({
			url: "./resources/Clock.swf",
			width: 120,height:120,
			style: 'margin:10px'
		});

		this.contentPanel = new Ext.Panel({
			region: 'center',
			autoScroll: true,
			bodyStyle: 'padding:10px;',
			listeners: {
				afterrender : function(){
					me.getInfo();
				}
			}
		});

		this.clockPanel = new Ext.Panel({
			width: 130,
			region: 'west',
			items: [
				this.clock,
				new Ext.BoxComponent({
					autoEl: {
						tag: 'div',
						style: "width: 120px;height:44px;margin:0 10px;padding:4px;background-color:#F2F2F2;text-align:center",
						html: me.getClockPanelHtml()
					}
				})
			]
		});

		var tools = [], draggable = false;

		tools.push({
			id:'refresh',
			handler: function(){
				me.getInfo();
			}
		});

		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_JOBTYPE == "superAdmin")){
			draggable = true;
			tools.push({
				id:'close',
				handler: function(e, target, panel){
					panel.ownerCt.remove(panel, true);
					me.closeAction();
				}
			});
		};

		this.mainPanel = new Ext.ux.Portlet({
			xtype: "portlet",
			id: this.id,
			title: lang('personAttNew'),
			layout: 'border',
			height: 250,
			draggable: draggable,
			tools: tools,
			items: [this.clockPanel, this.contentPanel]
		});
	},

	getClockPanelHtml : function(){
		var today = new Date();
		var d = new Array(lang('sunday'), lang('monday'), lang('tuesday'), lang('wednesday'), lang('thursday'), lang('friday'), lang('saturday'));
		var DDDD = today.format('Y年m月d日');

		DDDD = DDDD + "<br />" + (CnDateofDateStr(today));
		DDDD = DDDD + "<br />" + d[today.getDay()];

		return DDDD;
	},

	getPersonAtt : function(){
		var me = this;
		var info = me.info;
		var list = info.data;
		var h = "";
		Ext.each(list, function(value, i){
			var item 	= '',
				hTime 	= value.time,
				date	= new Date(),
				nDate	= value.date,
				nTime	= Date.parse(nDate + ' ' + value.nowTime),
				time 	= Date.parse(nDate + ' ' + value.time),
				recTime = Date.parse(nDate + ' ' + value.recTime),
				stime 	= Date.parse(nDate + ' ' + value.stime),
				etime 	= Date.parse(nDate + ' ' + value.etime),
				explain	= value.explain,
				workType = value.workType,
				reason	= '<a href="javascript:void(0);" onclick="DESKTOPAPP_OBJ.doExplain(' + value.num + ',' + "'"+value.classes+"'"+ ');">' + lang('explainSituation') + '</a>';
			
			if (value.recTime == '') {
				if (value.isRest == 1) {
					item = '---------';
				} else if (nTime >= stime && nTime <= etime){
					if(explain != ''){
						item = lang('unRegister') + '<img src=/resources/images/icons/message.gif ext:qtip="'+ explain + '">';
					} else {
						item = '<a href="javascript:void(0);"'
							 + ' onclick="DESKTOPAPP_OBJ.doRecord'
							 + '(' + "'" + value.classes + "','" + value.num +  "','" + value.stime +  "','" + value.etime +  "','" + value.time +  "','" + value.workType + "'" + ');">'
							 + lang('register2') + '</a>';
					}
				} else if (nTime > etime){
					if(explain != ''){
						item =  lang('unRegister') + '<img src=/resources/images/icons/message.gif ext:qtip="'+ explain + '">';
					} else {
						item = reason;
					}
				} else if (nTime < stime){
					if(explain != ''){
						item =  lang('unRegister') + '<img src=/resources/images/icons/message.gif ext:qtip="'+ explain + '">';
					} else {
						item = lang('notInRegisterTime');
					}
				}
			} else {
				// if (workType == 0){
				// 	if (recTime >= stime && recTime <= time) {
				// 		item = value.recTime;
				// 	} else if (recTime > time && recTime <= etime) {
				// 		item = value.recTime + "<span style='color: red;'>(" + lang('late') + ")</sapn>";
				// 	}
				// } else {
				// 	if (recTime >= time && recTime <= etime){
				// 		item = value.recTime;
				// 	} else if (recTime >= stime && recTime < time) {
				// 		item = value.recTime + "<span style='color: red;'>(" + lang('leaveEarly') + ")</sapn>";
				// 	}
				// }
				item = value.recTime
				if (value.type == 1) {
					item += "<span style='color: red;'>(" + lang('late') + ")</sapn>";
				} else if (value.type == 2) {
					item += "<span style='color: red;'>(" + lang('leaveEarly') + ")</sapn>";
				};
			}

			if(i % 2 == 0){
				h += [
					'  <tr bgcolor="#FFFFFF">',
					'    <td height="24" width="20%" bgcolor="#F2F2F2">&nbsp;' + hTime + '</td>',
					'    <td width="30%">&nbsp;' + item + '</td>'
				].join("");
			}else{
				h += [
					'    <td width="20%" bgcolor="#F2F2F2">&nbsp;' + hTime + '</td>',
					'    <td width="30%">&nbsp;' + item + '</td>',
					'  </tr>'
				].join("");
			}
		});

		return h;
	},

	closeAction : function(){
		var me = this;
		CNOA_main_common_index.closeDesktopApp(me.id);
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},
	
	getInfo : function(){
		var me = this;
		
		Ext.Ajax.request({
			url: this.baseUrl + "&task=getChecktime4Widgts",
			method: 'GET',
			success: function(r){
				var sText = r.responseText;
				if(sText != ''){
					var jsData = Ext.decode(sText);
					me.checkPermit(jsData.noPermit);
					try{
						me.info = jsData;
						me.contentPanel.body.update(me.tpl(jsData));
					}catch(e){}
				}
			}
		});	
	},

	//登记
	doRecord : function(classes, num, stime, etime, time, workType) {
		var me = this;
		var data = {
			classes: classes,
			num: num,
			stime: stime,
			etime: etime,
			time: time,
			workType: workType
		};

		Ext.Ajax.request({
			url: me.baseUrl + '&task=addChecktime',
			method: 'post',
			params: data,
			success: function(response) {
				var responseText = Ext.decode(response.responseText);

				if (responseText.success == true) {
					CNOA.msg.notice(responseText.msg, lang('personAttNew'));
					me.getInfo();
				} else {
					CNOA.msg.alert(responseText.msg);
					me.getInfo();
				}
			}
		});
	},

	//记录说明情况
	doExplain : function(cnum, classes){
		var me = this;
		var postUrl = me.baseUrl + "&task=updateExplain";
		
		var submit = function(){
			var f = formPanel.getForm();
			if (f.isValid()) {	
				f.submit({
					url: postUrl,
					waitTitle: lang('notice'),
					method: 'POST',
					waitMsg: lang('waiting'),
					params: {cnum: cnum, classes: classes},
					success: function(form, action) {
						addEditWindow.close();
						me.getInfo();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}
				});
			}
		};
		
		var formPanel = new Ext.form.FormPanel({
			hideBorders: true,
			border: false,
			waitMsgTarget: true,
			labelWidth: 10,
			labelAlign: "right",
			bodyStyle: "padding: 10px",
			items: [
				{
					xtype: "label",
					text: lang('describeAboutIt') + '：'
				},
				{		
					xtype: 'textarea',
					width: 330,
					height: 160,
					maxLength: 255,
					name: 'explain',
					style: 'margin: 20px 0 0 0;'
				}
			]
		});
		
		var addEditWindow = new Ext.Window({
			width: 400,
			height: 300,
			modal: true,
			autoDestroy: true,
			closeAction: "close",
			resizable: false,
			title: lang('explainSituation'),
			layout: "fit",
			items: [formPanel],
			buttons: [
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler: function(){
						submit();
					}
				},
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						addEditWindow.close();
					}
				}
			]
		});
		addEditWindow.show();
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
