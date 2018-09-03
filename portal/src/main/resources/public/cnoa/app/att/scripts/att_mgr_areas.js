//定义全局变量：
var CNOA_att_mgr_areasClass, CNOA_att_mgr_areas;
CNOA_att_mgr_areasClass = CNOA.Class.create();
CNOA_att_mgr_areasClass.prototype = {
	init: function(){
		var _this = this;
		this.Twidth =  $(window).width();
		this.Theight =  $(window).height();
		this.baseUrl = "index.php?app=att&func=mgr&action=areas";
		this.center  = this.centerPanel(); 
		this.mainPanel = new Ext.Panel({
			layout: 'border',
			border: false,
			items: [this.center]
		})
	},
	centerPanel: function (){
		var _this = this;
		var fields = [
			{name: 'id'},
			{name: 'address'},
			{name: 'longitude'},
			{name: 'latitude'},
			{name: 'radius'}
		]
		this.store = new Ext.data.Store({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({url: _this.baseUrl + '&task=getList'}),
            reader: new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})
		})
		var sm = new Ext.grid.CheckboxSelectionModel({singleSelect: true});
        
        var makeOperate = function(value, cellmeta, record){
        	var id = record.json.id;
        	return "<a href='javascript:void(0);' onclick='CNOA_att_mgr_areas.applyUsers("+id+");'>适用人员</a>";
        }
        var cm = new Ext.grid.ColumnModel({
            defaults: {menuDisabled: true},
            columns: [
                new Ext.grid.RowNumberer(),
                sm,
                {header: 'id', dataIndex: 'id', hidden: true},
                {header: '地点', dataIndex: 'address', width: 300},
                {header: '经度', dataIndex: 'longitude', width: 120},
                {header: '纬度', dataIndex: 'latitude', width: 120},
                {header: '半径(米)', dataIndex: 'radius'},
                {header: lang('opt'), dataIndex: '', renderer: makeOperate}
              
            ]
        });
		var grid  = new Ext.grid.GridPanel({
			border: false,
			region: 'center',
			store: this.store,
			sm: sm,
			cm: cm,
			tbar:  [
				{
					text: lang('refresh'),
					iconCls: 'icon-system-refresh',
					handler: function(){
						_this.store.reload();
					}
				},
				{
					text: lang('add'),
					iconCls: 'icon-utils-s-add',
					handler: function(){
						_this.win('');
					}
				},
				{
					text: lang('modify'),
					iconCls: 'icon-utils-s-edit',
					handler: function(btn){
						var data = grid.getSelectionModel().getSelections();
                        if(data.length == 0){
                            CNOA.miniMsg.alertShowAt(btn, lang('mustSelectOneRow'));
                            return false;
                        }else if(data.length != 1){
                            CNOA.miniMsg.alertShowAt(btn, lang('selectItemOnly'));
                        }
                        var id = data[0].json.id;
						_this.win(id);
					}
				},
				{
					text: lang('del'),
					iconCls: 'icon-utils-s-delete',
					handler: function(btn){
						var data = grid.getSelectionModel().getSelections();
                        if(data.length == 0){
                            CNOA.miniMsg.alertShowAt(btn, lang('mustSelectOneRow'));
                            return false;
                        }else if(data.length != 1){
                            CNOA.miniMsg.alertShowAt(btn, lang('selectItemOnly'));
                        }
                        var id = data[0].json.id;
						_this.deleteAddr(id);
					}
				}
			]
			
		}) 
		return grid;
	},
	win: function(id){
		var _this = this;
		var title = '添加';
		_this.formPanel  = new Ext.form.FormPanel({
			border: false,
			padding: 10,
			labelAlign: 'right',
			items: [
				{
					xtype: 'textfield',
					name: 'address',
					allowBlank: false,
					fieldLabel: '名称'
				},
				{
					xtype: 'textfield',
					name: 'longitude',
					allowBlank: false,
					fieldLabel: '经度',
					listeners: {
						'focus': function(){
							_this.mapWin();
						}
					}
				},
				{
					xtype: 'textfield',
					name: 'latitude',
					allowBlank: false,
					readOnly: true,
					fieldLabel: '纬度'
				},
				{
					xtype: 'numberfield',
					name: 'radius',
					allowBlank: false,
					allowDecimals:false,  
					fieldLabel: '半径(米)'
				},
				{
					xtype: 'displayfield',
					style: 'color: red',
					value: '半径:以选取点为中心,半径范围内打卡有效'
				}
			]
		})
		var submitForm = function(){
			if(_this.formPanel.getForm().isValid()){
				_this.formPanel.getForm().submit({
					url: _this.baseUrl + "&task=submitForm",
					method: 'POST',
					params: {id: id},
					success: function(form, action){
						_this.store.reload();
						win.close();
						CNOA.msg.notice2(action.result.msg);
					},
					failure: function(form, action){
						CNOA.msg.alert(action.result.msg);
					}
				})
			}
		}
		if (id) title = '修改';
		var win   = new Ext.Window({
			border: false,
			title: title,
			width: 400,
			height: 230,
			layout: 'fit',
			modal: true,
			items: [_this.formPanel],
			buttons: [
				{
					text: lang('ok'),
					handler: function(){
						submitForm()
					}
				},
				{
					text: lang('cancel'),
					handler: function(){
						win.close();
					}
				}
			]
		})

		win.show();
		if (id) {
			//加载数据
			_this.formPanel.getForm().load({
				url: _this.baseUrl + '&task=loadFormData',
				params: {id: id},
				success: function(form, action){

				}
			})
		}
	},
	mapWin: function(){
		var _this = this;
		var ID_WHERE   = Ext.id();
		var ID_LON  = Ext.id();
		var ID_LAT  = Ext.id();
		
		var form = new Ext.form.FormPanel({
			border: false,
			autoScroll: true,
			layout: 'border',
			items: [
				{
					xtype: 'panel',
					region: 'north',
					height: 98,
					layout: 'form',
					labelWidth: 60,
					padding: 10,
					border: false,
					items: [
						{
							xtype: 'panel',
							border: false,
							layout: 'column',
							columns: 3,
							items: [
								{
									xtype: 'displayfield',
									width: 65,
									value: '地点:'
								},
								{
									xtype: 'textfield',
									id: ID_WHERE,
									width: 185,
									listeners: {
										'specialkey': function(){
											var whereValue = Ext.getCmp(ID_WHERE).getValue();
											_this.sear(whereValue);
										}
									}
								},
								{
									xtype: 'button',
									width: 60,
									text: '搜索',
									handler: function(){
										var whereValue = Ext.getCmp(ID_WHERE).getValue();
										_this.sear(whereValue);
									}
								}
							]
						},
						{
							xtype: 'textfield',
							width: 180,
							id: ID_LON,
							readOnly: true,
							fieldLabel: '经度'
						},
						{
							xtype: 'textfield',
							width: 180,
							id: ID_LAT,
							readOnly: true,
							fieldLabel: '纬度'
						}
					]
				},
				{
					xtype: 'panel',
					region: 'center',
					border: false,
					html: "<div style='width:100%;height:100%;border-top: 1px solid gray' id='container'></div>",
					listeners: {
						afterrender: function(){
							Ext.Ajax.request({
								url: _this.baseUrl + "&task=getIP",
								success: function(rp){
									var rp = Ext.decode(rp.responseText);
									var x = '116.415854';
									var y = '39.920753';
									if (rp.x && rp.y) {
										x = rp.x;
										y = rp.y;
									}
									_this.map = new BMap.Map("container");
									_this.map.setDefaultCursor("crosshair");//设置地图默认的鼠标指针样式
									_this.map.enableScrollWheelZoom();//启用滚轮放大缩小，默认禁用。
									//创建点坐标

									var point = new BMap.Point(x, y);
									//初始化地图，设置中心点坐标和地图级别
									_this.map.centerAndZoom(point, 13);
									//panTo()方法 等待两秒钟后-让地图平滑移动至新中心点
									/**window.setTimeout(function(){ 
									map.panTo(new BMap.Point(120.386266, 30.307407)); }, 2000);**/
									//***********************地址解析类  
									var gc = new BMap.Geocoder();
									//向map中添加--------------------------------------控件
									/**地图API中提供的控件有：
									Control：控件的抽象基类，所有控件均继承此类的方法、属性。通过此类您可实现自定义控件。
									NavigationControl：地图平移缩放控件，默认位于地图左上方，它包含控制地图的平移和缩放的功能。
									OverviewMapControl：缩略地图控件，默认位于地图右下方，是一个可折叠的缩略地图。
									ScaleControl：比例尺控件，默认位于地图左下方，显示地图的比例关系。
									MapTypeControl：地图类型控件，默认位于地图右上方。
									CopyrightControl：版权控件，默认位于地图左下方。
									**/
									//NavigationControl 地图平移缩放控件，默认位于地图左上方 它包含控制地图的平移和缩放的功能。
									_this.map.addControl(new BMap.NavigationControl()); 
									//OverviewMapControl 缩略地图控件，默认位于地图右下方，是一个可折叠的缩略地图
									_this.map.addControl(new BMap.OverviewMapControl());
									//ScaleControl：比例尺控件，默认位于地图左下方，显示地图的比例关系。
									_this.map.addControl(new BMap.ScaleControl());
									//MapTypeControl：地图类型控件，默认位于地图右上方。
									_this.map.addControl(new BMap.MapTypeControl());
									//CopyrightControl：版权控件，默认位于地图左下方
									_this.map.addControl(new BMap.CopyrightControl());

									//----------------------------------------------地图覆盖物
									/**地图API提供了如下几种覆盖物：
									Overlay：覆盖物的抽象基类，所有的覆盖物均继承此类的方法。
									Marker：标注表示地图上的点，可自定义标注的图标。
									Label：表示地图上的文本标注，您可以自定义标注的文本内容。
									Polyline：表示地图上的折线。
									Polygon：表示地图上的多边形。多边形类似于闭合的折线，另外您也可以为其添加填充颜色。
									Circle: 表示地图上的圆。
									InfoWindow：信息窗口也是一种特殊的覆盖物，它可以展示更为丰富的文字和多媒体信息。注意：同一时刻只能有一个信息窗口在地图上打开。
									可以使用map.addOverlay方法向地图添加覆盖物，使用map.removeOverlay方法移除覆盖物，注意此方法不适用于InfoWindow。
									**/
									// 创建标注  
									var marker = new BMap.Marker(point);   
									// 将标注添加到地图中
									_this.map.addOverlay(marker);
									//********************************************监听标注事件
									//点击事件
									marker.addEventListener("click", function(e){  
									 	document.getElementById(ID_LON).value = e.point.lng; 
									 	document.getElementById(ID_LAT).value = e.point.lat; 
									 	
									}); 
									//*******************************************可托拽的标注
									//marker的enableDragging和disableDragging方法可用来开启和关闭标注的拖拽功能。
									marker.enableDragging();
									//监听标注的dragend事件来捕获拖拽后标注的最新位置
									marker.addEventListener("dragend",function(e){
									  gc.getLocation(e.point, function(rs){  
									        	showLocationInfo(e.point, rs);  
									    	});  
									});

									//*****************************信息窗口
									//显示地址信息窗口  
									function showLocationInfo(pt, rs){  
									    var opts = {  
									      width : 250,     //信息窗口宽度  
									      height: 150,     //信息窗口高度  
									      title : "当前位置"  //信息窗口标题  
									    }  
									    
									    var addComp = rs.addressComponents;  
									    var addr = "当前位置：" + addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber + "<br/>";  
									    addr += "纬度: " + pt.lat + ", " + "经度：" + pt.lng;  
									      
									    var infoWindow = new BMap.InfoWindow(addr, opts);  //创建信息窗口对象  
									    marker.openInfoWindow(infoWindow);  
									} 

									_this.map.addEventListener("click", function(e){//地图单击事件
									  	document.getElementById(ID_LON).value = e.point.lng;
									  	document.getElementById(ID_LAT).value = e.point.lat;
									  
									});

									//**************************** 目前百度地图提供的图层包括：
									//TrafficLayer：交通流量图层
									// 创建交通流量图层实例  
									var traffic = new BMap.TrafficLayer();     
									// 将图层添加到地图上  
									_this.map.addTileLayer(traffic);                    
									/**
									百度地图提供了交互功能更为复杂的“工具”，它包括：
									PushpinTool：标注工具。通过此工具用户可在地图任意区域添加标注。
									DistanceTool：测距工具。通过此工具用户可测量地图上任意位置之间的距离。
									DragAndZoomTool：区域缩放工具。此工具将根据用户拖拽绘制的矩形区域大小对地图进行放大或缩小操作。
									**/
									// 创建标注工具实例  
									//var myPushpin = new BMap.PushpinTool(map);
									// 监听事件，提示标注点坐标信息
									//myPushpin.addEventListener("markend",function(e){
									//	alert("你标注的位置:"+e.point.lng+","+e.point.lat);
									//});
									// 开启标注工具  
									//myPushpin.open();


									function iploac(result){//根据IP设置地图中心
									    var cityName = result.name;
									    //map.setCenter(cityName);
									}
									var myCity = new BMap.LocalCity();
									myCity.get(iploac);
									_this.sear = function(result){//地图搜索
									  	var local = new BMap.LocalSearch(_this.map, {
									  		renderOptions:{map: _this.map}
									  	});
									  	local.search(result);
									}

								}
							})
							
							
						}
					}
				}
			]
		})
		var win = new Ext.Window({
			border: false,
			width: _this.Twidth-100,
			height: _this.Theight-100,
			modal: true,
			layout: 'fit',
			items: [form],
			buttons: [
				{
					text: lang('ok'),
					handler: function(){
						var lonValue = Ext.getCmp(ID_LON).getValue();
						var latValue = Ext.getCmp(ID_LAT).getValue();
						if (lonValue && latValue) {
							_this.formPanel.getForm().findField('longitude').setValue(lonValue);
							_this.formPanel.getForm().findField('latitude').setValue(latValue);
							win.close();
						}else{
							CNOA.msg.alert('请选取有效的经纬度');
						}
					}
				},
				{
					text: lang('cancel'),
					handler: function(){
						win.close();
					}
				}
			]
		}) 
		win.show();
	},
	deleteAddr: function(id){
		var _this = this;
		if (!id) return;
		CNOA.msg.cf(lang('confirmToDelete'), function(btn){
            if (btn == 'yes') {
            	Ext.Ajax.request({
					url: _this.baseUrl + "&task=deleteAddr",
					params: {id: id},
					success: function(rp){
						var rp = Ext.decode(rp.responseText);
						if (rp.success == true) {
							_this.store.reload();
							CNOA.msg.notice2(rp.msg);
						}else{
							CNOA.msg.alert(rp.msg);
						}	
					}
				})
            }
        })
		
	},
	applyUsers: function(id){
		var _this = this;
		var ID_applyUids = Ext.id();
		var form = new Ext.form.FormPanel({
			border: false,
			labelAlign: 'right',
			labelWidth: 70,
			padding: 10,
			items: [
				{
                    xtype: "textarea",
                    height: 200,
                    anchor: "-20",
                    readOnly: true,
                    fieldLabel: '适用人员',
                    allowBlank: false,
                    blankText: '请选择适用人员',
                    name: "user",
                    listeners: {
                        'render': function(th){
                            th.to = ID_applyUids;
                        },
                        'focus': function(th){
                            people_selector('user', th, true, false);
                        }
                    }
                },{
                    xtype: "hidden",
                    id: ID_applyUids,
                    name: "uids"
                }
			]
		})
		var submit = function(){
			if(form.getForm().isValid()){
				form.getForm().submit({
					url: _this.baseUrl + "&task=submit",
					method: 'POST',
					params: {addressId: id},
					success: function(form, action){
						win.close();
						CNOA.msg.notice2(action.result.msg);
					},
					failure: function(from, action){
						CNOA.msg.alert(action.result.msg);
					}
				})
			}
		}
		var win = new Ext.Window({
			border: false,
			modal: true,
			width: 600,
			height: 320,
			layout: 'fit',
			items: [form],
			buttons: [
				{
					text: lang('save'),
					handler: function(){
						submit();
					}
					
				},
				{
					text: lang('cancel'),
					handler: function(){
						win.close();
					}
				}
			]
		})
		win.show();
		form.getForm().load({
			url: _this.baseUrl + "&task=loadApplyUsers", 
			method: "POST",
			params: {id: id}
		})
	}

};