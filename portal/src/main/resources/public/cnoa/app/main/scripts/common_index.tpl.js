



//生成用户列表
CNOA.main.common.index.DestTopAppListUser = makeUserDesktopAppList();

function makeUserDesktopAppList(){
	list = Ext.decode(CNOA.main.common.index.DestTopAppList);

	var tmp0 = new Array();
	var tmp1 = new Array();
	var tmp2 = new Array();
	Ext.each(list, function(a, b, c){
		var e = a;
		if (e.inuse == 1){
			eval("tmp"+e.column+".push(e);");
		}
	});
	tmp0 = sortColumn(tmp0);
	tmp1 = sortColumn(tmp1);
	tmp2 = sortColumn(tmp2);
	function sortColumn(arr){
		var a = new Array();
		//外层循环，共要进行arr.length次求最大值操作
		for(var i=0;i<arr.length;i++){
			//内层循环，找到第i大的元素，并将其和第i个元素交换
			for(var j=i;j<arr.length;j++){
				if(arr[i].position>arr[j].position){
					//交换两个元素的位置
					var temp=arr[i];
					arr[i]=arr[j];
					arr[j]=temp;
				}
			}
		}
		return arr;
	}
	return tmp0.concat(tmp1).concat(tmp2);
}

//定义全局变量：
var CNOA_main_common_indexClass, CNOA_main_common_index;

