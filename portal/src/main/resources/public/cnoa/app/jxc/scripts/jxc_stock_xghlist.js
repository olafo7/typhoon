/**
 * 库存查询 - 强克农资 - 与象过河软件对接
 */

var dttClass = function(){
	/** 
	* 获取本周、本季度、本月、上月的开端日期、停止日期 
	*/
	var now = new Date(); //当前日期 
	var nowDayOfWeek = now.getDay(); //今天本周的第几天 
	var nowDay = now.getDate(); //当前日 
	var nowMonth = now.getMonth(); //当前月 
	var nowYear = now.getYear(); //当前年 
	nowYear += (nowYear < 2000) ? 1900 : 0; //
	var lastMonthDate = new Date(); //上月日期 
	lastMonthDate.setDate(1);
	lastMonthDate.setMonth(lastMonthDate.getMonth() - 1);
	var lastYear = lastMonthDate.getYear();
	var lastMonth = lastMonthDate.getMonth();

	//格局化日期：yyyy-MM-dd 
	function formatDate(date) {
		var myyear = date.getFullYear();
		var mymonth = date.getMonth() + 1;
		var myweekday = date.getDate();

		if (mymonth < 10) {
			mymonth = "0" + mymonth;
		}
		if (myweekday < 10) {
			myweekday = "0" + myweekday;
		}
		return (myyear + "-" + mymonth + "-" + myweekday);
	}

	this.getPrevYearStart=function(){
        var currentYear=now.getFullYear(); 
        var currentYearFirstDate=new Date(currentYear-1,0,1);
		return formatDate(currentYearFirstDate);
    }; 

	this.getPrevYearEnd=function(){
        var currentYear=now.getFullYear(); 
        var currentYearLastDate=new Date(currentYear-1,11,31);
        return formatDate(currentYearLastDate);
    }; 

	this.getCurrentYearStart=function(){
        var currentYear=now.getFullYear(); 
        var currentYearFirstDate=new Date(currentYear,0,1);
		return formatDate(currentYearFirstDate);
    }; 

	this.getCurrentYearEnd=function(){
        var currentYear=now.getFullYear(); 
        var currentYearLastDate=new Date(currentYear,11,31);
        return formatDate(currentYearLastDate);
    }; 

	//获得某月的天数 
	function getMonthDays(myMonth) {
		var monthStartDate = new Date(nowYear, myMonth, 1);
		var monthEndDate = new Date(nowYear, myMonth + 1, 1);
		var days = (monthEndDate - monthStartDate) / (1000 * 60 * 60 * 24);
		return days;
	}

	//获得本季度的开端月份 
	function getQuarterStartMonth() {
		var quarterStartMonth = 0;
		if (nowMonth < 3) {
			quarterStartMonth = 0;
		}
		if (2 < nowMonth && nowMonth < 6) {
			quarterStartMonth = 3;
		}
		if (5 < nowMonth && nowMonth < 9) {
			quarterStartMonth = 6;
		}
		if (nowMonth > 8) {
			quarterStartMonth = 9;
		}
		return quarterStartMonth;
	}

	//获得本周的开端日期 
	this.getWeekStartDate = function () {
		var weekStartDate = new Date(nowYear, nowMonth, nowDay - nowDayOfWeek + 1);
		return formatDate(weekStartDate);
	}

	//获得本周的停止日期 
	this.getWeekEndDate = function () {
		var weekEndDate = new Date(nowYear, nowMonth, nowDay + (6 - nowDayOfWeek + 1));
		return formatDate(weekEndDate);
	}

	//获得本月的开端日期 
	this.getMonthStartDate = function () {
		var monthStartDate = new Date(nowYear, nowMonth, 1);
		return formatDate(monthStartDate);
	}

	//获得本月的停止日期 
	this.getMonthEndDate = function () {
		var monthEndDate = new Date(nowYear, nowMonth, getMonthDays(nowMonth));
		return formatDate(monthEndDate);
	}

	//获得上月开端时候 
	this.getLastMonthStartDate = function () {
		var lastMonthStartDate = new Date(nowYear, lastMonth, 1);
		return formatDate(lastMonthStartDate);
	}

	//获得上月停止时候 
	this.getLastMonthEndDate = function () {
		var lastMonthEndDate = new Date(nowYear, lastMonth, getMonthDays(lastMonth));
		return formatDate(lastMonthEndDate);
	}

	//获得本季度的开端日期 
	this.getQuarterStartDate = function () {

		var quarterStartDate = new Date(nowYear, getQuarterStartMonth(), 1);
		return formatDate(quarterStartDate);
	}

	//或的本季度的停止日期 
	this.getQuarterEndDate = function () {
		var quarterEndMonth = getQuarterStartMonth() + 2;
		var quarterStartDate = new Date(nowYear, quarterEndMonth, getMonthDays(quarterEndMonth));
		return formatDate(quarterStartDate);
	}

	this.getPreviousWeekStart = function () {
		var start,
		end,
		dayMSec = 24 * 3600 * 1000;
		today = now.getDay() - 1;
		end = now.getTime() - today * dayMSec;
		start = end - 7 * dayMSec;
		return formatDate(new Date(start));
	}

	this.getPreviousWeekEnd = function () {
		var start,
		end,
		dayMSec = 24 * 3600 * 1000;
		today = now.getDay();
		end = now.getTime() - today * dayMSec;
		return formatDate(new Date(end));
	}
}

