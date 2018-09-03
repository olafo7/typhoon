Ext.ns('CNOA.wf.query');

CNOA.wf.query = Ext.extend(Ext.Window, {
	constructor : function(detailid, bindfunc){
		this.detailid = detailid;
		this.bindfunc = bindfunc;
		CNOA.wf.query.superclass.constructor.call(this);
	},
	initComponent : function(){
		var me = this;
		me.baseUrl = "index.php?app=abutment&func=abutment&action=sqldetail";
		

		me.items = me.getItems();
		// CNOA.wf.query.superclass.initComponent.call(me);

		var box = Ext.getBody().getBox(),
			w	= box.width - 20,
			h	= box.height - 20;
			
		Ext.apply(me, {
			modal: true,
			width: w,
			height: h,
			maximizable: true,
			title: "自定义：SQL选择器",
			layout: 'fit',
			buttons: me.getButtons()
		});
		CNOA.wf.query.superclass.initComponent.call(me);
	},

	getItems : function(){
		var me = this;
		var list = new Ext.grid.CustomGridPanel({
			border: false,
			region: 'center',
			pageSize: 100,
			customFiledUrl: me.baseUrl+"&task=getCustomerCustomField&detailId="+this.detailid,
			storeUrl: me.baseUrl+"&task=getCustomerList&detailId="+this.detailid,
			loadMask : {msg: lang('waiting')},
			listeners:{
				afterrender: function(th){
					setTimeout(function(){
						th.store.reload()
					},200)
				}
			}
		});
		me.mainPanel = list;

		var searchPanel = new Ext.form.FormPanel({
			border: false,
			height: 150,
			hidden: true,
			region: 'north',
			labelAlign: 'right',
			padding: 5,
			autoScroll: true,
			split: true,
			defaults: {
				xtype: 'textfield'
			}
		});

		var tbar = new Ext.Toolbar({
			items: [
				{
					text: lang('expandSearch'),
					handler: function(th){
						if(searchPanel.isVisible()){
							searchPanel.hide();
						}else{
							th.setText(lang('foldSearch'));
							searchPanel.show();
						}
						panel.doLayout();
					}
				},{
					text:lang('search'),
					handler: function(th){
						me.searchParams = searchPanel.getForm().getValues();
						list.store.reload({params:me.searchParams});
						list.store.searchStore = me.searchParams;
					}
				},{
					text: lang('clear'),
					style: "margin-left:5px",
					handler: function(th){
						me.searchParams = {};
						Ext.each(searchPanel.getForm().items.items, function(item, index){
							var xtype = item.getXType();
							if(xtype=='compositefield' || xtype=='checkboxgroup'){
								Ext.each(item.items.items, function(i){
									i.setValue('');
								});
							}else{
								item.setValue('');
							}
						});
						list.store.reload({params:me.searchParams});
						list.searchStore = me.searchParams;
					}
				}
			]
		});
		
		//获取查询条件生成条件按钮
		Ext.Ajax.request({
			url: this.baseUrl + "&task=getCustomerSearchFields&detailId="+this.detailid,
			method: 'POST',
			success: function(r) {
				var condition = Ext.decode(r.responseText), item;
				for(var i = 0; i < condition.msg.length; i++){
					item = new Ext.form.TextField({
						fieldLabel: condition.msg[i].mapName,
						name: 'search|' + condition.msg[i].id
					})
					searchPanel.add(item);
				}
				searchPanel.doLayout();
			}
		});

		var panel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [searchPanel,list],
			tbar: tbar
		});
		return panel;
	},

	getButtons : function(){
		var me = this;
		return [
			{
				text: lang('determineSelect'),
				scope: this,
				handler: me.onOk
			},
			{
				text: lang('cancel'),
				scope: this,
				handler: function(){
					me.close();
				}
			}
		]
	},

	onOk : function(){
		var me=this,dt = [], tr, im, bi, hasFirstLine, le, bindid;
		var rows = me.mainPanel.getSelectionModel().getSelections();
		if(rows.length > 0){
			var length = rows.length;
			for (var i=0; i<length; i++){
				dt.push(rows[i].json);
			}
		}

		// var data1 = new Date();
		// var time1 = data1.getTime();

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

		// var data2 = new Date();
		// var time2 = data2.getTime();
		// console.log(time2-time1);

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
						if(input.attr("isnum") == 'true'){
							input.val(dt[j][key]).change();
						}else{
							input.val(dt[j][key]);
						}
					}
					bindid.val(dt[j]['id']);
					j++;
				}
				tr.find("[bindfield=count]").val(1).change();
			}
		}

		// var data3 = new Date();
		// var time3 = data3.getTime();
		// console.log(time3-time2);

		CNOA_wf_form_checker.bindMinMaxNumber();
		this.close();
	}
});