/**
* 主面板-列表
*
*/
CNOA_main_common_indexSettingWinClass = CNOA.Class.create();
CNOA_main_common_indexSettingWinClass.prototype = {
	init: function(btn){
		var _this = this;
		btn.setDisabled(true);
		
		this.allData = Ext.decode(CNOA.main.common.index.DestTopAppList);
		this.useData = CNOA.main.common.index.DestTopAppListUser;
		
		this.dataDX = (function(){
			var tmp = new Array();
			Ext.each(_this.allData, function(v1, i1){
				var use = false;
				Ext.each(_this.useData, function(v2, i2){
					if(v1.code == v2.code){
						use = true;
					}
				});
				if(!use){
					tmp.push(v1);
				}
			});
			return tmp;
		})();
		
		// Generic fields array to use in both store defs.
		this.fields = [
			{name: 'name'},
			{name: 'about'}
		];
		
		// create the data store
		this.firstGridStore = new Ext.data.JsonStore({
			fields: this.fields,
			data: {
				records: this.dataDX
			},
			root: 'records'
		});
		
		// declare the source Grid
		this.firstGrid = new Ext.grid.GridPanel({
			ddGroup: 'secondGridDDGroup',
			store: this.firstGridStore,
			columns: [
				{id: 'name',header: lang('name'),width: 100,sortable: true,dataIndex: 'name', renderer:function(value, cellmeta, record){var conHTML="<div ext:qtip='"+record.data.about+"'>"+value+"</div>";return conHTML;}}
			],
			enableDragDrop: true,
			stripeRows: true,
			autoExpandColumn: 'name',
			title: lang('chooseTools')
		});
		
		// create the destination Grid
		this.secondGrid = new Ext.grid.GridPanel({
			ddGroup: 'firstGridDDGroup',
			store: this.getSecondGridStore(),
			columns: [
				{id: 'name',header: lang('name'),width: 100,sortable: true,dataIndex: 'name', renderer:function(value, cellmeta, record){var conHTML="<div ext:qtip='"+record.data.about+"'>"+value+"</div>";return conHTML;}}
			],
			enableDragDrop: true,
			stripeRows: true,
			autoExpandColumn: 'name',
			title: lang('selectedTools')
		});

		this.searchName = new Ext.form.TextField({
			width: 120, 
			enableKeyEvents : true,
			listeners: {
				keyup : function(th, e){
                     searchName = th.getValue();
					_this.firstGridStore.filter("name",searchName);
                },
                change: function(th, e){
                	 searchName = th.getValue();
					_this.firstGridStore.filter("name",searchName);
                }
			}
		});

		this.mainPanel = new Ext.Window({
			title: lang('setDesktopMod'),
			width: 700,
			height: 400,
			layout: 'hbox',
			resizable: false,
			defaults: {
				flex: 1,
				border: false
			},
			layoutConfig: {
				align: 'stretch'
			},
			tbar: [
				'快速查询: ',this.searchName,
				' ',
				lang('dragDesktopModNotice')
			],
			buttons: [
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler: function(){
						_this.save(true);
					}
				},
				{
					text: lang('close'),
					id: this.ID_btn_close,
					handler: function(){
						_this.mainPanel.close();
					}
				}
			],
			items: [this.firstGrid, this.secondGrid],
			listeners: {
				close: function(){
					btn.setDisabled(false);
				},
				afterrender : function(){
					var firstGridDropTargetEl = _this.firstGrid.getView().scroller.dom;
					var firstGridDropTarget = new Ext.dd.DropTarget(firstGridDropTargetEl, {
						ddGroup: 'firstGridDDGroup',
						notifyDrop: function(ddSource, e, data) {
							var records = ddSource.dragData.selections;
							Ext.each(records, ddSource.grid.store.remove, ddSource.grid.store);
							_this.firstGrid.store.add(records);
							_this.firstGrid.store.sort('name', 'ASC');
							return true
						}
					});
				
					var secondGridDropTargetEl = _this.secondGrid.getView().scroller.dom;
					var secondGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {
						ddGroup: 'secondGridDDGroup',
						notifyDrop: function(ddSource, e, data) {
							var records = ddSource.dragData.selections;
							Ext.each(records, ddSource.grid.store.remove, ddSource.grid.store);
							_this.secondGrid.store.add(records);
							_this.secondGrid.store.sort('name', 'ASC');
							return true
						}
					});
				}
			}
		}).show();
	},
	
	getSecondGridStore : function(){
		this.secondGridStore = new Ext.data.JsonStore({
			fields: this.fields,
			root: 'records',
			data: {
				records: this.useData
			}
		});
		return this.secondGridStore;
	},
	
	save : function(save){
		var _this = this;
		
		this.useData = new Array();
		Ext.each(this.secondGrid.store.data.items, function(v, i){
			_this.useData.push(Ext.apply(v.json, {
				inuse: 1 
			}));
		});
		
		try{Ext.getCmp(CNOA_main_common_index.ID_column0).removeAll();}catch(e){}
		try{Ext.getCmp(CNOA_main_common_index.ID_column1).removeAll();}catch(e){}
		try{Ext.getCmp(CNOA_main_common_index.ID_column2).removeAll();}catch(e){}
		
		for (var j=0;j<this.useData.length;j++){
			var app = this.useData[j];
			try{
				if((LAYOUT_TYPE == 2 && app.column == 2) || (LAYOUT_TYPE == 3 && app.column == 3)){
					app.column = 1;
				}
				//cdump(app.code+"_OBJ = new "+app.code+"_CLASS();");
				eval(app.code+"_OBJ = new "+app.code+"_CLASS();");
				eval("Ext.getCmp(CNOA_main_common_index.ID_column"+app.column+").add("+app.code+"_OBJ.mainPanel);");
			}catch(e){}
		}
		
		try{Ext.getCmp(CNOA_main_common_index.ID_column0).doLayout();}catch(e){}
		try{Ext.getCmp(CNOA_main_common_index.ID_column1).doLayout();}catch(e){}
		try{Ext.getCmp(CNOA_main_common_index.ID_column2).doLayout();}catch(e){}
		
		if(save){
			this.saveDesktop();
		}
	},
	
	saveDesktop : function(){
		var _this = this;
		
		var data = new Array();
		Ext.each(this.useData, function(v, i){
			data.push(v.code);
		});
		Ext.Ajax.request({  
			url: CNOA_main_common_index.baseUrl,
			method: 'POST',
			params: {task: "saveDesktop", data: data.join(",")},
			success: function(r) {
				CNOA.msg.notice2(lang('successopt'));
				CNOA.main.common.index.DestTopAppListUser = _this.useData;
				_this.mainPanel.close();
			}
		});
	}
}
CNOA_main_common_indexClass = CNOA.Class.create();
CNOA_main_common_indexClass.prototype = {
	init : function(){
		var _this = this;

		this.baseUrl = "index.php?action=index";

		this.layoutType = LAYOUT_TYPE;

		this.ID_column0   = Ext.id();
		this.ID_column1   = Ext.id();
		this.ID_column2   = Ext.id();

		var items = [];

		if(this.layoutType == 2){
			items = [
				{
					columnWidth:.5,
					id: _this.ID_column0,
					style:'padding:5px 0 0 5px',
					items:[]
				},
				{
					columnWidth:.5,
					id: _this.ID_column1,
					style:'padding:5px 5px 0 5px',
					items:[]
				}
			]
		}
		if(this.layoutType == 3){
			items = [
				{
					columnWidth:.33,
					id: _this.ID_column0,
					style:'padding:5px 0 0 5px',
					items:[]
				},
				{
					columnWidth:.33,
					id: _this.ID_column1,
					style:'padding:5px 5px 0 5px',
					items:[]
				},
				{
					columnWidth:.33,
					id: _this.ID_column2,
					style:'padding:5px 0 0 0',
					items:[]
				}
			]
		}

		var appleHtml = '<div class="apple">'
			appleHtml +='<ul class="appleUl">';
			appleHtml +='</ul>';
			appleHtml +='</div>';

		var remindHtml  = '<div id="remind">'
			remindHtml += '<div class="remindNews">'
			remindHtml += '<div class="reminds">'
			remindHtml += '<span class="tubiao"></span>'
			remindHtml += '<a class="rem1" href="javascript:void(0);"><span></span><label></label></a>'
			remindHtml += '</div>'
			remindHtml += '<div class="reminds">'
			remindHtml += '<span class="tubiao"></span>'
			remindHtml += '<a class="rem2" href="javascript:void(0);"><span></span><label></label></a>'
			remindHtml += '</div>'
			remindHtml += '</div>'
			remindHtml += '<a class="addRemind" href="javascript:void(0)"><img src="../resources/images/addRemind.png"/></a>'
			remindHtml += '</div>';
		this.topPanel = new Ext.Panel({
			//region: "north",
			height: 390,
			border: false,
			layout: 'column',
			hidden: CNOA_INDEX_LAYOUT == 1 ? false : true,
			columns: 3,
			bodyStyle: 'margin: 10px 0px 0px 30px;font-family: Microsoft YaHei;',
			items:[
				{
					columnWidth: .15,
					border: false,
					bodyStyle: 'text-align: center; margin-top: 10px;',
					hideBorders: true,
					items: [ 
						{
							height: 140,
							style: 'margin: 0 auto; width: 100%;position: relative;',
							id: 'CNOA_MY_BIGIMAGES',
							html: '<div id="CNOA_MY_IMAGES"><img src="" style="cursor: pointer;" id="CNOA_MY_PORTRAIT"/></div>'
						},{
							html: '<div id="CNOA_MY_NAME"><span></span></div>'
						},{
							html: '<div id="CNOA_MY_DEPT"></div>'
						},{
							html: '<div id="CNOA_MY_SIGN"><input id="cnoa_mainpanel_diary_input" type="text" readonly="readonly" /><a class="write" href="javascript:void(0)"></a></div>'
						}
					],
					listeners: {
						afterrender: function(){
							Ext.Ajax.request({  
								url: "index.php?app=my&func=info&action=index&task=editLoadFormDataInfo",
								method: 'POST',
								success: function(result) {
									var r = Ext.decode(result.responseText);
									$('#CNOA_MY_NAME').text(r.data.truename);
									if(r.data.face){
										$('#CNOA_MY_PORTRAIT').attr('src',r.data.face);
									} else {
										$('#CNOA_MY_PORTRAIT').attr('src','./resources/images/default.jpg');
									}
									if (r.data.partTimeJob) {
										$('#CNOA_MY_DEPT').text(r.data.deptname+'-'+r.data.jobname+'-'+r.data.partTimeJob);
										$("#CNOA_MY_DEPT").attr("title", r.data.deptname+'-'+r.data.jobname+'-'+r.data.partTimeJob);
									} else {
										$('#CNOA_MY_DEPT').text(r.data.deptname+'-'+r.data.jobname);
										$("#CNOA_MY_DEPT").attr("title", r.data.deptname+'-'+r.data.jobname);
									}
									
									if (r.data.personSign) {
										$('#cnoa_mainpanel_diary_input').val(r.data.personSign);
										$("#cnoa_mainpanel_diary_input").attr("title",r.data.personSign);
									} else {
										$('#cnoa_mainpanel_diary_input').val('留下你今天的心情吧！');
										$("#cnoa_mainpanel_diary_input").attr("title","留下你今天的心情吧！");
									}
								}
							});
							
							var personSignOld = "";
							setTimeout(function(){
								$('#CNOA_MY_PORTRAIT').click(function(){
									mainPanel.loadClass("index.php?app=my&func=info&action=index&task=loadPage&from=info", "CNOA_MENU_MY_INFO", '我的资料', "icon-cnoa-my-info");
								});
								$('#cnoa_mainpanel_diary_input').click(function(){
									$(this).removeAttr("readonly");
									$(this).addClass("input");
									personSignOld = $(this).val();
								});
								$('#cnoa_mainpanel_diary_input').blur(function(){
									$(this).attr("readonly","readonly");
									$(this).removeClass("input");
									var personSign = $(this).val();
									if(personSignOld == personSign){
										return;
									}
									Ext.Ajax.request({  
										url: "index.php?app=my&func=info&action=index&task=editPersonSign",
										method: 'POST',
										params: {personSign: personSign},
										success: function(r) {
											CNOA.msg.notice2('更新签名成功');
										},
										failure: function(r){
											CNOA.msg.alert('没有权限修改签名');
										}
									});
								});
							}, 500);
						},
						resize : function(th){
							var winWidth 	= $(window).width(),
								winHeight 	= $(window).height(),
								idwidth  	= winWidth*133/1920,
								inputWidth  = winWidth*169/1920,
								imgleft     = winWidth*63/1920,
								aleft       = winWidth*180/1920,
								nameFont    = winWidth*30/1920;
								if (winWidth < 1360) {
									$('#CNOA_MY_SIGN input').css('width','50px');
								} else {
									$('#CNOA_MY_SIGN input').css('width',inputWidth+'px');
								}
								if (winWidth == 1024) {
									$('#CNOA_MY_IMAGES').css('width','118px');
									$('#CNOA_MY_IMAGES').css('height','113px');
									$('#CNOA_MY_PORTRAIT').css('height','110px');
									$('#CNOA_MY_PORTRAIT').css('width','110px');
								};
							$('#CNOA_MY_NAME span').css('left',aleft+'px');
							$('#CNOA_MY_NAME').css('font-size',nameFont+'px');
						}
					}
				},{
					columnWidth: .53,
					border: false,
					height: 390,
					style: 'margin-left: 2%; margin-top: 10px;',
					hideBorders: true,
					items: [
						{
							layout: 'column',
							columns: 4,
							height: 110,
							defaults: {
				                bodyStyle: 'background-color: #fff;'
							},
							items: [
								{
									bodyStyle: 'background-color: #6075A3;',
					                columnWidth: .24,
					                height: 110,
									html: '<div id="levelOne" class="level"></div>',
									listeners: {
										resize: function(th){
											_this.echarts('levelOne','#A2A9C6','紧急流程',2);
											th.doLayout();
										}
									}
								},{
									bodyStyle: 'background-color: #61B860;',
					                columnWidth: .24,
					                style: 'margin-left: 1%',
					                height: 110,
									html: '<div id="levelTwo" class="level"></div>',
									listeners: {
										resize: function(th){
											_this.echarts('levelTwo','#ACD59F','重要流程',1);
											th.doLayout();
										}
									}
								},{
									bodyStyle: 'background-color: #f7a23c;',
					                columnWidth: .24,
					                style: 'margin-left: 1%',
					                height: 110,
									html: '<div id="levelThree" class="level"></div>',
									listeners: {
										resize: function(th){
											_this.echarts('levelThree','#F7D098','普通流程',3);
											th.doLayout();
										},
										afterrender: function(){
											_this.goToList();
										}
									}
								},{
									bodyStyle: 'background-color: #47A4CC;',
					                columnWidth: .24,
					                style: 'margin-left: 1%',
					                height: 110,
									html: '<div id="levelFour" class="level"></div>',
									listeners: {
										resize: function(th){
											Ext.Ajax.request({  
												url: "index.php?app=notice&func=notice&action=todo&task=refreshTodoTotal",
												method: 'POST',
												success: function(r) {
													var result  = Ext.decode(r.responseText);
													var dIdWidth = $('#levelFour').width();
													if (result.noPermit == true) {
														_this.toDoList('levelFour','#AFD3E6','levelFour',0,'其他待办',100,0,dIdWidth);
														$('#levelFour').click(function(){
															CNOA.msg.alert('对不起，你没有被授权使用该功能，请确认您是否有权限，或联系管理员为您开通！');
														});
													} else {
														if ((result.total == 0) || (result.total == 2 && result.data[31])) {
															_this.toDoList('levelFour','#AFD3E6','levelFour',0,'其他待办',100,0,dIdWidth);
															$('#levelFour').click(function(){
																mainPanel.closeTab("CNOA_MENU_SYSTEM_NEED_TODO");
																mainPanel.loadClass("index.php?app=notice&func=notice&action=todo", "CNOA_MENU_SYSTEM_NEED_TODO", '待办事务', "workToDo");
															});
														} else {
															var wf =  result.data[31];
															var total =  result.data.counts;
															if (result.data[31]) {
																var counts = total-wf;
															} else {
																counts = result.data.counts;
															}
															var num = ((counts/total)*100).toFixed(1);
															var other = 100-num;
															_this.toDoList('levelFour','#AFD3E6','levelFour',counts,'其他待办',num,other,dIdWidth);
															$('#levelFour').click(function(){
																mainPanel.closeTab("CNOA_MENU_SYSTEM_NEED_TODO");
																mainPanel.loadClass("index.php?app=notice&func=notice&action=todo", "CNOA_MENU_SYSTEM_NEED_TODO", '待办事务', "workToDo");
															});
														}
													}
													th.doLayout();
												}
											});
										}
									}
								}
							]
						},{
							height: 120,
							style: 'margin-top: 40px',
							html: '<div id="text2"><textarea id="text"></textarea><span></span></div>'
						},{
							layout: 'column',
							columns: 5,
							height: 30,
							style: 'margin-top: 20px',
							hideBorders: true,
							border: false,
							items: [
								{	
									columnWidth: .90,
									height: 30,
									style: 'color: #6075A3; font-size: 14px; margin-left: 15px; line-height: 30px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;',
									html: appleHtml,
									listeners: {
										afterrender: function(){
											function autoScroll(obj){  
												$(obj).find("ul").animate({  
													marginTop : "-30px"
												},500,function(){  
													$(this).css({marginTop : "0px"}).find("li:first").appendTo(this);  
												})  
											};
											setTimeout(function(){
												Ext.Ajax.request({  
													url: "index.php?app=news&func=notice&action=index&task=desktoplist",
													method: 'post',
													success: function(r) {
														var result = Ext.decode(r.responseText);
														if (result.noPermit == true) {
															$(".appleUl").append("<li><a title='无数据' href='javascript:void(0)'>无数据</a></li>");														} else{
															if(result.data != ''){
																var inter = setInterval(function(){
																	autoScroll(".apple");
																},2000);
																$.each(result.data,function(i){
																	if (i < 10) {
																		$(".appleUl").append("<li><a class=gonggao"+i+" title="+result.data[i].title+" href='javascript:void(0)'>"+result.data[i].title+"</a></li>");
																		$('.appleUl li').children('a.gonggao'+i).click(function(){_this.viewNotice(result.data[i].id);});
																	};
																});
																if (result.data.length == 1) {
																	clearInterval(inter);
																};
																$('.appleUl li a').mouseover(function(){
																	clearInterval(inter);
																});
																if (result.data.length > 1) {
																	$('.appleUl li a').mouseout(function(){
																		inter = setInterval(function(){
																			autoScroll(".apple");
																		},2000);
																	});
																};
															} else {
																$(".appleUl").append("<li><a title='无数据' href='javascript:void(0)'>无数据</a></li>");
															}
														}
													},
													failure: function(r){
														$(".appleUl").append("<li><a title='无数据' href='javascript:void(0)'>无数据</a></li>");
													}
												});
											},200)
										}
									}
								},{
									style: 'margin-left: 3%',
									html: '<a class="lhc_images" href="javascript:void(0)"></a>'
								},{
									style: 'margin-left: 2%',
									html: '<div style="font-size: 13px;  width: 100%; margin-top:3px; color: #677B84; line-height: 30px;">我的日记</div>'
								},
								// {
								// 	style: 'margin-left: 1%;margin-top: 17px;',
								// 	html: '<span style="margin-top: 5%; margin-left: 13%;" href="javascript:void(0)"><img src="../resources/images/dropDown.png"/></span>'
								// },
								{
									style: 'margin-left: 2%',
									html: '<div class="lhc_submit">提交</div>'
								}
							],
							listeners: {
								afterrender: function(){
									_this.toDoNotice();
								}
							}
						}
					]
				},{
					columnWidth: .28,
					height: 390,
					hideBorders: true,
					style: 'margin-left: 1%',
					border: false,
					items: [
						{
							html: "<div class='datepicker'></div>",
							listeners: {
								render: function(){
									var winWidth = $(window).width();
									setTimeout(function(){
										$(".datepicker").datePicker({
											inline:true,
											selectMultiple:false
										});
									},100);
									setInterval(function(){
										if(winWidth >= 1280) {
											showtime();
										} else {
											$(".time .nowtime").css('display','none');
										}
									},1000);
									function showtime(){
										var now = new top.Date();
										var today = new Array(lang('sunday'), lang('monday'), lang('tuesday'), lang('wednesday'), lang('thursday'), lang('friday'), lang('saturday'));
										now.setTime(top.differentMillisec + now.getTime());
										var nowTime = now.getTime();
										var day = new Date(nowTime);
										var week = today[day.getDay()];
										var str = now.format('H:i:s');
										$(".time .nowtime").html(str+' ['+week+']');
									}

									
								}
							}
						},{
							height: 90,
							html: remindHtml,
							listeners: {
								render: function(th){
									setTimeout(function() {
										_this.getMonthDate('','');
										var winWidth = $(window).width();
										if(winWidth < 1280){
											$(".time .nowtime").css('display','none');
											$('div.dp-nav-prev').css('left','20%');
											$('.time .ym').css('margin-left','0');
											$('div.dp-nav-next').css('right','20%');
										} else {
											$(".time .nowtime").css('display','block');
											$('div.dp-nav-prev').css('left','45%');
											$('.time .ym').css('margin-left','25%');
											$('div.dp-nav-next').css('right','20%');
										}

										$('#dp-nav-prev-month').click(function(){
									        var str = $('.time .ym').html();
									        	arr = str.split(" ");
									        	datestr = arr[0]+'-'+arr[1];
									        	_this.getMonthDate(datestr,arr[1]);
									    });

									    $('#dp-nav-next-month').click(function(){
									        var str = $('.time .ym').html();
									        	arr = str.split(" ");
									        	datestr = arr[0]+'-'+arr[1];
									        	_this.getMonthDate(datestr,arr[1]);
									    });

									    $('.lhc_images').click(function(){
											_this.addAttachWindow('add');
										})

									}, 100);
									th.doLayout();
								}
							}
						}
					],
					listeners: {
						resize : function(th){
							var winWidth = $(window).width();
							if(winWidth < 1280){
								$(".time .nowtime").css('display','none');
								$('div.dp-nav-prev').css('left','20%');
								$('.time .ym').css('margin-left','0');
								$('div.dp-nav-next').css('right','20%');
							} else {
								$(".time .nowtime").css('display','block');
								$('div.dp-nav-prev').css('left','45%');
								$('.time .ym').css('margin-left','25%');
								$('div.dp-nav-next').css('right','20%');
							}

						}
					}
				}
			]
		});
		this.portalPanel = {
			bodyStyle : " border-top: 10px solid #EBEDF1; padding-top: 10px;",
			xtype:'portal',
			//region:'center',
			border: false,
			listeners: {
				'drop' : function(e){
					//Ext.Msg.alert('Portlet Dropped', e.panel.id + '<br />Column: ' + e.columnIndex + '<br />Position: ' + e.position);
					_this.savePosition(e.panel.id, e.columnIndex, e.position);
				},

				'afterrender' : function(){
					var a = CNOA.main.common.index.DestTopAppListUser;
					for (var j=0;j<a.length;j++){
						var app = a[j];
						try{
							if(this.layoutType == 2){
								if(app.column == 2){
									app.column = 1;
								}
							}
							eval(app.code+"_OBJ = new "+app.code+"_CLASS();");
							eval("Ext.getCmp(_this.ID_column"+app.column+").add("+app.code+"_OBJ.mainPanel);");
						}catch(e){}
					}

					setTimeout(function(){
						main_init_load_tab();
					}, 2000);
				},
				resize : function(th){
					th.doLayout();
				}
			},
			items: items
		};

		this.mainPanel = new Ext.Panel({
			border: false,
			autoScroll: true,
			items: [this.topPanel, this.portalPanel]
		});
	},

	echarts: function(id,color,name,level){
		var _this = this;
		function round(v,e){
			var t=1;
			for(;e>0;t*=10,e--);
			for(;e<0;t/=10,e++);
			return Math.round(v*t)/t;
							}
		Ext.Ajax.request({  
			url: "index.php?app=wf&func=flow&action=use&modul=todo&task=getJsonData",
			method: 'POST',
			params: {level: level, lhc: 1},
			success: function(r) {
				var result  = Ext.decode(r.responseText);

					var width = $('#'+id).width();
				if (result.noPermit == true) {
					_this.toDoList(id,color,id,0,name,100,0,width);
				} else {
					var total =  result.data.length;
					var baifenbi = (total/result.total)*100;
					var num = baifenbi.toFixed(1);
					var other = 100-num;
					if (result.total == 0) {
						_this.toDoList(id,color,id,0,name,100,0,width);
					} else {
						_this.toDoList(id,color,id,total,name,num,other,width);
					}
				}
			}
		});
	},

	viewNotice: function(id){
        try{
            mainPanel.closeTab("CNOA_MENU_NEWS_NOTICE_VIEW");
            mainPanel.loadClass("index.php?app=news&func=notice&action=index&task=loadPage&from=view&id="+id, "CNOA_MENU_NEWS_NOTICE_VIEW", '通知/公告', "icon-diary-my");
        }catch (e){}
	},
	
	// showSettingPanel : function(btn){
	// 	new CNOA_main_common_indexSettingWinClass(btn);
	// },

	savePosition : function(id, column, position){
		var data = new Array();
		var items = this.mainPanel.items;
		var ii = this.layoutType;
		for(var i=0;i<ii;i++){
			data[i] = new Array();
			eval("data[i] = Ext.getCmp(this.ID_column"+i+").items.keys;");
		}

		var _this = this;

		Ext.Ajax.request({  
			url: _this.baseUrl,
			method: 'POST',
			params: {task: "savePosition", data: Ext.encode(data)},
			success: function(r) {
				//var result = Ext.decode(r.responseText);

				//alert(r.responseText);
			}
		});
	},
		
	closeDesktopApp : function(code){
		var _this = this;

		Ext.Ajax.request({  
			url: _this.baseUrl,
			method: 'POST',
			params: {task: "closeApp", id: code},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					//CNOA.main.common.index.DestTopAppListUser;
					var tmpArray = new Array();
					Ext.each(CNOA.main.common.index.DestTopAppListUser, function(v, i){
						if(v.code != code){
							tmpArray.push(v);
						}
					});
					CNOA.main.common.index.DestTopAppListUser = new Array();
					CNOA.main.common.index.DestTopAppListUser = tmpArray;
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	},
	viewDiary: function(stime){
		try{
			mainPanel.closeTab("CNOA_MENU_USER_DIARY_MY");
			mainPanel.loadClass("index.php?app=user&func=diary&action=index&goDate="+stime, "CNOA_MENU_USER_DIARY_MY", lang('myDiary'), "icon-diary-my");
		}catch (e){}
	},
	goToList: function(){
		var _this = this;
		$('.level').each(function(){
           	$(this).click(function(){
				var id = $(this).attr('id');
				if (id == 'levelOne') level = 2;
				if (id == 'levelTwo') level = 1;
				if (id == 'levelThree') level = 3;
				getJson(level);
			})
        });
		function getJson(levels){
			Ext.Ajax.request({  
				url: "index.php?app=wf&func=flow&action=use&modul=todo&task=getJsonData",
				method: 'POST',
				params: {levels: levels},
				success: function(r) {
					var result  = Ext.decode(r.responseText);
					if (result.noPermit == true) {
						CNOA.msg.alert('对不起，你没有被授权使用该功能，请确认您是否有权限，或联系管理员为您开通！');
					} else {
						mainPanel.closeTab("CNOA_MENU_WF_USE_NOTDONE");
						mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=list&levels="+levels, "CNOA_MENU_WF_USE_NOTDONE", '待办流程', "icon-wf-flowToDo");
					}
				}
			});
		}
	},
	toDoList: function(idname,valueColor,dataValue,valueNum,valueName,otherValue,nameValue,idWidth) {
		 require(
	        [
	            "echarts",
	            "echarts/chart/pie",
	            "echarts/chart/funnel"
	        ],
	        function (ec) {
	        	var xZB = idWidth*0.9;
	        	var width = $(window).width();
	        	var Xyuan = width*30/1920;
	        	var Yyuan = width*40/1920;
	        	var YuanFS = width*15/1920;
	            var myChart = ec.init(document.getElementById(idname));
	            var labelTop = {
				    normal : {
				        label : {
				            show : false,
				            position : 'center',
				            formatter : '{b}',
				            textStyle: {
				                baseline : 'bottom'
				            }
				        },
				        labelLine : {
				            show : false
				        }
				    }
				};
				var labelFromatter = {
				    normal : {
				        color: '#FFFFFF',
				        label : {
				            formatter : function (params){
				                return params.value+'%';
				            },
				            textStyle: {
				            	color: '#FFFFFF',
				            	fontSize: YuanFS,
				            	baseline : 'middle'
				            }
				        }
				    }
				};
				var labelBottom = {
				    normal : {
				        color: valueColor,
				        label : {
				            show : true,
				            position : 'center'
				        },
				        labelLine : {
				            show : false
				        }
				    }
				};
	            var radius = [Xyuan, Yyuan];
	            var option = {
	               	legend: {
				        x : 'center',
				        show: false,
				        y : 'center',
				        data:[
				            dataValue
				        ]
				    },
				    title: {
				    	text: valueNum,
				       	subtext: valueName,
				        x: xZB,
				        y: '69',
				      	textAlign: 'right',
				      	textStyle : {
				        	fontSize: 13,
				           	color: '#FFFFFF',
				           	fontFamily: 'arial'
				        },
				      	subtextStyle: {
				        	fontSize: 13,
				          	color: '#FFFFFF',
				          	fontFamily: 'Microsoft YaHei'
				        }
				    },
				    series : [
				        {
				            type : 'pie',
				            center : ['50%', '50%'],
				            radius : radius,
				            x: '0%', // for funnel
				            itemStyle : labelFromatter,
				            data : [
				                {name:'other', value: otherValue, itemStyle : labelBottom},
				                {name: dataValue, value:nameValue ,itemStyle : labelTop}
				            ]
				        }
				    ]
	            };
	            myChart.setOption(option);
	        }
	    );
	},
	getMonthDate : function(datestr,month2){
		var me = this;
		$('.addRemind').click(function(){
			mainPanel.loadClass("index.php?app=user&func=diary&action=index", "CNOA_MENU_USER_DIARY_MY", '我的日记', "icon-cnoa-my-info");
		})
		var now = new Date();
		var year 		=	now.getFullYear();
        var month 		=	now.getMonth()+1;
        var date 		=	now.getDate();   
		var monthdate 	= 	year+'-'+month;
		var sday 		= 	year+'-'+month+'-'+date;
		if (datestr) {
			monthdate 	= 	datestr;
			month 	 	=	month2;
		}else{
			if(month<10){
				month = '0' + month;
				monthdate = year+'-'+month;
			}
		}
		Ext.Ajax.request({  
            url: "index.php?app=user&func=diary&action=index&task=loadMonthList",
            method: 'post',
            params: {yearmonth: monthdate},
            success: function(r) {
            	$('.rem1').unbind("click");
				$('.rem2').unbind("click");
                var result = Ext.decode(r.responseText);
                var newArray = [];
                $(result.data).each(function(i){
                	newArray.push(result.data[i].date);
                })
                $('.current-month').each(function(k){
                	var dates = Number($(this).html());
                	var days = date;
                	if(dates<10){
                		days = '0'+dates;
                	} else {
                		days = dates;
                	}
                	mydate = year+'-'+month+'-'+days;
                	if($.inArray(mydate,newArray) != '-1'){
                		$(this).addClass('content');
                		$('.today').removeClass('content');
                	}
                })
            }
        });
		Ext.Ajax.request({  
            url: "index.php?app=user&func=diary&action=index&task=loadDiary",
            method: 'post',
            params: {date: sday},
            success: function(r) {
                $('.rem1').unbind("click");
                $('.rem2').unbind("click");
                var result = Ext.decode(r.responseText);
                if(result.noPermit == true){
                	$('.rem1 span').html('无数据 ...');
	                $('.rem1 label').html('');
	                $('.rem2 span').html('无数据 ...');
	                $('.rem2 label').html('');
                } else{
                if (result.data.list != 0) {
                    $('.rem1').click(function(){me.viewDiary(sday);});
                    var time = result.data.list[0].plantime;
                    $('.rem1 span').html(time);
                    $('.rem1 label').html(result.data.list[0].content);
                    if (result.data.list.length > 1) {
                        $('.rem2').click(function(){me.viewDiary(sday);});
                        var time2 = result.data.list[1].plantime;
                        $('.rem2 span').html(time2);
                        $('.rem2 label').html(result.data.list[1].content);
                    } else {
                        $('.rem2 span').html('无数据...');
                        $('.rem2 label').html('');
                    }
                } else {
                	if(result.data.summary){
                		$('.rem1').click(function(){me.viewDiary(sday);});
	            		$('.rem1 span').html('工作总结:');
	            		$('.rem1 label').html(result.data.summary);
	            		$('.rem2 span').html('无数据 ...');
                    	$('.rem2 label').html('');
                	}else{
                		$('.rem1 span').html('无数据 ...');
	                    $('.rem1 label').html('');
	                    $('.rem2 span').html('无数据 ...');
	                    $('.rem2 label').html('');
                	}
                }
            }
            },
			failure: function(r){
				$('.rem1 span').html('无数据 ...');
                $('.rem1 label').html('');
                $('.rem2 span').html('无数据 ...');
                $('.rem2 label').html('');
			}
        });
	},

	addAttachWindow : function(job){
		var _this 	= this;
		var myDate  = new Date();
		var str 	= myDate.toLocaleDateString();
			date 	= str.replace(/\//g,"-");
		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelWidth: 70,
			labelAlign: 'right',
			waitMsgTarget: true,
			autoScroll: true,
			bodyStyle: "padding-top:10px;",
			items: [
				{
					xtype: "flashfile",
					fieldLabel: lang('addAttach'),
					name: "files",
					style: "margin-top:5px;",
					inputPre: "filesUpload"
				}
			]
		});
		
		var win = new Ext.Window({
			width: 440,
			height: 310,
			title: lang('addEditDiaryAttach'),
			resizable: true,
			modal: true,
			layout: "fit",
			items: [formPanel],
			buttons : [
				//汇报
				{
					text : lang('ok'),
					iconCls: 'icon-order-s-accept',
					handler : function() {
						submit();
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
		
		var submit = function(){
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: "index.php?app=user&func=diary&action=index&task=addEditAttach",
					waitTitle: lang('notice'),
					method: 'POST',
					waitMsg: lang('waiting'),
					params: {job: job, date:date},
					success: function(form, action) {
						CNOA.msg.notice2('添加日记附件成功');
						win.close();
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							win.close();
						}.createDelegate(this));
					}.createDelegate(this)
				});
			}
		}
	},

	toDoNotice : function(){
		var _this = this;
		$("#text").focus(function(){
		  $("#text2 span").hide();
		});
		$("#text").blur(function(){
		 var value = $(this).val();
			if(value.length > 0){
				$("#text2 span").hide();
			} else { 
				$("#text2 span").show();
					}
			});
		setTimeout(function(){
			$('.lhc_submit').click(function(){
				var content = $('#text').val(),
					myDate 	= new Date(),
					year 	= myDate.getFullYear(),
					month 	= myDate.getMonth()+1,
					day 	= myDate.getDate(),
					hours   = myDate.getHours(),
					min		= myDate.getMinutes();
					ymd     = year+'-'+month+'-'+day,
					plantime= hours+':'+min;
				if (content) {
					Ext.Ajax.request({  
					url: "index.php?app=user&func=diary&action=index&task=addEditPlan",
					method: 'POST',
					params: {job:'add',date: ymd,plandate: ymd,plantime:plantime,content:content,planetime:ymd},
					success: function(r) {
						var result = Ext.decode(r.responseText);
						if (result.noPermit == true) {
							CNOA.msg.alert('对不起，你没有被授权使用该功能，请确认您是否有权限，或联系管理员为您开通！');
						} else {
							CNOA.msg.notice2('添加日记成功');
							$('#text').val('');
							$("#text2 span").show();
							$('.rem1 span').html(hours+'时'+min+'分');
	                    	$('.rem1 label').html(content);
						}
					},
					failure: function(r){
						CNOA.msg.alert('没有权限添加日记');
					}
					});
				} else {
					CNOA.msg.alert('^_^不妨输入点再提交吧');
				}

			})
		},1000)
		
	}

}

Ext.onReady(function() {
	CNOA_main_common_index = new CNOA_main_common_indexClass();
	Ext.getCmp(CNOA.main.common.index.parentID).add(CNOA_main_common_index.mainPanel);
	Ext.getCmp(CNOA.main.common.index.parentID).doLayout();
});



