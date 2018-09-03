Ext.ns('CNOA.wf.query');

CNOA.wf.query = Ext.extend(Ext.Window, {
	constructor : function(detailid, bindfunc){
		this.detailid = detailid;
		this.bindfunc = bindfunc;
		CNOA.wf.query.superclass.constructor.call(this);
	},
	initComponent : function(){
		this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new&bindfunction="+this.bindfunc;

		var box = Ext.getBody().getBox();
		var w	= box.width - 20;
		var h	= box.height - 20;

		this.replaceFirstLineCBID = Ext.id();
		this.ID_Search_title	  = Ext.id();
		this.ID_Search_sortid	  = Ext.id();
		this.ID_Search_libraryid  = Ext.id();

		Ext.apply(this, {
			modal: true,
			width: w,
			height: h,
			maximizable: true,
			title: lang('queryPanelStock'),
			layout: 'fit',
			items: this.getItems(),
			buttons: this.getButtons()
		});

		CNOA.wf.query.superclass.initComponent.call(this);
	},

	getItems : function(){
		var _this = this;

		this.libraryComboBoxDataStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl+"&task=getQueryList&from=library"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name:"libraryID", mapping: 'id'},
					{name:"name"}
				]
			})
		});
		this.libraryComboBoxDataStore.load();
		this.typeComboBoxDataStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl+"&task=getQueryList&from=type"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name:"typeID"},
					{name:"name"}
				]
			})
		});

		this.fields = [
			{name:"id", mapping: "id"},
			{name:"lib", mapping: "libraryName"},
			{name:"sort", mapping: "typeName"},
			{name:"name", mapping: "name"},
			{name:"number", mapping: "number"},
			{name:"price", mapping: "price"},
			{name:"guige", mapping: "standard"},
			{name:"danwei", mapping: "unit"},
			{name:"kucun", mapping: "stock"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getQueryList&from=list", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
			listeners : {
				exception : function(th, type, action, options, response, arg){
					//var result = Ext.decode(response.responseText);
					//if(result.failure){
					//	CNOA.msg.alert(result.msg);
					//}
				}
			}
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();

		//['lib', "所在库"], ['sort', lang('sort2')], ['name', "物品名称"], ['number', "物品编号"], ['guige', "物品规格"], ['danwei', "单位"], ['count', "数量"], ['danjia', "单价"], ['jine', "金额"]]

		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('itemName'), dataIndex: 'name', width: 110, sortable: false,menuDisabled :true},
			{header: lang('itemNum'), dataIndex: 'number', width: 110, sortable: false,menuDisabled :true},
			{header: lang('whereWarehouse'), dataIndex: 'lib', width: 110, sortable: true, menuDisabled :true},
			{header: lang('categorie'), dataIndex: 'sort', width: 110, sortable: false,menuDisabled :true},
			{header: lang('spec'), dataIndex: 'guige', width: 90, sortable: false,menuDisabled :true},
			{header: lang('unitPrice'), dataIndex: 'price', width: 90, sortable: false,menuDisabled :true},
			{header: lang('unit'), dataIndex: 'danwei', width: 60, sortable: false,menuDisabled :true},
			{header: lang('stockNum'), dataIndex: 'kucun', width: 60, sortable: false,menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		// var pagingBar = new Ext.PagingToolbar({
  //           plugin: [new Ext.grid.plugins.ComboPageSize()],
  //           style: "border-left-width:1px;",
  //           displayInfo: true, emptyMsg:lang('pagingToolbarEmptyMsg'), displayMsg:lang('showDataTotal2'),  
  //           store: _this.store,
  //           pageSize: 15
  //       });

        var pagingBar = new Ext.PagingToolbar({
			plugins: [new Ext.grid.plugins.ComboPageSize()],
			displayInfo:true,
			store: _this.store,
			pageSize: 2
		});
		
		this.mainPanel = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			sm: this.sm,
       		stripeRows : true,
			hideBorders: true,
			border: false,
			region: "center",
			bbar: pagingBar,
			viewConfig: {
				forceFit: true
			},
			tbar : new Ext.Toolbar({
				items: [
					lang('supLi') + ':',
					new CNOA.form.ComboBox({
						fieldLabel: lang('offSupLi'),
						name: 'libraryID',
						store: this.libraryComboBoxDataStore,
						hiddenName: 'libraryID',
						valueField: 'libraryID',
						displayField:'name',
						mode: 'local',
						width: 120,
						triggerAction:'all',
						forceSelection: true,
						editable: false,
						id: this.ID_Search_libraryid,
						helpTip: lang('adminShowName'),
						listeners:{
							select : function(th, record, index){
								Ext.getCmp(_this.ID_Search_sortid).setValue("");
								_this.typeComboBoxDataStore.load({params: {id: th.getValue()}});
							}.createDelegate(this)
						}
					}),
					lang('sort') + ':',
					new CNOA.form.ComboBox({
						fieldLabel: lang('suppCategory'),
						name: 'typeID',
						id: this.ID_Search_sortid,
						store: this.typeComboBoxDataStore,
						hiddenName: 'typeID',
						valueField: 'typeID',
						displayField:'name',
						mode: 'local',
						width: 120,
						helpTip: lang('selectKuOtherEmpey'),
						triggerAction:'all',
						forceSelection: true,
						editable: false
					}),
					lang('supName') + ':',
					{
						xtype: "textfield",
						id: this.ID_Search_title,
						width: 120
					},
					{
						xtype: "button",
						text: lang('search'),
						style: "margin-left:5px",
						handler: function(){
							_this.doSearch();
						}
					},'-',
					{
						xtype: "button",
						text: lang('clear'),
						style: "margin-right:5px",
						handler: function(){
							_this.clearSearch();
						}
					},'-',
					{
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					}
				]
			})
		});

		return this.mainPanel;
	},

	getButtons : function(){
		return [
			{
				xtype: 'checkbox',
				boxLabel: lang('replaceFirstLine'),
				id: this.replaceFirstLineCBID
			},
			{
				text: lang('determineSelect'),
				scope: this,
				handler: this.onOk
			},
			{
				text: lang('cancel'),
				scope: this,
				handler: function(){
					this.close();
				}
			}
		]
	},

	onOk : function(){
		var dt = [], tr, im, bi, hasFirstLine, le, bindid;
		var rows = this.mainPanel.getSelectionModel().getSelections();
		if(rows.length > 0){
			for (var i=0; i<rows.length; i++){
				dt.push(rows[i].data);
			}
		}
		
		//如果选择替换第一行数据
		var replaceFirstLine = Ext.getCmp(this.replaceFirstLineCBID).getValue();
		if(dt.length>0 && replaceFirstLine){
			tr = $($(".wf_form_content tr[class=detail-line][detail="+this.detailid+"]").get(0));
			bindid = tr.find("input:hidden[id^=wf_detailbid_]");
			var data = dt[0];
			for(var key in data){
				var input = tr.find("[bindfield="+key+"]");
				if(key == 'kucun' && this.bindfunc!='admarticlesb'){
					var max = data['kucun'].replace(/<span.*>([0-9]{1,})<\/span>/ig, "$1");
					var countfield = tr.find("[bindfield=count]");
					countfield.attr('maxnum', max);
					countfield.attr('maxnumtext', '库存');
					
					var maxname = countfield.attr('name').replace(/wf_detail_/ig, 'wf_detailmax_');
					var maxfield = $('<input type="hidden" name="'+maxname+'" value="'+max+'">');
					
					countfield.before(maxfield);
				}
				input.val(data[key]).change();
			}
			bindid.val(data['id']);
			tr.find("[bindfield=count]").val(1).change();
			dt.shift();
		}

		//给明细表添加新行
		if(dt.length>0){
			tr = $(".wf_form_content tr[class=detail-line][detail="+this.detailid+"]");
			im = $(tr.find("img[class=wf_row_jia][queryadd=true]")[0]);
			bi = $(tr[0]).find("input:hidden[id^=wf_detailbid_]");
			hasFirstLine = !Ext.isEmpty(bi.val());
			le = !hasFirstLine ? (dt.length-1) : dt.length;
			for (var i=0; i<le; i++){
				im.click();
			}
		}

		//给添加的新行填充数据
		//找出最后几行
		if(dt.length>0){
			var trs = $(".wf_form_content tr[class=detail-line][detail="+this.detailid+"]");

			var j=0;
			for (var i=0; i<trs.length; i++){
				var tr = $(trs[i]);
				bindid = tr.find("input:hidden[id^=wf_detailbid_]");
				if(Ext.isEmpty(bindid.val())){
					for(var key in dt[j]){
						var input = tr.find("[bindfield="+key+"]");
						if(key == 'kucun' && this.bindfunc!='admarticlesb'){
							var max = dt[j]['kucun'].replace(/<span.*>([0-9]{1,})<\/span>/ig, "$1");
							var countfield = tr.find("[bindfield=count]");
							countfield.attr('maxnum', max);
							countfield.attr('maxnumtext', '库存');
							
							var maxname = countfield.attr('name').replace(/wf_detail_/ig, 'wf_detailmax_');
							var maxfield = $('<input type="hidden" name="'+maxname+'" value="'+max+'">');
							
							countfield.before(maxfield);
						}
						input.val(dt[j][key]).change();
					}
					bindid.val(dt[j]['id']);
					j++;
				}
				if (tr.find("[bindfield=count]").val() == ''){
					tr.find("[bindfield=count]").val(1).change();
				}
			}
		}
		
		CNOA_wf_form_checker.bindMinMaxNumber();

		this.close();
	},
	
	doSearch : function(){
		var title = Ext.getCmp(this.ID_Search_title).getValue(),
			sortid = Ext.getCmp(this.ID_Search_sortid).getValue(),
			libraryid = Ext.getCmp(this.ID_Search_libraryid).getValue();

		this.store.load({params: {title: title, sortid: sortid, libraryid: libraryid}});
	},

	clearSearch : function(){
		Ext.getCmp(this.ID_Search_title).setValue("");
		Ext.getCmp(this.ID_Search_sortid).setValue("");
		Ext.getCmp(this.ID_Search_libraryid).setValue("");

		Ext.getCmp(this.ID_Search_sortid).getStore().removeAll();

		this.store.load();
	}
});


