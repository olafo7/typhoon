Ext.ns('CNOA.wf.query');

CNOA.wf.query = Ext.extend(Ext.Window, {
	constructor : function(detailid, bindfunc){
		this.detailid = detailid;
		this.bindfunc = bindfunc;
		CNOA.wf.query.superclass.constructor.call(this);
	},
	initComponent : function(){
		var me = this;
		me.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new&&task=getQueryList&bindfunction="+this.bindfunc;
		
		Ext.Ajax.request({
			url: me.baseUrl+"&operate=getStorageBindWidgetId",
			success: function(response){
				var widgetId = response.responseText;
				storageId = $('#wf_engine_field_'+widgetId).val();
				if(storageId){
					me.items = me.getItems(storageId);
					CNOA.wf.query.superclass.initComponent.call(me);
				}else{
					me.close();
					CNOA.msg.alert(lang('selectWarehouse'));
				}
			}
		});
		var box = Ext.getBody().getBox(),
			w	= box.width - 20,
			h	= box.height - 20;
			
		Ext.apply(me, {
			modal: true,
			width: w,
			height: h,
			maximizable: true,
			title: lang('queryPanelJXCquery'),
			layout: 'fit',
			buttons: me.getButtons()
		});
		CNOA.wf.query.superclass.initComponent.call(me);
	},

	getItems : function(storageId){
		var me = this;
		var storeBar = {
			goodsname : "",
			standard : "",
			priceStart : 0,
			priceEnd : 0,
			sid : 0,
			fields: "",
			fieldName: "",
			goodsCode: ""
		};

		var list = new Ext.grid.CustomGridPanel({
			page: true,
			pageSize: 15,
			singleSelect: false,
			searchStore: storeBar,
			border: false,
			region: 'center',
			customFiledUrl: me.baseUrl+"&operate=getGoodsCustomField",
			storeUrl: me.baseUrl+"&operate=getGoodsList&storageId="+storageId,
			loadMask : {msg: lang('waiting')}
		});
		me.mainPanel = list;
		
		var search_goodsname = new Ext.form.TextField({width:80}),
			search_goodsCode = new Ext.form.TextField({width:80}),
			search_standard= new Ext.form.TextField({width:80}),
			search_priceStart = new Ext.form.NumberField({width:80}),
			search_priceEnd = new Ext.form.NumberField({width:80}),
			search_sid = new Ext.form.ComboBox({
				width:90,
				typeAhead: true,
			    triggerAction: 'all',
			    lazyRender:true,
			    mode: 'local',
			    valueField: 'id',
			    displayField: 'name',
			    store: new Ext.data.Store({
					autoLoad: true,
					baseParams: storeBar,
					proxy:new Ext.data.HttpProxy({url: me.baseUrl+"&operate=getComboList&storageId="+storageId, disableCaching: true}),   
					reader:new Ext.data.JsonReader({totalProperty:"total", root:"data", fields: [{name: 'id'}, {name: 'name'}]})		
				})
			}),
			search_fields = new Ext.form.ComboBox({
				width:90,
				typeAhead: true,
			    triggerAction: 'all',
			    lazyRender:true,
			    mode: 'local',
			    valueField: 'field',
			    displayField: 'fieldname',
			    store: new Ext.data.Store({
					autoLoad: true,
					baseParams: storeBar,
					proxy:new Ext.data.HttpProxy({url: me.baseUrl+"&operate=getOnlyCustomField&storageId="+storageId, disableCaching: true}),   
					reader:new Ext.data.JsonReader({totalProperty:"total", root:"data", fields: [{name: 'field'}, {name: 'fieldname'}]})		
				})
			}),
			search_fieldName = new Ext.form.TextField({width:80});

		var panel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			keys:[{
	            key:13,  
	            fn:function(){
	                document.getElementById("search").click();
	            },
	            scope:this
	        }],
			items: [list],
			tbar : [
				lang('productName')+':',
				search_goodsname,

				lang('goodsCode')+':',
				search_goodsCode,
				
				lang('spec')+":",
				search_standard, 
				
				lang('seller')+":",
				search_sid,

				lang('priceRange')+":",
				search_priceStart," - ",search_priceEnd,"-",

				lang('searchCustomField')+":",
				search_fields," ",
				search_fieldName,


				{
					text:lang('search'),
					id: 'search',
					iconCls:'icon-hr-search',
					handler: function(button){
						storeBar.goodsname = search_goodsname.getValue();
						storeBar.goodsCode = search_goodsCode.getValue();
						storeBar.standard 	= search_standard.getValue();
						storeBar.priceStart 	= search_priceStart.getValue();
						storeBar.priceEnd 	= search_priceEnd.getValue();
						storeBar.sid 		= search_sid.getValue();
						storeBar.fields 	= search_fields.getValue();
						storeBar.fieldName 	= search_fieldName.getValue();

						list.store.reload({params:storeBar});
						list.searchStore = storeBar;
					}	
				},
				{
					text:lang('clear'),
					handler: function(button){
						search_goodsname.setValue();
						search_goodsCode.setValue();
						search_standard.setValue();
						search_priceStart.setValue();
						search_priceEnd.setValue();
						search_sid.setValue();
						search_fields.setValue();
						search_fieldName.setValue();
						
						storeBar.goodsname = "";
						storeBar.goodsCode = "";
						storeBar.standard 	= "";
						storeBar.priceStart 	= 0;
						storeBar.priceEnd 	= 0;
						storeBar.sid 		= 0;
						storeBar.fields 	= "";
						storeBar.fieldName = "";
						
						list.store.reload({params:storeBar});
						list.searchStore = storeBar;
					}
				}
			]
		});
		return panel;
	},

	getButtons : function(){
		var me = this;
		return [
			{
				xtype: 'checkbox',
				boxLabel: lang('replaceFirstLine'),
				id: me.replaceFirstLineCBID
			},
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

		//如果选择替换第一行数据
//		var replaceFirstLine = Ext.getCmp(this.replaceFirstLineCBID).getValue();
//		if(dt.length>0 && replaceFirstLine){
//			tr = $($(".wf_form_content tr[class=detail-line][detail="+this.detailid+"]").get(0));
//			bindid = tr.find("input:hidden[id^=wf_detailbid_]");
//			var data = dt[0];
//			for(var key in data){
//				var input = tr.find("[bindfield="+key+"]");
//				if(key == 'quantity' && this.bindfunc!='jxcChuku'){
//					var max = data['quantity'].replace(/<span.*>([0-9]{1,})<\/span>/ig, "$1");
//					var countfield = tr.find("[bindfield=count]");
//					countfield.attr('maxnum', max);
//					countfield.attr('maxnumtext', '库存');
//					
//					var maxname = countfield.attr('name').replace(/wf_detail_/ig, 'wf_detailmax_');
//					var maxfield = $('<input type="hidden" name="'+maxname+'" value="'+max+'">');
//					
//					countfield.before(maxfield);
//				}
//				input.val(data[key]).change();
//			}
//			bindid.val(data['id']);
//			tr.find("[bindfield=count]").val(1).change();
//			dt.shift();
//		}

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
		
		CNOA_wf_form_checker.bindMinMaxNumber();

		this.close();
	}
});


