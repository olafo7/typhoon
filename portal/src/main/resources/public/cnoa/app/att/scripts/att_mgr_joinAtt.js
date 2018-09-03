//定义全局变量：
var CNOA_att_mgr_joinAttClass, CNOA_att_mgr_joinAtt;
CNOA_att_mgr_joinAttClass = CNOA.Class.create();
CNOA_att_mgr_joinAttClass.prototype = {
	init: function(){
		this.baseUrl = "index.php?app=att&func=mgr&action=joinAtt";

		this.joinAttPanel = this.getJoinAttPanel();
		
		this.mainPanel = new Ext.Container({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border', 
			autoScroll: true,
			items: [this.joinAttPanel]
		});
	},

	getJoinAttPanel : function(){
		var me = this;
		var fields = [
			{name: 'uid'},
			{name: 'truename'},
			{name: 'deptName'},
			{name: 'isJoinAtt'},
			{name: 'machineId'},
			{name: 'importKey'},
			{name: 'ykkq'},
			{name: 'pc'},
			{name: 'phone'},
			{name: 'address'}

		];

		var getChecked = function(value){
			switch (value) {
				case 'notReg':
					return lang('mianQianPerson');
					break;
				case '1':
					return "<input type='checkbox' checked=true>";
					break;
				case '0':
					return "<input type='checkbox'>";
					break;
				case '':
					return "";
					break;
			}
		};

		var getImportKey = function(value){
			if (value == 0) {
				value = lang('notBind');
			}

			return value;
		};

		var baseSearchParams = {
			truename : ''
		};

		var truenameText = new Ext.form.TextField();
			
		var colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('truename'), dataIndex: 'truename', width: 130, sortable: false, menuDisabled :true},
			{header: lang('belongDept'), dataIndex: 'deptName', width: 130, sortable: true, menuDisabled: true},
			{header: lang('whetherForAtt'), dataIndex: 'isJoinAtt', width: 120, sortable: false, menuDisabled: true, renderer: getChecked},
			{header: lang('attMachineId'), dataIndex: 'machineId', width: 80, sortable: false, menuDisabled: true, editor: new Ext.form.TextField(), renderer: getImportKey},
			{header: lang('attIdentifier'), dataIndex: 'importKey', width: 80, sortable: false, menuDisabled: true, editor: new Ext.form.TextField(), renderer: getImportKey},
			{header: "<input type='checkbox' id='ID_ISYKKQ'>是否考勤机打卡", dataIndex: 'ykkq', width: 100, sortable: false, menuDisabled: true, renderer: getChecked},
			{header: "<input type='checkbox' id='ID_ISPC'>是否电脑打卡", dataIndex: 'pc', width: 100, sortable: false, menuDisabled: true, renderer: getChecked},
			{header: "<input type='checkbox' id='ID_ISPHONE'>是否手机打卡", dataIndex: 'phone', width: 100, sortable: false, menuDisabled: true, renderer: getChecked},
			{header: '允许考勤签到地址', dataIndex: 'address', width: 220, sortable: false, menuDisabled: true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		

		this.store = new Ext.data.Store({
			autoLoad: true,
			baseParams: baseSearchParams,
			proxy: new Ext.data.HttpProxy({url: this.baseUrl + '&task=getJoinAttUser', disableCaching: true}),   
			reader: new Ext.data.JsonReader({totalProperty: 'total', root: 'data', fields: fields}),
			listeners: {
				load: function(th){
					me.isCheck();
				}
			}
		});
		
		var pagingBar = new Ext.PagingToolbar({
			plugins: [new Ext.grid.plugins.ComboPageSize()],
			displayInfo: true,   
			store: me.store,
			pageSize: 15
		});

		var grid = new Ext.grid.EditorGridPanel({
			region: 'center',
			store: me.store,
			cm: colModel,
			tbar: new Ext.Toolbar({
				items: [
					{
						text:lang('refresh'),
						iconCls:'icon-system-refresh',
						handler:function(){
							me.store.reload();
						}
					},"-",
					{
						xtype: 'importButton',
						browseTitle: lang('importAttMan'),
						url: me.baseUrl + '&task=import_preson'
					},'-',{
		                //导出按钮
		                text: lang('export2'),
		                iconCls: 'icon-excel',
		                handler: function(){
		                    Ext.Ajax.request({
		                        url: me.baseUrl + '&task=exportExcel&step=1',
		                        method: 'POST',
		                        success: function(response, action){
		                        	var result = Ext.decode(response.responseText);
		                        	ajaxDownload(me.baseUrl + "&task=exportExcel&file=" + result.msg+"&step=2");
		                        }
		                    });
		                }
		            },'-',

					lang('truename') + ':',
					truenameText,
					
					{
						text: lang('search'),
						cls: 'x-btn-over',
						listeners: {
							'mouseout': function(btn){
								btn.addClass('x-btn-over');
							}
						},
						handler: function(){
							var truename = truenameText.getValue();
							baseSearchParams.truename = truename;

							me.store.reload();
						}
					},{
						text: lang('clear'),
						handler: function(){
							baseSearchParams.truename = '';
							truenameText.setValue('');
							me.store.reload();
						}
					}, '-',
				"<span class='cnoa_color_gray'>" + '请先导出数据再导入修改后的数据' + "</span>"
				]
			}),
			bbar: pagingBar,
			listeners: {
				afteredit: function(th){
					var field = th.field,
						value = th.value,
						uid = th.record.data.uid;

						if (value.match(/^[0-9]\d*$/) == null && value != '') {
							if ( field == 'importKey' ) {
								CNOA.msg.alert(lang('attIdentOnlyInt'));
							} else if ( field == 'machineId' ) {
								CNOA.msg.alert('考勤机ID只能为数字，请重新输入!');
							}
							return;
						}
						
						me.updateJoinAtt(uid, field, value);
				},

				cellclick: function(grid, rowIndex, columnIndex){
					var field = grid.getColumnModel().getDataIndex(columnIndex);
					if(field == 'isJoinAtt' || field == 'ykkq' || field == 'pc' || field == 'phone' ){
						var record = grid.getStore().getAt(rowIndex),
							uid = record.get('uid'),
							oldValue  = record.get('isJoinAtt'),
							ykkqValue = record.get('ykkq'),
							pcValue   = record.get('pc'),
							phoneValue = record.get('phone');


						value = $(grid.getView().getCell(rowIndex, columnIndex)).find('input').attr('checked');

						if (field == 'isJoinAtt' && (value == undefined || value == oldValue)) return;
						if (field == 'ykkq' && (value == undefined || value == ykkqValue)) return;
						if (field == 'pc' && (value == undefined || value == pcValue)) return;
						if (field == 'phone' && (value == undefined || value == phoneValue)) return;
						value = value ? 1 : 0;
						me.updateJoinAtt(uid, field, value);
					}
				}
			}
		});
		
		return grid;
	},

	updateJoinAtt : function(uid, field, value) {
		var me = this;

		Ext.Ajax.request({
			url: me.baseUrl + '&task=editJoinAtt',
			params: {uid: uid, field: field, value: value},
			success: function(response){
				var responseText = Ext.decode(response.responseText);
				if (responseText.success) {
					CNOA.msg.notice2(responseText.msg);
				} else {
					CNOA.msg.alert(responseText.msg);
				}
				
				me.store.reload();
			}
		})
	},
	isCheck: function(){
		var me = this;
		Ext.Ajax.request({
			url: me.baseUrl + "&task=isFull",
			success: function(rp){
				var result = Ext.decode(rp.responseText);
				var ykkq = result.ykkq,
					pc   = result.pc,
					phone= result.phone;
				if (phone) $('#ID_ISPHONE').attr('checked', true);
				if (ykkq) $('#ID_ISYKKQ').attr('checked', true);
				if (pc) $('#ID_ISPC').attr('checked', true);
			}
		})
		//是否全选
		$('#ID_ISPHONE').click(function(){
			var check = $(this).attr('checked');
			if (check) {
				//全选
				toAjax('phone', 1);
			}else{
				toAjax('phone', 0);
			}
			
		});
		$('#ID_ISYKKQ').click(function(){
			var check = $(this).attr('checked');
			if (check) {
				//全选
				toAjax('ykkq', 1);
			}else{
				toAjax('ykkq', 0);
			}
			
		});
		$('#ID_ISPC').click(function(){
			var check = $(this).attr('checked');
			if (check) {
				//全选
				toAjax('pc', 1);
			}else{
				toAjax('pc', 0);
			}
			
		});
		function toAjax(type, isAll){
			Ext.Ajax.request({
				url: me.baseUrl + "&task=allSelect&type="+ type,
				params: {isAll: isAll},
				success: function(rp){
					var rp = Ext.decode(rp.responseText);
					if (rp.success == true) {
						me.store.reload();
						CNOA.msg.notice2(rp.msg);
					}
				}
			})
		}
	}
};