var dtt = new dttClass();
 
//定义全局变量：
var CNOA_jxc_stock_xghlistClass, CNOA_jxc_stock_xghlist;
CNOA_jxc_stock_xghlistClass = CNOA.Class.create();
CNOA_jxc_stock_xghlistClass.prototype = {
	init: function(){
		var _this = this;
		this.baseUrl = "index.php?app=jxc&func=stock&action=xghlist";

		this.ID_chanku = Ext.id();
		this.ID_chanku_hide = Ext.id();
		this.ID_name = Ext.id();

		this.storeBar = {chanku: 0, name: ''};

		this.fields = [
			{name:"GOODS_ID"},
			{name:"GOODS_NAME"},
			{name:"GOODS_UNIT"},
			{name:"SUMNUM"},
			{name:"SUMMONEY"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields, unread: "unread"}),
			listeners: {
				load : function(store, records, options){
					
				}
			}
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: true
		});
		this.store.load({params:{from: _this.from}});
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "GOODS_ID", dataIndex: 'GOODS_ID', hidden: true},
			{header: lang('productName'), dataIndex: 'GOODS_NAME', id: 'GOODS_NAME', width: 250, sortable: true, menuDisabled :true},
			{header: lang('unit'), dataIndex: 'GOODS_UNIT', width: 80, sortable: false, menuDisabled :true},
			{header: lang('quantity'), dataIndex: 'SUMNUM', width: 80, sortable: false, menuDisabled :true},
			{header: lang('stockAmount'), dataIndex: 'SUMMONEY', width: 80, sortable: false,menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.grid = new Ext.grid.PageGridPanel({//PageGridPanel No Need
			stripeRows : true,
			pageSize: 25,
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			sm: this.sm,
			autoExpandColumn: 'GOODS_NAME',
			hideBorders: true,
			border: false,
			searchStore: this.storeBar,
			region: "center",
			listeners: {
				rowdblclick : function(th, rowIndex){
					var name = th.getStore().getAt(rowIndex).get("GOODS_NAME"),
						id = th.getStore().getAt(rowIndex).get("GOODS_ID");
					_this.showDetailList(id, name);
				}
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						text:lang('refresh'),
						iconCls:'icon-system-refresh',
						handler:function(){
							_this.store.reload()
						}
					},/*
					"仓库:",
					{
						xtype: 'textfield',
						id: this.ID_chanku,
						readOnly: true,
						width: 150,
						listeners: {
							'focus' : function(th){
								_this.showStorageList();
							}
						}
					},{
						xtype: 'hidden',
						id: this.ID_chanku_hide
					},*/
					lang('productName') + ":",
					{
						xtype: 'textfield',
						id: this.ID_name,
						width: 150
					},
					{
						xtype: 'button',
						text: lang('search'),
						handler: function(){
							_this.storeBar.name = Ext.getCmp(_this.ID_name).getValue();
							_this.store.load({params: _this.storeBar});
						}
					},
					{
						xtype: 'button',
						text: lang('clear'),
						handler: function(){
							Ext.getCmp(_this.ID_name).setValue("");
							_this.storeBar.name = "";
							_this.store.load({params: _this.storeBar});
						}
					},"<span style='color:#999'>" + lang('dblClickToView') + lang('selectGoods') + "</span>"
				]
			})
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid]//, this.bottomPanel
		});
	},

	showStorageList : function(){
		var _this = this;
		var fields = [
			{name:"id"},
			{name:"NAME_"},
			{name:"CODE_"},
			{name:"PY_"}
		];

		var store = new Ext.data.Store({
			autoLoad : true,
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + '&task=getStorageList'}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})		
		});
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:true
		});

		var colum = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			sm,
			{header: "id", dataIndex: 'id', sortable: true, hidden: true},
			{header: lang('houseName') , width: 196 , sortable: true, dataIndex: 'NAME_'},
			{header: lang('houseCoding') , width: 120 , sortable: true, dataIndex: 'CODE_'},
			{header: lang('pinYinCode') , width: 100 , sortable: true, dataIndex: 'PY_'}
		]);

		var grid = new Ext.grid.GridPanel({
			store : store,
			sm: sm,
			hideBorders: true,
			border: false,
			colModel : colum
		});

		var mainPanel = new Ext.Window({
			layout:"fit",
			width: 500,
			height: 360,
			resizable: false,
			modal: true,
			items: [grid],
			plain: true,
			title: lang('selectHouse'),
			buttonAlign: "right",
			autoScroll: true,
			buttons: [
				//关闭
				{
					text: lang('ok'),
					iconCls: 'icon-btn-save',
					handler: function() {
						var rows = grid.getSelectionModel().getSelections(), id=0, name = "";

						if (rows.length == 0) {
							CNOA.msg.alert(lang('pleaseSelctOne'));
						} else {
							id = rows[0].get("id");
							name = rows[0].get("NAME_");
							mainPanel.close();
						}
					}
				},
				//关闭
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function() {
						mainPanel.close();
					}
				}
			]
		}).show();
	},
	
	showDetailList : function(id, name){
		var _this = this,

		ID_find_stime = Ext.id(),
		ID_find_etime = Ext.id(),
		storeBar = {stime:'', etime:''};

		var fields = [
			{name:"BILL_DATE"},
			{name:"BILL_NO"},
			{name:"BILLTYPE"},
			{name:"BUSIUSER_NAME"},
			{name:"STO_NAME"},
			{name:"NUM_IN"},
			{name:"NUM_OUT"},
			{name:"NUM_END"},
			{name:"COST_PRICE"},
			{name:"SUM_MONEY_COST"},
			{name:"SUM_MONEY"}
		];

		var store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + '&task=getDetailList&id='+id}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})		
		});

		store.load({params: {stime: dtt.getMonthStartDate(), etime: dtt.getMonthEndDate()}});
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:true
		});

		var colum = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer({width:40}),
			sm,
			{header: lang('receiptDate'), width: 77, sortable: true, dataIndex: 'BILL_DATE'},
			{header: lang('receiptNum'), width: 150, sortable: true, dataIndex: 'BILL_NO'},
			{header: lang('receiptType'), width: 100, sortable: true, dataIndex: 'BILLTYPE'},
			{header: lang('personHand'), width: 66, sortable: true, dataIndex: 'BUSIUSER_NAME'},
			{header: lang('housr'), width: 100, sortable: true, dataIndex: 'STO_NAME'},
			{header: lang('bePutQuantity'), width: 86, sortable: true, dataIndex: 'NUM_IN'},
			{header: lang('chuKuShu'), width: 86, sortable: true, dataIndex: 'NUM_OUT'},
			{header: lang('kCYL'), width: 86, sortable: false, dataIndex: 'NUM_END'},
			{header: lang('cost'), width: 86, sortable: true, dataIndex: 'COST_PRICE'},
			{header: lang('costAmount'),  width: 86, sortable: true, dataIndex: 'SUM_MONEY_COST'},
			{header: lang('yEXJ'), width: 86, sortable: false, dataIndex: 'SUM_MONEY'}
		]);

		var grid = new Ext.grid.GridPanel({
			store : store,
			sm: sm,
			loadMask : {msg: lang('waiting')},
			hideBorders: true,
			border: false,
			colModel : colum
		});

		var box = Ext.getBody().getBox();
		var w	= box.width - 50;
		var h	= box.height - 50;
		var combo = new Ext.form.ComboBox({
			store: new Ext.data.SimpleStore({
				fields: ['value', 'quick'],
				data: [['1', lang('All')], ['2', lang('today')], ['3', lang('yesterday')], ['4', lang('thisWeek')], ['5', lang('lastWeek')], ['6', lang('thisMonth')], ['7', lang('lastMonth')], ['8', lang('thisYeat')], ['9', lang('prevYear')]]
			}),
			width: 75,
			value: 6,
			hiddenName: 'quick',
			valueField: 'value',
			displayField: 'quick',
			mode: 'local',
			triggerAction: 'all',
			forceSelection: true,
			editable: false,
			listeners: {
				select : function(combo, record, index){
					var v = record.data.value,
						stime = '';
						etime = '';
					if(v == 1){
						stime = ''; etime = '';
					}
					if(v == 2){
						stime = new Date().format("Y-m-d"); etime = '';
					}
					if(v == 3){
						var vv  = new Date((new Date()).add(Date.DAY, -1)).format("Y-m-d");
						stime = vv; etime = vv;
					}
					if(v == 4){
						var v1  = dtt.getWeekStartDate();
						var v2  = dtt.getWeekEndDate();
						stime = v1; etime = v2;
					}
					if(v == 5){
						var v1  = dtt.getPreviousWeekStart();
						var v2  = dtt.getPreviousWeekEnd();
						stime = v1; etime = v2;
					}
					if(v == 6){
						var v1  = dtt.getMonthStartDate();
						var v2  = dtt.getMonthEndDate();
						stime = v1; etime = v2;
					}
					if(v == 7){
						var v1  = dtt.getLastMonthStartDate();
						var v2  = dtt.getLastMonthEndDate();
						stime = v1; etime = v2;
					}
					if(v == 8){
						var v1  = dtt.getCurrentYearStart();
						var v2  = dtt.getCurrentYearEnd();
						stime = v1; etime = v2;
					}
					if(v == 9){
						var v1  = dtt.getPrevYearStart();
						var v2  = dtt.getPrevYearEnd();
						stime = v1; etime = v2;
					}

					Ext.getCmp(ID_find_stime).setValue(stime);
					Ext.getCmp(ID_find_etime).setValue(etime);
					storeBar.stime = stime;
					storeBar.etime = etime;
					store.load({params: storeBar});
				}
			}
		});

		var mainPanel = new Ext.Window({
			layout:"fit",
			width: w,
			height: h,
			maximizable: true,
			modal: true,
			items: [grid],
			plain: true,
			title: lang('detailGoods') + " - 《 " + name + " 》",
			buttonAlign: "right",
			autoScroll: true,
			buttons: [
				//关闭
				{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function() {
						mainPanel.close();
					}
				}
			],
			tbar: [
				{
					text:lang('refresh'),
					iconCls:'icon-system-refresh',
					handler:function(){
						store.reload()
					}
				},
				lang('quickSelect') + '：',
				combo,
				lang('timeFrom') + '：',
				{
					xtype: 'datefield',
					id: ID_find_stime,
					name: 'stime',
					format: 'Y-m-d',
					//maxValue: new Date(),
					value: dtt.getMonthStartDate(),
					width: 115
				},
				lang('to'),
				{
					xtype: 'datefield',
					id: ID_find_etime,
					name: 'etime',
					//maxValue: new Date(),
					value: dtt.getMonthEndDate(),
					format: 'Y-m-d',
					width: 115
				},
				{
					xtype: 'button',
					text: lang('search'),
					iconCls: 'icon-search',
					handler: function(){
						storeBar.stime = Ext.getCmp(ID_find_stime).getRawValue();
						storeBar.etime = Ext.getCmp(ID_find_etime).getRawValue();
						store.load({params: storeBar});
					}
				},
				{
					xtype: 'button',
					text: lang('clear'),
					handler: function(){
						Ext.getCmp(ID_find_stime).setValue("");
						Ext.getCmp(ID_find_etime).setValue("");
						combo.setValue(1);
						storeBar.stime = "";
						storeBar.etime = "";
						store.load({params: storeBar});
					}
				}
			]
		}).show();
	}
};

Ext.onReady(function() {
	CNOA_jxc_stock_xghlist = new CNOA_jxc_stock_xghlistClass();
	Ext.getCmp(CNOA.jxc.stock.xghlist.parentID).add(CNOA_jxc_stock_xghlist.mainPanel);
	Ext.getCmp(CNOA.jxc.stock.xghlist.parentID).doLayout();
});