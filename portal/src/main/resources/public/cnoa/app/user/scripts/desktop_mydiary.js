/**
 * 桌面工作日记
 */
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl	= "index.php?app=user&func=diary&action=index";
		this.id			= "CNOA_MAIN_DESKTOP_USER_DIARY";
		if(portalsID) this.id += portalsID;

		this.calendar = new Ext.Calendar({
			style: "border:0;",
			format: "Y-m-d",
			region:'west',
			cancelFocus: true,
			listeners: {
				"select" : function(dp, dt){
					var ydt = dt.getFullYear()+"-"+(dt.getMonth()+1)+"-"+dt.getDate();
					_this.showDetailPanel(ydt);
				},
				
				"monthchange" : function(dp, dt){
					var newYearMonth = dt.getFullYear()+"-"+(dt.getMonth()+1);
					_this.changeMonth(newYearMonth);
				},
				
				"afterrender" : function(th){
					if(DESKTOPAPP_OBJ.goDate != ""){
						var a = DESKTOPAPP_OBJ.goDate;
						var p = /([0-9]{4,4})-([0-9]{1,2})-([0-9]{1,2})/g;
						if(p.test(a)){
							var as = a.split("-");
							var dt = new Date(parseInt(as[0]), parseInt(as[1])-1, parseInt(as[2]));
							setTimeout(function(){
								th.goToDate(dt);
							}, 500);
						}
					}
				}
			}
		});
		
		var fields = [
			{name:"id"},
			{name:"did"},
			{name:"plantime"},
			{name:"posttime"},
			{name:"content"},
			{name:"date"}
		];
		
		var colModelDiary = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', hidden: true},
			{header: "did", dataIndex: 'did', hidden: true},
			{header: lang('content'), dataIndex: 'content', width: 210, sortable: false,menuDisabled :true},
			{header: lang('executionTime'), dataIndex: 'plantime', width: 100, sortable: false,menuDisabled :true}
		]);
		
		this.storeDiary = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=listMydiary"}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
			listeners: {
				load: function(st, rd){
					if(st.getCount() == 0){
						st.add(new Ext.data.Record({content: "<span class='cnoa_color_gray'>" + lang('nodata') + "</span>"}));
					}
				}
			}	
		});
		
		var searchField = new Ext.ux.form.SearchField({
            store: this.storeDiary,
			allowBlank:false,
            width: 110
        });
		
		searchField.onTrigger1Click = function(e){
			if(this.hasSearch){
	           this.el.dom.value = '';
	           var o = {start: 0};
	           this.store.baseParams = this.store.baseParams || {};
	           this.store.baseParams[this.paramName] = '';
	           this.store.removeAll();
	           this.triggers[0].hide();
	           this.hasSearch = false;
	        }
		};
		
		searchField.onTrigger2Click = function(e){
			var v = this.getRawValue();
	        if(v.length < 1){
	            alert(lang('searchNotEmpty'))
	            return;
	        }
	        var o = {start: 0};
	        this.store.baseParams = this.store.baseParams || {};
	        this.store.baseParams[this.paramName] = v;
	        this.store.reload({params:o});
	        this.hasSearch = true;
	        this.triggers[0].show();
		};
		
		this.grid = new Ext.grid.GridPanel({
			region:'center',
			border:true,
			style: 'border-left-width:1px;',
			store: this.storeDiary,
			loadMask : {msg: lang('waiting')},
			cm: colModelDiary,
			hideBorders: true,
			viewConfig: {
				forceFit: true
			},
			listeners:{  
				"rowclick" : function(grid, rowIndex, e){
					var record = grid.getStore().getAt(rowIndex);

					if(!Ext.isEmpty(record.data.date)){
						_this.rowClick(grid, record);
						try{
							mainPanel.closeTab("CNOA_MENU_USER_DIARY_MY");
							mainPanel.loadClass("index.php?app=user&func=diary&action=index&goDate="+record.data.date, "CNOA_MENU_USER_DIARY_MY", lang('myDiary'), "icon-diary-my");
						}catch (e){}
					}
				}
			},
			tbar:new Ext.Toolbar({
				style: 'border-width:0px;',
				items:[
					"->",searchField
				]
			})
		});

		var tools = [], draggable = false;
		tools.push({
			id: "plus",
			qtip: lang('clickEnterThisDay'),
			handler: function(e, target, panel){
				try{
					mainPanel.closeTab("CNOA_MENU_USER_DIARY_MY");
					mainPanel.loadClass("index.php?app=user&func=diary&action=index", "CNOA_MENU_USER_DIARY_MY", lang('myDiary'), "icon-diary-my");
				}catch (e){}
			}
		});
		tools.push({
			id:'refresh',
			handler: function(e, target, panel){
				_this.storeDiary.reload();
			}
		});
		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_JOBTYPE == "superAdmin")){
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
			hideHeaders : true,
			title: lang('workDiaryBoard'),
			height: 250,
			autoScroll: false,
			layout: "border",
			draggable: draggable,
			items: [this.calendar, this.grid],
			tools: tools
		});
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	},
	
	//行点击，取消加粗
	rowClick : function(grid, record){
		var l = grid.getEl().query("span[gridid="+this.actionFolder+"_"+record.data.id+"]");
		for (var i=0;i<l.length;i++){
			l[i].style.fontWeight = "normal";
		}
		
		try{
			var imgs = grid.getEl().query("img[gridid="+this.actionFolder+"_"+record.data.id+"]");
			imgs[0].src = "./resources/images/icons/sms-readed.gif";
		}catch (e){}
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},
	
	/*重新获取月日历数据*/
	changeMonth : function(dt){
		var _this = this;
		
		_this.calendar.getEl().mask(lang('waiting'));
		_this.calendar.setMarkup([]);
		
		Ext.Ajax.request({  
			url: _this.baseUrl+"&task=loadMonthList",
			method: 'POST',
			params: {yearmonth: dt},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				
				if(result.success === true){
					var rd = result.data;
					_this.calendar.setMarkup(rd);
					
					//初始化，获取当天信息
					if(!_this.pageInit){
						_this.pageInit = true;
						_this.calendar.selectToday();
					}
					
				}
				_this.calendar.getEl().unmask();

				_this.checkPermit(result.noPermit);
			},
			failure: function(){
				CNOA.msg.alert(result.msg);
			}
		});
	},
	
	showDetailPanel : function(date){
		if(date != undefined){
			this.storeDiary.load({params:{start:0, task:"listMydiary", date:date}});
		}
	}
}
