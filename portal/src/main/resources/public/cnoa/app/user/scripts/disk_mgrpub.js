//定义全局变量：
var CNOA_user_disk_mgrpubClass, CNOA_user_disk_mgrpub;


/**
* 主面板-列表
*
*/
CNOA_user_disk_mgrpubClass = CNOA.Class.create();
CNOA_user_disk_mgrpubClass.prototype = {
	init : function(){
		var _this = this;
		
		this.date = new Date();
		this.todayIsMarkup = false;
		this.pageInit = false;
		this.nowfid = 0;
		this.nowpid = 0;
		this.viewMod = CNOA.user.disk.mgrpub.viewMod; //thumb / list / icon

		this.nowpath = '';
		
		this.storeBar = {
			word : "",
			pid : 0
		};
		
		this.ID_tree_treeRoot = Ext.id();
		this.ID_uppath = Ext.id();
		
		var ID_BTN_NEWDIR	= Ext.id();
		var ID_BTN_UPLOAD	= Ext.id();
		var ID_BTN_DELETE	= Ext.id();
		
		//查找文件
		this.ID_find_edtWord = Ext.id();
		
		
		this.baseUrl = "index.php?app=user&func=disk&action=mgrpub";

		this.treeRoot = new Ext.tree.AsyncTreeNode({
			text: lang('pubDisk'),
			id: "0",
			pid: "0",
			fid: "0",
			cls: 'folder',
			iconcls: 'folder',
			expanded: true
		});

		
		this.treeLoader = new Ext.tree.TreeLoader({
			dataUrl: this.baseUrl + "&task=getDir",
			preloadChildren: false,
			clearOnLoad: false,
			listeners:{
				"load":function(node){
					_this.showFullPath();
				}.createDelegate(this),
				"beforeload" : function(th, node, callback){
					try{
						th.baseParams.pid = node.attributes.fid;
						th.baseParams.path = node.attributes.path;
					}catch(e){}
				}
			}
		})

		
		this.dirTree = new Ext.tree.TreePanel({
			hideBorders: true,
			border: false,
			rootVisible: true,
			lines: true,
			animCollapse: false,
			animate: false,
			loader: this.treeLoader,
			root: this.treeRoot,
			autoScroll: true,
			listeners:{
				"click":function(node){
					
					_this.nowpid = node.attributes.pid;
					_this.nowfid = node.attributes.fid;
					_this.storeBar.pid = _this.nowfid
					_this.store.load({params:_this.storeBar});

					node.expand();
					_this.nowpath = node.getPath();
					_this.checkUpPathBtn();

					_this.showFullPath();
				}.createDelegate(this),
				"render" : function(){
				}
			}
		});

		this.fields = [
			{name:"id", mapping:'id'},
			{name:"pid", mapping:'pid'},
			{name:"ext", mapping:'ext'},
			{name:"name", mapping:'name'},
			{name:"size", mapping:'size'},
			{name:"type", mapping:'type'},
			{name:"downpath", mapping:'downpath'},
			{name:"downhref"},
			{name:"viewhref"},
			{name:"edithref"},
			{name:"posttime", mapping:'posttime'},
			{name:"postname"},
			{name:"vi"},
			{name:"dl"},
			{name:"sh"},
			{name:"ed"},
			{name:"fileid"},
			{name:""}
		];
		
		this.store = new Ext.data.Store({
			autoLoad : true,
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=getList&viewMod="+this.viewMod}),
			reader:new Ext.data.JsonReader({idProperty:'', totalProperty:"total",root:"data", fields: this.fields}),
			listeners:{
				load : function(th, record, e){
					var dl = th.reader.jsonData.dl;
					var dt = th.reader.jsonData.dt;
					var up = th.reader.jsonData.up;
					var mgr= th.reader.jsonData.mgr;
					if(mgr){
						_this.mgr = true;
						Ext.getCmp(ID_BTN_NEWDIR).show();
						if(_this.nowfid != undefined && _this.nowfid != 0){
							Ext.getCmp(ID_BTN_UPLOAD).show();
						}else{
							Ext.getCmp(ID_BTN_UPLOAD).hide();
						}
						Ext.getCmp(ID_BTN_DELETE).show();
						_this.dl = true;
					}else{
						_this.mgr = false;
						Ext.getCmp(ID_BTN_NEWDIR).hide();
						if(dl){
							_this.dl = true;
						}else{
							_this.dl = false;
						}
						if(dt){
							Ext.getCmp(ID_BTN_DELETE).show();
						}else{
							Ext.getCmp(ID_BTN_DELETE).hide();
						}
						if(up){
							Ext.getCmp(ID_BTN_UPLOAD).show();
						}else{
							Ext.getCmp(ID_BTN_UPLOAD).hide();
						}
					}
					try{
						_this.dirTree.expandPath(th.reader.jsonData.nowpath);
						_this.dirTree.selectPath(th.reader.jsonData.nowpath);
					}catch(e){}
					
					_this.initPreviewImage();
				}
			}
		});

		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect : false
		});

		this.colModel = new Ext.grid.ColumnModel([
			//new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', width: 1, sortable: true, hidden: true},
			{header: lang('opt'), dataIndex: 'opt', width: 160, sortable: false, renderer: this.makeOpt.createDelegate(this)},
			{header: lang('fileName'), dataIndex: 'name', width: 120, sortable: true, id: 'name', renderer: this.makeTitle.createDelegate(this)},
			{header: lang('size'), dataIndex: 'size', width: 100, sortable: true, renderer:function(v,c,record){
				var rd = record.data;
				if(rd.type == 'd'){
					return '';
				}else{
					return v;
				}
			}},
			{header: lang('type'), dataIndex: 'type', width: 100, sortable: true, renderer: this.makeType.createDelegate(this)},
			{header: lang('addTime'), dataIndex: 'posttime', width: 130, sortable: true},
			{header: lang('creater'), dataIndex: 'postname', width: 130, sortable: true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			sm : this.sm,
			hideBorders: true,
			border: false,
       		stripeRows : true,
			autoExpandColumn: 'name',
			listeners:{
				//双击  
				rowdblclick : function(grid, row){
					var fid = grid.store.getAt(row).get('id');
					var pid = grid.store.getAt(row).get('pid');
					var type = grid.store.getAt(row).get('type');

					//双击文件则不处理
					if(type == "f"){return;}

					_this.nowpid = pid;
					_this.nowfid = fid;

					var selectednode = _this.dirTree.getNodeById(fid);
					try{
						_this.nowpath = selectednode.getPath();
						_this.dirTree.selectPath(selectednode.getPath());
						selectednode.expand();
					}catch(e){}
					
					_this.store.load({params:{pid: fid}});
					Ext.getCmp(_this.ID_find_edtWord).setValue('');
					_this.storeBar.word = "";

					_this.checkUpPathBtn();

					_this.showFullPath();
				}.createDelegate(this),

				rowcontextmenu: function(client, rowIndex, e){
					e.preventDefault();//覆盖默认右键
					e.stopEvent();
					
					this.grid.getSelectionModel().selectRow(rowIndex);

					var rd = this.grid.getSelectionModel().getSelected();
			
					var contextMenu = new Ext.menu.Menu({
							items: [
								{
									text: lang('rename'),
									handler: function(){
										_this.showRenameWindow('rename', rd.get('name'), rd.get('id'), rd.get('type'));
									},
									iconCls: 'icon-utils-s-edit',
									scope: this
								},/*
								{
									text: '设置共享',
									disabled: rd.get('type')=="f"?true:false,
									handler: function(){
										_this.doShare(rd);
									},
									iconCls: 'folder-share',
									scope: this
								},*/
								'-',
								{
									text: lang('historyLog'),
									handler: function(){
										_this.logWin(rd.data);
									},
									iconCls: 'icon-application-list',
									scope: this
								},
								'-',
								{
									text: lang('historyVer'),
									hidden: rd.get('type')=="f"?false:true,
									handler: function(){
										_this.versions(rd.data);
									},
									iconCls: 'icon-applications-stack',
									scope: this
								},
								'-',
								{
									text: lang('userPermitSet'),
									hidden: rd.get('type')=="d"?false:true,
									handler: function(){
										//if(_this.mgr){
											_this.doPermit(rd.data.id, rd.data.pid);
										//}else{
										//	CNOA.msg.alert("您无权设置权限");
										//}
									},
									iconCls: 'folder-share',
									scope: this
								}
							]
						});
					contextMenu.showAt(e.getXY());
				}.createDelegate(this)
			}
		});

		
		this.dirPanel = new Ext.Panel({
			region: 'west',
			layout:'fit',
			split: true,
			border: false,
			width: 176,
			minWidth: 30,
			maxWidth: 300,
			bodyStyle: 'border-right-width:1px;',
			items: [this.dirTree],
			tbar : new Ext.Toolbar({
				style: 'border-right-width:1px;',
				items: [
					{
						handler : function(button, event) {
							_this.refreshTree();
							_this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					},("<span style='color:#999'>"+lang('clickShowDownFolder')+"</span>")
				]
			})
		});
		
		
		this.gridPanel = new Ext.Panel({
			region: "center",
			layout: "fit",
			border: false,
			items: [this.grid]
		});

		
		this.addressBar = new Ext.BoxComponent({
			autoEl: {
				tag: 'span',
				html: lang('pubDisk')
			}
		});
		
		this.thumbView = new Ext.DataView({
			store: this.store,
			style: 'overflow:auto',
			overClass: 'x-view-over',
			tpl: new Ext.XTemplate(
				'<tpl for=".">',
				'<div class="thumb-wrap" title="{altName}">',
				'<table width="96"><tr><td align="center" valign="middle" height="92" style="border: 1px solid #ECE9D8;background-color: #FFF;">',
				'<div class="thumb">',
				'<tpl if="isImg==1">',
				'<a rel="isdiskimg" href="{thumb}&target=big" title="{altName}"><img src="{thumb}" fileid="{fileid}" href="{thumb}" onerror="this.src=\'resources/images/icons_file/undefined-48.gif\'" /></a>',
				'</tpl>',
				'<tpl if="isImg!=1">',
				'<img src="{thumb}" fileid="{fileid}" href="{thumb}" onerror="this.src=\'resources/images/icons_file/undefined-48.gif\'" />',
				'</tpl>',
				'</div>',
				'</td></tr></table>',
				'<span class="x-editable name" style="white-space:normal;word-break :break-all">{name}',
				'<tpl if="type!=\'d\'">',
				'.{ext}',
				'</tpl>',
				'</span></div>',
				'</tpl>',
				'<div class="x-clear"></div>'
			),
			multiSelect: true,
			itemSelector: 'div.thumb-wrap',
			emptyText: '',
			picexts: ['gif', 'jpg', 'jpeg', 'png'],
			mimetypes: ['image/gif', 'image/jpeg', 'image/jpeg', 'image/png'],
			prepareData: function(data) {
				//data.ShortName = data.FILENAME.ellipsis(32);
				if (data.ext == null) data.ext = "null";
				var index = this.picexts.indexOf(data.ext.toLowerCase());
				if (index == -1) {
					data.thumb = 'resources/images/icons_file/' + data.ext + '-48.gif';
					data.isImg = '0';
				} else {
					data.thumb = 'index.php?app=user&func=disk&action=mgrpub&task=getThumb&fileid=' + data.fileid;
					data.isImg = '1';
				}
				data.altName = data.name.replace(/<\/?[^>]*>/g,'');
				return data;
			}
		});
		
		this.thumbView.on("dblclick",function(dataview, index, node, e){
			var rec = dataview.getRecord(node);
			var type = rec.get("type");
			var pid = rec.get("pid");
			var fid = rec.get("id");
			if(type == 'd'){
				_this.nowpid = pid;
				_this.nowfid = fid;

				var selectednode = _this.dirTree.getNodeById(fid);
				try{
					_this.nowpath = selectednode.getPath();
					_this.dirTree.selectPath(selectednode.getPath());
					selectednode.expand();
				}catch(e){}
				
				_this.store.load({params:{pid: fid}});
				Ext.getCmp(_this.ID_find_edtWord).setValue('');
				_this.storeBar.word = "";

				_this.checkUpPathBtn();

				_this.showFullPath();
			}
		},this);
		
		this.thumbView.on("contextmenu",function (dataview,index,node,e){
			var rd = dataview.getRecord(node), type = rd.get("type"), fileid = rd.get('fileid'),share = rd.get('sh'), name = rd.get('name'), id = rd.get('id'), pid = rd.get('pid');
	    	e.stopEvent();        
	    	dataview.select(node);
	    	var items = [];
	    	//下载/预览/编辑
	    	if(type == 'f'){
	    		var dhref = rd.get('downhref'), vhref=rd.get('viewhref'), ehref = rd.get('edithref');
	    		if(!Ext.isEmpty(dhref)){
	    			items.push({
						text: "下载",
						handler: function(){
							try{
								eval(dhref);
							}catch(e){}
						},
						scope: this
					});
	    		}
	    		if(!Ext.isEmpty(vhref)){
	    			items.push({
						text: "浏览",
						handler: function(){
							var isImg = rd.get("isImg");
							try{
								if(isImg == 1){
									window.open(vhref);
								}else{
									eval(vhref);
								}
							}catch(e){}
						},
						scope: this
					});
	    		}
	    		if(!Ext.isEmpty(ehref)){
	    			items.push({
						text: "编辑",
						handler: function(){
							try{
								eval(ehref);
							}catch(e){}
						},
						scope: this
					});
	    		}
    			if(share){
    				items.push({
						text: "共享",
						handler: function(){
							CNOA_user_disk_mgrpub.shareFileTo(fileid);
						},
						scope: this
					});
				}
	    	}
	    	items.push({
				text: lang('rename'),
				handler: function(){
					_this.showRenameWindow('rename', name, id, type);
				},
				iconCls: 'icon-utils-s-edit',
				scope: this
			});
	    	items.push('-');
	    	items.push({
				text: lang('historyLog'),
				handler: function(){
					_this.logWin(rd.data);
				},
				iconCls: 'icon-application-list',
				scope: this
			});
	    	items.push('-');
	    	if(type=="d"){
	    		items.push({
					text: lang('userPermitSet'),
					handler: function(){
						//if(_this.mgr){
							_this.doPermit(id, pid);
						//}else{
						//	CNOA.msg.alert("您无权设置权限");
						//}
					},
					iconCls: 'folder-share',
					scope: this
				});
	    	}else{
	    		items.push({
					text: lang('historyVer'),
					handler: function(){
						_this.versions(rd.data);
					},
					iconCls: 'icon-applications-stack',
					scope: this
				});
	    	}
	    	
	        var contextMenu = new Ext.menu.Menu({items: items});
				contextMenu.showAt(e.getXY());
	    },this);
		
	    var changeViewMod = function(viewMod){
	    	Ext.Ajax.request({  
				url: _this.baseUrl+"&task=changeViewMod",
				method: 'POST',
				params: {viewMod: viewMod},
				success: function(r) {
				}
			});
	    }
		
		this.ctPanel = new Ext.Panel({
			border: false,
			cls: "oa-disk",
			items: [this.thumbView, this.gridPanel],
			layout: "card",
			activeItem: (function(){
				if(_this.viewMod == 'thumb'){return 0;}
				if(_this.viewMod == 'list'){return 1;}
			})(),
			tbar : new Ext.Toolbar({
				items: [
					{
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					},'-',
					{
						handler : function(button, event) {
							_this.goUpPath();
						}.createDelegate(this),
						disabled: true,
						id: this.ID_uppath,
						iconCls: 'arrow-up-double',
						text : lang('up')
					},'-',
					{
						handler : function(button, event) {
							_this.showRenameWindow("add");
						}.createDelegate(this),
						id : ID_BTN_NEWDIR,
						hidden : true,
						iconCls: 'icon-disk-folder-add',
						text : lang('newFolder')
					},'-',
					{
						handler : function(button, event) {
							_this.uploadWin();
						}.createDelegate(this),
						id : ID_BTN_UPLOAD,
						hidden : true,
						iconCls: 'icon-file-up',
						text : lang('upFileFolder')
					},'-',
					{
						text: lang('del'),
						id : ID_BTN_DELETE,
						hidden : true,
						handler: function(){
							_this.del();
						},
						iconCls: 'icon-utils-s-delete',
						scope: this
					},("<span style='color:#999'>"+lang('dblToEnterNotice')+"</span>"),
					'->', 
					new Ext.Button({
			        	text: '视图',
			            iconCls:'application_view_list',                    
			            tooltip:'文件视图',            
			            scope: this,
			            menu: [
							this.thumbviewItem = new Ext.menu.CheckItem({
							    text : '缩略图',                    
							    handler : function(){
							    	this.ctPanel.getLayout().setActiveItem(0);
							    	this.viewMod = "thumb";
							    	this.store.proxy = new Ext.data.HttpProxy({url: this.baseUrl + "&task=getList&viewMod="+this.viewMod});
							    	changeViewMod(this.viewMod);
							    	this.initPreviewImage();
							    },
							    checked: this.viewMod == "thumb" ? true : false,
							    group: 'toolview',
							    scope : this
							}),                
							this.iconviewItem = new Ext.menu.CheckItem({
							    text : '图标',
							    hidden: true,
							    checked: false,
							    group: 'toolview',                   
							    handler : function(){
							    	this.ctPanel.getLayout().setActiveItem(2);
							    	this.store.proxy = new Ext.data.HttpProxy({url: this.baseUrl + "&task=getList&viewMod="+this.viewMod});
							  		this.initPreviewImage();
							   },
							    scope : this
							}),
							this.listviewItem = new Ext.menu.CheckItem({
							    text : '详细信息',
							    checked: this.viewMod == "list" ? true : false,
							    group: 'toolview',                   
							    handler : function(){
							    	this.ctPanel.getLayout().setActiveItem(1);
							    	this.store.proxy = new Ext.data.HttpProxy({url: this.baseUrl + "&task=getList&viewMod="+this.viewMod});
							    	this.viewMod = "list";
							    	changeViewMod(this.viewMod);
							    	this.initPreviewImage();
							    },
							    scope : this
							})                
			            ]
			        }),'-',{xtype: 'cnoa_helpBtn', helpid: 81}
				]
			})
		});

		this.centerPanel = new Ext.Panel({
			region: "center",
			bodyStyle: 'border-left-width:1px;overflow:auto;',
			layout: "fit",
			border: false,
			items: [this.ctPanel],
			tbar : new Ext.Toolbar({
				style: 'border-left-width:1px;',
				items: [
					lang('path')+": ", 
					this.addressBar, 
					"->", 
					lang('fileName')+":",
					{
						xtype: 'textfield',
						id: this.ID_find_edtWord,
						width: 120
					},
					'-',
					{
						xtype: 'button',
						cls: 'x-btn-over',
						text: lang('search'),
						listeners: {
							'mouseout': function(btn){
								btn.addClass('x-btn-over');
							}
						},
						handler: function(btn){
							var sWord = Ext.getCmp(_this.ID_find_edtWord).getValue();
							_this.storeBar.pid = _this.nowfid;
							_this.storeBar.word = sWord;
							_this.store.load({params: _this.storeBar});
						}
					},
					'-',{
						text: lang('clear'),
						handler: function(){
							Ext.getCmp(_this.ID_find_edtWord).setValue('');
							_this.storeBar.word = "";
							_this.store.load({
								params: {
									pid: _this.nowfid
								}
							});
						}
					}
				]
			})
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.dirPanel, this.centerPanel]
		});
	},
	
	loadFancyBox : function(){
		var _this = this;
		loadCss("scripts/jquery/fancybox/jquery.fancybox-1.3.4.css");
		loadJs("scripts/jquery/fancybox/jquery.fancybox-1.3.4.pack.js", true, function(){
			setTimeout(function(){
				_this.initPreviewImage();
			}, 500);
		});
	},
	
	initPreviewImage : function(){
		if(!$.fancybox){
			this.loadFancyBox();
		}else{
			$("a[rel=isdiskimg]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'type'				: 'image',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}//,
				//'hideOnOverlayClick': false
			});
		}
	},
	
	del : function(){
		var _this = this, rows;
		if(this.viewMod == 'list'){
			rows = _this.grid.getSelectionModel().getSelections();
		}else if(this.viewMod == 'thumb'){
			rows = _this.thumbView.getSelectedRecords();
		}
		
		if (rows.length == 0) {
			CNOA.miniMsg.alertShowAt(button, lang('mustSelectOneRow'));
		} else {
			CNOA.msg.cf(lang('confirmToDelete'), function(btn) {
				if (btn == 'yes') {
					var delIds = [];
					
					type	= rows[0].get("type");
					$.each(rows, function(k, v){
						if(v.get("type") == 'f'){
							delIds.push({id: v.get("fileid"), type: 'f'});
						}else{
							delIds.push({id: v.get("id"), type: 'd'});
						}
					});
					_this.doDelete(delIds);
				}
			});
		}
	},
	
	uploadWin : function(){
		var _this = this;
		
		var ID_NOTE = Ext.id();
		
		var note = new Ext.Panel({
			width: 150,
			layout: "fit",
			region : 'east',
			autoScroll: true,
			tbar : [{
				xtype : "box",
				autoEl : {
					tag : "div",
					html : lang('versionBZ'),
					style : "height : 22px; line-height : 22px"
				}
			}],
			border : false,
			hideBorders : true,
			items: [
				{
					xtype : "textarea",
					border : false,
					id : ID_NOTE
				}
			]
		});
		
		var upload = new Ext.ux.SwfUploadPanel({
			region:"center",
			hasFolder:true,
			border:false,
			upload_url:"../" + _this.baseUrl + "&task=upload&CNOAOASESSID="+CNOA.cookie.get("CNOAOASESSID"),
			post_params:{ pid: _this.nowfid },
			//debug: true,
			flash_url:"resources/swfupload.swf",
			single_file_select:false,
			confirm_delete:false,
			remove_completed:false,
			file_types:"*.*",
			file_types_description:lang('allType'),
			listeners:{
				fileUploadComplete:function(){
					_this.fileUploadComplete();
					win.close();
				},
				folderUploadStart:function(){
					win.el.mask(lang('refreshFolderNotice'));
				},
				folderUploadComplete:function(){
					win.el.unmask();
				},
				uploadError:function(file, error, code){
					//alert(code);
				},
				startUpload:function(th){
					win.el.mask(lang('waiting'));
					var note = Ext.getCmp(ID_NOTE).getValue();
					th.addPostParam("note", note);
				}
			}
		});
		
		var win = new Ext.Window({
			width: 600,
			height: makeWindowHeight(500),
			layout: "border",
			modal: true,
			title: lang('upFile'),
			resizable: false,
			items: [upload, note]
		}).show();
	},

	refreshTree : function(){
		
		this.treeRoot.reload();
	},

	makeTitle : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data,name,isImg;
		
		
		if(rd.type == 'd'){
			return '<img src="' + CNOA_EXTJS_PATH + '/resources/images/default/tree/folder.gif" align="absmiddle">&nbsp;' + value;
		}else{
			var index = ['jpg','png','gif','jpeg'].indexOf(rd.ext.toLowerCase());
			if (index == -1) {
				name = '<img width="22" height="22" src="resources/images/icons_file/'+rd.ext+'-22.gif" onerror="this.src=\'resources/images/icons_file/undefined-22.gif\'" align="absmiddle">&nbsp;' + value + "." + rd.ext;
				isImg = '0';
			} else {
				var src = 'index.php?app=user&func=disk&action=mgrpub&task=getThumb&fileid=' + rd.fileid;
				var altName = rd.name.replace(/<\/?[^>]*>/g,'');
				name = '<a rel="isdiskimg" href="'+src+'&target=big" title="'+altName+'"><img onmouseover="$(this).attr(\'ext:qtip\', \'<img src=\\\''+src+'&target=middle\\\' />\')" ext:qtip="" width="22" height="22" src="'+src+'" align="absmiddle"></a>&nbsp;' + value + "." + rd.ext;
				isImg = '1';
			}

			return name;
		}
	},

	makeType : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		
		if(value == 'd'){
			return lang('folder');
		}else{
			return CNOA_user_disk_common.getFileExt(rd.ext, 2);
		}
	},

	makeOpt : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var _this = this;
		var rd = record.data;
		if(rd.type == 'd'){
			return '';
		}else{
			if(rd.dl || rd.vi || rd.ed || rd.sh){
				var l = rd.downpath;
				if(rd.sh){
					l += '&nbsp;&nbsp;<a href="javascript:void(0);" onclick="CNOA_user_disk_mgrpub.shareFileTo('+rd.fileid+')">'+lang('share')+'</a>';
				}
				return l;
			}else{
				return lang('noPermit');
			}
		}
	},
	
	shareFileTo:function(fileid){
		var _this = this;
		
		var outsideWin = new Ext.Window({
			title:lang('setShare'),
			width:500,
			height:140,
			modal:true,
			resizable:false,
			layout:"fit",
			items:[
				{
					xtype:'form',
					id:'OUTSIDE_FORM_ID',
					border:false,
					items:[
						{
							xtype:'textarea',
							hideLabel:true,
							name:'outsideurl',
							width:480
						},
						{
							xtype:'button',
							text:lang('copyLink'),
							handler:function(btn){
								var outsideurl = Ext.getCmp('OUTSIDE_FORM_ID').getForm().findField('outsideurl').getValue();
								try{
									window.clipboardData.setData('text', outsideurl);
								}catch(e){
									alert(lang('noSupportCopy'));
								}
							}
						}
					]
				}
			]
		});
		
		var form =  new Ext.form.FormPanel({
			bodyStyle:"padding:10px;",
			border:false,
			waitMsgTarget:true,
			items:[
				{
					xtype:'hidden',
					name:'peopleUid'
				},
				{
					xtype:"textarea",
					width:325,
					height:50,
					fieldLabel:lang('selectPeople'),
					name:"people",
					readOnly:true,
					listeners:{
						afterrender:function(th){
							th.mon(th.el, 'click', function(){
								var ids = form.getForm().findField('peopleUid').getValue();
								new_selector("user", form.getForm().findField("people"), form.getForm().findField("peopleUid"), true,  _this.baseUrl+"&task=selector&target=user", ids);
							});
						}
					}
				},
				{
					xtype:'hidden',
					name:'deptIds'
				},
				{
					xtype:"textarea",
					width:325,
					height:50,
					fieldLabel:lang('selectDept'),
					name:"dept",
					readOnly:true,
					listeners:{
						afterrender:function(th){
							th.mon(th.el, 'click', function(){
								var ids = form.getForm().findField('deptIds').getValue();
								new_selector("dept", form.getForm().findField("dept"), form.getForm().findField("deptIds"), true,  _this.baseUrl+"&task=selector&target=dept", ids);
							});
						}
					}
				},
				{
	                xtype: 'compositefield',
	                fieldLabel: lang('expireTime'),
	                combineErrors: false,
	                items: [
	                   {
	                       xtype: 'displayfield',
	                       value: lang('visitorOver')
	                   },
	                   {
	                       name : 'disTime',
	                       xtype: 'datetimefield',
						   format:'Y-m-d H:i',
	                       width: 150
	                   },
	                   {
	                       xtype: 'displayfield',
	                       value: lang('whenNoView')
	                   }
	                ]
	            },
				{
	                xtype: 'compositefield',
	                fieldLabel: lang('expireTimes'),
	                combineErrors: false,
	                items: [
	                   {
	                       xtype: 'displayfield',
	                       value: lang('wtbo')
	                   },
	                   {
	                       name : 'disView',
	                       xtype: 'numberfield',
	                       width: 100
	                   },
	                   {
	                       xtype: 'displayfield',
	                       value: lang('whenNoView2')
	                   }
	                ]
	            },
				{
	                xtype:'compositefield',
	                fieldLabel:lang('expireTimes'),
	                combineErrors:false,
	                items:[
	                   {
	                       xtype:'displayfield',
	                       value:lang('wtdo')
	                   },
	                   {
	                       name :'disDownload',
	                       xtype:'numberfield',
	                       width:100
	                   },
	                   {
	                       xtype:'displayfield',
	                       value:lang('whenNoView2')
	                   }
	                ]
	            },
				{
					xtype: 'checkboxgroup',
					fieldLabel: lang('permitShare'),
					columns: 3,
					width:200,
					items: [
						{boxLabel: lang('download'), name: 'download'},
						{boxLabel: lang('edit'), name: 'edit'},
						{boxLabel: lang('newEmail'), name: 'email'}
					]
				},
				{
					xtype: 'checkbox',
					boxLabel: lang('makeLinkNotice'),
					name:'outsideLink',
					listeners: {
						
					}
				},
				{
					xtype:'displayfield',
					value:'('+lang('makeLinkBZ')+')'
				}
			]
		});
		
		var win = new Ext.Window({
			title:lang('setShare'),
			width:700,
			height:400,
			modal:true,
			resizable:false,
			items:form,
			layout:"fit",
			buttons:[
				{
					text: lang('share'),
					iconCls: 'icon-btn-save',
					handler: function(){
						CNOA.msg.cf(lang('suerToShare'), function(btn){
							if(btn == 'yes'){								
								form.getForm().submit({
							        url: _this.baseUrl+"&task=submitShare",
							        method: 'POST',
							        waitMsg: lang('waiting'),
							        params: {fileid:fileid},
							        success: function(form, action) {
							            if(action.result.success == true){
											if(action.result.rand == true){
												Ext.getCmp('OUTSIDE_FORM_ID').getForm().findField('outsideurl').setValue(action.result.randomUrl);
												outsideWin.show();
												win.close();
											}else{
												CNOA.msg.alert(action.result.msg, function(){
													win.close();
												});									
											};
										}
							        },
							        failure: function(form, action) {
							            CNOA.msg.alert(action.result.msg, function(){
							                
							            });
							        }
							    })
							}
						});
					}
				},
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	},

	showRenameWindow : function(tp, name, fid, type){
		var _this = this;
		
		if(!name){name = "";}
		name = name.replace(/<span\/?[^>]*>.*<\/span>/g,'');
		
		var fid = fid == undefined ? '0' : fid;

		var ID_btn_save = Ext.id();

		var submit = function(){
			var f = form.getForm();
			var nt = f.findField('name');
			
			if(nt.getValue() == name){
				CNOA.miniMsg.alertShowAt(Ext.getCmp(ID_btn_save), lang('noChange'));
				return;
			}

			if(nt.getValue().indexOf('/') !== -1){
				CNOA.miniMsg.alertShowAt(Ext.getCmp(ID_btn_save), lang('fileNameNotAllow'));
				return;
			}
			
			if(f.isValid()){
				f.submit({
					url: _this.baseUrl + "&task=rename",
					waitTitle: lang('notice'),
					method: 'POST',
					waitMsg: lang('waiting'),
					params: params,
					success: function(form, action) {
						win.close();
						_this.store.reload();
						if((type == "d") || (tp == "add")){
							_this.refreshTree();
						}
					}.createDelegate(this),
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){});
					}.createDelegate(this)
				});
			}
		}

		var form = new Ext.form.FormPanel({
			bodyStyle: "padding:10px;",
			border: false,
			waitMsgTarget: true,
			items: [
				{
					xtype: "textfield",
					width: 325,
					hideLabel: true,
					allowBlank: false,
					name: "name",
					value: name,
					listeners: {
						render : function(th){
							th.focus.defer(100,th);
						}
					}
				}
			]
		});

		var win = new Ext.Window({
			title: tp == 'add' ? lang('newFolder') : lang('rename'),
			width: 360,
			height: 130,
			modal: true,
			resizable: false,
			items: form,
			layout: "fit",
			buttons: [
				{
					text: lang('ok'),
					id: ID_btn_save,
					iconCls: 'icon-btn-save',
					handler: function(){
						submit();
					}.createDelegate(this)
				},
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			],
			keys: [{
				key: Ext.EventObject.ENTER,
				fn: submit,
				scope:this
			}]
		}).show();
		
		var params;
		if(tp == "add"){
			params = {pid: _this.nowfid, type: tp};
		}else{
			params = {fid: fid, type: tp, ftype : type};
		}
	},

	checkUpPathBtn : function(){
		var _this = this;

		if(_this.nowfid == "0" && _this.nowpid == "0"){
			Ext.getCmp(this.ID_uppath).disable();
		}else{
			Ext.getCmp(this.ID_uppath).enable();
		}
	},

	goUpPath : function(){
		var _this = this;
		if(_this.nowfid == "0" && _this.nowpid == "0"){
			_this.checkUpPathBtn();
			_this.showFullPath();
			return;
		}
		try{
			if(_this.nowpid == '0'){
				_this.treeRoot.select();
				_this.nowpath = _this.dirTree.getNodeById("0");
			}else{
				var selectednode = _this.dirTree.getNodeById(_this.nowpid);
				_this.nowpath = selectednode.getPath();
				_this.dirTree.selectPath(_this.nowpath);
				selectednode.expand();
			}
			_this.store.load({params:{pid: _this.nowpid}});
			Ext.getCmp(_this.ID_find_edtWord).setValue('');
			_this.storeBar.word = "";
			_this.nowfid = _this.nowpid;
			_this.nowpid = _this.dirTree.getNodeById(_this.nowfid).attributes.pid;
		}catch (e){_this.store.load({params:{pid: _this.nowpid}});}

		_this.checkUpPathBtn();
		_this.showFullPath();
	},

	showFullPath : function(){
		var _this = this;
		try{
			var selectednode = _this.dirTree.getNodeById(_this.nowfid);
			var path = selectednode.getPath("text");
			path = path.substr(1, path.length);
			_this.addressBar.getEl().update(path.replace(/\//g, "<span style='color:#AAAAAA;margin:0 3px;'>/</span>"));
		}catch(e){}
		
	},

	doDelete : function(ids){
		var _this = this;

		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=delete",
			method: 'POST',
			params : {ids: Ext.encode(ids)},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					_this.store.reload();
					if(type == 'd'){
						_this.refreshTree();
					}
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},
	
	//历史记录
	logWin : function(data){
		var _this = this;
		var id = data.id;
		var type = data.type;
		var fields = [
			{name : "id"},
			{name : "log"},
			{name : "truename"},
			{name : "posttime"}
		];
		
		var store = new Ext.data.Store({
			autoLoad : true,
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl + "&task=getLogList&id="+id+"&type="+type}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
			listeners : {
				
			}
		});
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect : true
		});

		var colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "id", dataIndex: 'id', width: 1, sortable: true, hidden: true},
			{header: lang('optType'), dataIndex: 'log', width: 280, sortable: false, resizable: false, menuDisabled: true},
			{header: lang('operator'), dataIndex: 'truename', width: 80, sortable: true, resizable: false, menuDisabled: true},
			{header: lang('optTime'), dataIndex: 'posttime', width: 140, sortable: true, resizable: false, menuDisabled: true}
		]);
		
		var list = new Ext.grid.GridPanel({
			autoScroll : true,
			store : store,
			loadMask : {msg: lang('waiting')},
			cm : colModel,
			//sm : sm,
			hideBorders : true,
			border : false,
       		stripeRows : true
		});
		
		var win = new Ext.Window({
			title:lang('historyLog'),
			width: 560,
			layout:"fit",
			modal: true,
			height: 400,
			resizable:false,
			border:false,
			items:[list],
			buttons:[
				{
					text: lang('close'),
					iconCls:'icon-dialog-cancel',
					handler:function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	//历史版本
	versions : function(data){
		var _this = this;
		var id = data.id;
		var fields = [
			{name : "id"},
			{name : "note"},
			{name : "num"},
			{name : "truename"},
			{name : "posttime"},
			{name : "down"},
			{name : "view"}
		];
		
		var store = new Ext.data.Store({
			autoLoad : true,
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl + "&task=getVersionsList&fileid="+id}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
			listeners : {
				
			}
		});
		
		var colModel = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', width: 1, sortable: true, hidden: true},
			{header: lang('version'), dataIndex: 'num', width: 80, sortable: false, resizable: false, menuDisabled: true},
			{header: lang('creater'), dataIndex: 'truename', width: 80, sortable: true, resizable: false, menuDisabled: true},
			{header: lang('addTime'), dataIndex: 'posttime', width: 140, sortable: true, resizable: false, menuDisabled: true},
			{header: lang('opt'), dataIndex: 'id', width: 80, sortable: true, resizable: false, menuDisabled: true, renderer: function(value, cellmeta, record){
				var rd = record.data;
				var l = "";
				//下载权限
				if(data.dl){
					l += rd.down + " ";
				}
				//查看权限
				if(data.vi){
					l += rd.view;
				}
				return l;
			}},
			{header: lang('remark'), dataIndex: 'note', width: 300, sortable: true, resizable: true, menuDisabled: true}
		]);
		
		var list = new Ext.grid.GridPanel({
			autoScroll : true,
			store : store,
			loadMask : {msg: lang('waiting')},
			cm : colModel,
			hideBorders : true,
			border : false,
       		stripeRows : true
		});
		
		var win = new Ext.Window({
			title:lang('historyVer'),
			width: 600,
			layout:"fit",
			modal: true,
			height: 400,
			resizable:false,
			border:false,
			items:[list],
			buttons:[
				{
					text: lang('close'),
					iconCls:'icon-dialog-cancel',
					handler:function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	doPermit:function(fid, pid){
		var _this = this;
		
		CNOA_user_disk_mgrpubPermitMember	= new CNOA_user_disk_mgrpubPermitMemberClass(fid);
		CNOA_user_disk_mgrpubPermitDeptment	= new CNOA_user_disk_mgrpubPermitDeptmentClass(fid);

		var member	= CNOA_user_disk_mgrpubPermitMember.mainPanel;
		var dept	= CNOA_user_disk_mgrpubPermitDeptment.mainPanel;
		
		var docFormPanel = new Ext.Panel({
			border: false,
			region : "center",
			layout : "card",
			activeItem: 0,
			items : [member, dept],
			tbar : [
				{
				    handler : function(button, event) {
						docFormPanel.getLayout().setActiveItem(0);
				    }.createDelegate(this),
				    iconCls: 'icon-roduction',
				    enableToggle: true,
				    pressed: true,
				    allowDepress: false,
				    toggleGroup: "DISK_BTN_GROUP_PERMIT",
				    text : lang('userPermitSet')
				},
				{
				    handler : function(button, event) {
						docFormPanel.getLayout().setActiveItem(1);
				    }.createDelegate(this),
				    enableToggle: true,
				    allowDepress: false,
				    toggleGroup: "DISK_BTN_GROUP_PERMIT",
				    iconCls: 'icon-roduction',
				    text : lang('deptPermitSet')
				},
				'->',
				{
					xtype: 'displayfield',
					hidden: pid == 0 ? true : false,
					value: '继承上级目录权限:'
				},
				{
					xtype: 'checkbox',
					hidden: pid == 0 ? true : false,
					id: 'pubDiskExtendCombobox',
					listeners: {
						check : function(th, checked){
							if(!checked){
								CNOA.msg.cf("是否中断与上级目录的继承关系？", function(btn){
									if(btn == 'yes'){
										CNOA.msg.cf("即将中断与上级目录的继承关系，请确定：<br />是否从上级目录中复制权限信息？", function(btn2){
											if(btn2 == 'yes'){
												_this.doExtend(fid, 0, 1);
											}else{
												_this.doExtend(fid, 0, 0);
											}
										});
									}else{
										th.suspendEvents();
										th.setValue(true);
										th.resumeEvents();
									}
								});
							}else{
								CNOA.msg.cf("是否继承上级目录的权限设置？本目录的原有权限设置会被清空！", function(btn){
									if(btn == 'yes'){
										_this.doExtend(fid, 1);
									}else{
										th.suspendEvents();
										th.setValue(false);
										th.resumeEvents();
									}
								});
							}
						}
					}
				}
			]
		});
		
		var directoryPermitWindow = new Ext.Window({
			title:lang('userPermit'),
			width: 860,
			layout:"border",
			modal: true,
			height: 400,
			resizable:false,
			scroll: true,
			border:false,
			items:[docFormPanel],
			listeners : {
				close : function(){
					Ext.Ajax.request({
						url: _this.baseUrl + "&task=deleteEmptyPermit",
						method: 'POST',
						params: {fid : fid},
						success: function(r) {
							var result = Ext.decode(r.responseText);
							if(result.success === true){
								
							}else{
								CNOA.msg.alert(result.msg, function(){});
							}
						}
					});
				}
			}
		}).show();
		
		//_this.loadFormData(rd);
	},
	
	doExtend : function(fid, extend, copy){
		Ext.Ajax.request({  
			url: this.baseUrl + "&task=doExtend",
			method: 'POST',
			params: {fid: fid, extend: extend, copy: copy},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice2(result.msg);
					CNOA_user_disk_mgrpubPermitMember.store.reload();
					CNOA_user_disk_mgrpubPermitDeptment.store.reload();
				}else{
					CNOA.msg.alert(result.msg, function(){});
				}
			}
		});
	},
	
	submit:function(rd){
		var _this = this;
		
		if (_this.docFormPanel.getForm().isValid()) {
			_this.docFormPanel.getForm().submit({
				url: _this.baseUrl+"&task=directorypermit",
				waitMsg: lang('waiting'),
				params:{fid:rd.data.fid},
				method: 'POST',	
				success: function(form, action) {
					CNOA.msg.notice(action.result.msg, lang('netDisk'));
					_this.directoryPermitWindow.close();
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}.createDelegate(this)
			});
		}
	},
	
	setfid : function(rd){
		var _this = this;
		_this.fid = rd.data.fid;
	},
	
	doShare : function(rd){
		var _this = this;

		var win;
		var loaded_p = loaded_s = false;
		
		//部门选择器
		var structTreeCheckChange = function(node, checked){
			node.expand();
			node.attributes.checked = checked;
			node.eachChild(function(child) {
				child.ui.toggleCheck(checked);
				child.attributes.checked = checked;
				child.fireEvent('checkchange', child, checked);
			});
		}
		
		var structTreeRoot = new Ext.tree.AsyncTreeNode({
			expanded: true,
			rootVisible: false,
			checked: false,
			draggable:false,
			listeners: {
				load : function(){
					loaded_s = true;
				}
			}
		});
		
		var structTreeLoader = new Ext.tree.TreeLoader({
			dataUrl: _this.baseUrl + "&task=getStructTree",
			preloadChildren: true,
			clearOnLoad: false,
			baseAttrs: { uiProvider: Ext.ux.TreeCheckNodeUI },
			listeners:{
				load : function(th, node, response){
					structTree.expandAll();
				}
			}
		});
		
		var structTree = new Ext.tree.TreePanel({
			animate:false,  
			enableDD:false,
			hideBorders: true,
			border: false,
			containerScroll: true,
			//checkModel: 'cascade',
			checkModel: 'multiple',
			loader: structTreeLoader,
			root: structTreeRoot,
			rootVisible: false,
			listeners:{
				checkchange : structTreeCheckChange
			}
		});
		
		var structPanel = new Ext.Panel({
			width: 240,
			height: 326,
			layout: "anchor",
			autoScroll: true,
			tbar : [(lang('tdcv')+"&nbsp;&nbsp;")],
			items: [structTree]
		});
		
		//人员选择器
		var selectorForPeopleStoreData=[];
		//try{selectorForPeopleStoreData=nowNode.operator.user;}catch(e){selectorForPeopleStoreData=[]}
		//try{selectorForPeopleStoreData=[{uid:"12", uname:"林晓庆"}];}catch(e){selectorForPeopleStoreData=[]}
		var selectorForPeopleStore =  new Ext.data.ArrayStore({
			proxy: new Ext.data.MemoryProxy(selectorForPeopleStoreData),
            fields: [{name:"uid", mapping: "uid"},{name:"uname", mapping: "uname"}]
        });
		
		selectorForPeopleStore.load();
		
		var selectorForPeople = new Ext.ux.form.MultiSelect({
			name: 'multiselect',
			width: 240,
			height: 326,
			valueField: 'uid',
			displayField: 'uname',
			hiddenName: 'uid',
			store: selectorForPeopleStore,
			tbar:[
				(lang('setPeople')+"&nbsp;&nbsp;"),
				"->",
				{
					xtype: "btnForPoepleSelector",
					text: lang('select'),
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					iconCls: "icon-order-s-spAdd",
					listeners: {
						"selected" : function(th, data){
							if (data.length>0){
								selectorForPeopleStore.removeAll();
								for (var i=0;i<data.length;i++){
									var records = new Ext.data.Record(data[i]);
									selectorForPeople.store.add(records);
								}
							}
						},
						"onrender" : function(th){
							
						}
					}
				},"-",
				{
					text: lang('clear'),
					iconCls: "icon-clear",
					handler: function(){
						selectorForPeopleStore.removeAll();
					}
				}
			],
			ddReorder: true,
			listeners: {
				render : function(){
					loaded_p = true;
				}
			}
		});
			
		var selectorPanel = new Ext.Panel({
			border: false,
			items: [
				{
	                margins:'5 5 5 5',
	                layout:'column',
	                autoScroll:true,
					border: false,
	                items:[{
	                    columnWidth:.50,
	                    baseCls:'x-plain',
						bodyStyle:'padding:5px 0 5px 5px',
						layout: "fit",
	                    items:[selectorForPeople]
	                },{
	                    columnWidth:.50,
	                    baseCls:'x-plain',
	                    bodyStyle:'padding:5px',
	                    items:[structPanel]
	                }]
	            }
			],
			tbar: [lang('setPermitViewNotice')]
		});

		win = new Ext.Window({
			title: lang('setShareFolder') + ' - '+rd.get('name'),
			width: 514,
			height: makeWindowHeight(469),
			modal: true,
			resizable: false,
			items: selectorPanel,
			layout: "fit",
			buttons: [
				{
					text: lang('ok'),
					iconCls: 'icon-btn-save',
					handler: function(){
						//取得树列表
						var people_data_post = new Array();
						var people_data = selectorForPeople.store.data.items;
						Ext.each(people_data, function(v, i){
							people_data_post.push({uid: v.data.uid, uname: v.data.uname});
						});
						Ext.Ajax.request({  
							url: _this.baseUrl + "&task=doShare",
							method: 'POST',
							params: {data_p: Ext.encode(people_data_post), data_s: Ext.encode(structTree.getChecked("deptId")), fid : rd.get('fid')},
							success: function(r) {
								var result = Ext.decode(r.responseText);
								if(result.success === true){
									CNOA.msg.notice(result.msg, lang('netDisk'));
									win.close();
								}else{
									CNOA.msg.alert(result.msg, function(){});
								}
							}
						});
					}
				},
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			],
			listeners: {
				show : function(th){
					th.getEl().mask(lang('waiting'));
					Ext.Ajax.request({  
						url: _this.baseUrl + "&task=getShareData",
						method: 'POST',
						params: {fid : rd.get('fid')},
						success: function(r) {
							var result = Ext.decode(r.responseText);
							if(result.success === true){
								var rd = result.data;
								
								var siv = setInterval(function(){
									if(loaded_p && loaded_p){
										clearInterval(siv);
										//设置人员数据
										try{
											for (var i=0;i<rd.data_p.data.length;i++){
												var records = new Ext.data.Record(rd.data_p.data[i]);
												selectorForPeople.store.add(records);
											}
										}catch (e){}

										//设置树数据
										try{
											for(var i=0;i<rd.data_s.length;i++){
												if(rd.data_s[i] == "0"){
													structTreeRoot.ui.toggleCheck(true);
												}else{
													structTree.getNodeById("CNOA_main_struct_list_tree_node_"+rd.data_s[i]).ui.toggleCheck(true);
												}
											}
										}catch (e){}
									}
								}, 200);
							}else{
								CNOA.msg.alert(result.msg, function(){});
							}
							th.getEl().unmask();
						}
					});
				}
			}
		}).show();
	},

	//上传完成
	fileUploadComplete : function(){
		this.store.reload();
		this.treeRoot.reload();
	}
}

/**
 * 用户权限
 */
CNOA_user_disk_mgrpubPermitMemberClass = CNOA.Class.create();
CNOA_user_disk_mgrpubPermitMemberClass.prototype = {
	init:function(fid){
		var _this = this;
		var selectOAUID = Ext.id();
		
		this.fid	= fid;
		this.submitBtnID = Ext.id();
		
		this.baseUrl = "index.php?app=user&func=disk&action=mgrpub";
		
		this.fields = [
			{name : "pid"},
			{name : "uid"},
			{name : "name"}
		];
		
		this.store = new Ext.data.GroupingStore({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + '&task=permitListByM&fid='+fid
			}),
			reader: new Ext.data.JsonReader({
				totalProperty: "total",
				root: "data",
				fields: this.fields
			}),
			listeners : {
				load : function(th, record, option) {
					Ext.fly('CNOA_USER_DISK_SELLECTALL_VIEW').on('click',function(){
						var ck = this.dom.checked;
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_VIEW]').each(function(){
							if(!$(this.dom).attr('disabled')){
								this.dom.checked = ck;
							}
						});
					});
					
					Ext.fly('CNOA_USER_DISK_SELLECTALL_EDIT').on('click',function(){
						var ck = this.dom.checked;
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_EDIT]').each(function(){
							if(!$(this.dom).attr('disabled')){
								this.dom.checked = ck;
							}
						});
					});
					
					Ext.fly('CNOA_USER_DISK_SELLECTALL_DOWNLOAD').on('click',function(){
						var ck = this.dom.checked;
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_DOWNLOAD]').each(function(){
							if(!$(this.dom).attr('disabled')){
								this.dom.checked = ck;
							}
						});
					});
					
					Ext.fly('CNOA_USER_DISK_SELLECTALL_SHARE').on('click',function(){
						var ck = this.dom.checked;
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_SHARE]').each(function(){
							if(!$(this.dom).attr('disabled')){
								this.dom.checked = ck;
							}
						});
					});
					
					Ext.fly('CNOA_USER_DISK_SELLALL_UPLOAD').on('click',function(){
						var ck = this.dom.checked;
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_UPLOAD]').each(function(){
							if(!$(this.dom).attr('disabled')){
								this.dom.checked = ck;
							}
						});
					});
					
					Ext.fly('CNOA_USER_DISK_SELLALL_DELETE').on('click',function(){
						var ck = this.dom.checked;
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_DELETE]').each(function(){
							if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}
						});
					});

					var formFieldSelectAllMGR = Ext.fly('CNOA_USER_DISK_SELLALL_MGR').on('click',function(){
						var ck = this.dom.checked;
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_VIEW]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_EDIT]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_DOWNLOAD]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_SHARE]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_UPLOAD]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_DELETE]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_ALL]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
					});
					
					var pubDiskExtendCombobox = Ext.getCmp('pubDiskExtendCombobox');
						pubDiskExtendCombobox.suspendEvents();
						pubDiskExtendCombobox.setValue(th.reader.jsonData.extend);
						pubDiskExtendCombobox.resumeEvents();
					
					var n = 0;
					Ext.each(record, function(v){
						if(v.json.extend == '1'){
							n++;
						}
					});
					if(th.getCount() > n){
						Ext.getCmp(_this.submitBtnID).enable();
					}
				}
			}
		});
				
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: true
		});
		
		this.store.load();
		
		this.cm = [
			new Ext.grid.RowNumberer(),
			{header: 'pid',dataIndex: 'pid', hidden: true},
			{header: lang('name2'),dataIndex: 'name',width: 100,sortable: true},
			{header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_VIEW'>"+lang('permit4View'), dataIndex: 'pid', width: 100, sortable: false, menuDisabled :true, renderer:this.viewfile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_EDIT'>"+lang('permit4Edit'), dataIndex: 'pid', width: 100, sortable: false, menuDisabled :true, renderer:this.editfile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_DOWNLOAD'>"+lang('permit4Down'), dataIndex: 'pid', width: 80, sortable: false, menuDisabled :true, renderer:this.downloadfile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_SHARE'>"+lang('permit4Share'), dataIndex: 'pid', width: 80, sortable: false, menuDisabled :true, renderer:this.sharefile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_USER_DISK_SELLALL_UPLOAD'>"+lang('permit4Up'), dataIndex: 'pid', width: 80, sortable: false, menuDisabled :true, renderer:this.uploadfile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_USER_DISK_SELLALL_DELETE'>"+lang('permit4Del'), dataIndex: 'pid', width: 80, sortable: false, menuDisabled :true, renderer:this.deletefile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_USER_DISK_SELLALL_MGR'>"+lang('permit4Mgr'), dataIndex: 'pid', width: 80, sortable: false, menuDisabled :true, renderer:this.selectall.createDelegate(this)},
			{header: lang('opt'), dataIndex: 'pid', width: 80, sortable: true, menuDisabled :true, renderer:this.operate.createDelegate(this)},
			{header: "", dataIndex: '', width: 1, menuDisabled: true,resizable: false}
		];
		
		this.grid = new Ext.grid.GridPanel({
			border:false,
			layout:"fit",
			autoScroll:true,
			store:this.store,
			loadMask : {msg: lang('waiting')},
			columns: this.cm,
			sm: this.sm,
       		stripeRows : true,
			hideBorders: true,
			tbar : [
				{
					text : lang('refresh'),
					iconCls: 'icon-system-refresh',
					handler : function(btn){
						_this.store.reload();
						//_this.loadFormData(_this.fid);
					}
				},"-",
				{
					text : lang('addUser'),
					iconCls: 'icon-utils-s-add',
					handler: function(){
						_this.add(fid);
					}
				},"-",
				{
					text : '&nbsp;&nbsp;'+lang('submit')+'&nbsp;&nbsp;',
					iconCls : "icon-system-menu-user",
					style: "margin-left:5px",
					cls: "x-btn-over",
					id: this.submitBtnID,
					disabled: true,
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submit(fid);
					}
				}
			]
		});
		
		this.mainPanel = new Ext.form.FormPanel({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'fit',
			autoScroll: false,
			items: [this.grid]
		})
		
		//_this.loadFormData(fid);
	},
	
	viewfile : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'",  check = rd.vi == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW"+rowIndex+"' name='mem["+value+"][vi]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_VIEW"+value+"' "+check+" "+disabled+" /></label>";
	},
		
	editfile : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'", check = rd.ed == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW"+rowIndex+"' name='mem["+value+"][ed]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_EDIT"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	downloadfile : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'", check = rd.dl == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW"+rowIndex+"' name='mem["+value+"][dl]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_DOWNLOAD"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	sharefile : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'", check = rd.sh == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW"+rowIndex+"' name='mem["+value+"][sh]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_SHARE"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	uploadfile:function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'", check = rd.up == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW"+rowIndex+"' name='mem["+value+"][up]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_UPLOAD"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	deletefile:function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'", check = rd.dt == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW"+rowIndex+"' name='mem["+value+"][dt]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_DELETE"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	selectall:function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'", check = rd.mgr == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input type='checkbox' onclick='CNOA_user_disk_mgrpubPermitMember.selectall2("+rowIndex+",this)' name='mem["+value+"][mgr]' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ALL"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	selectall2:function(rowIndex,allobj){
		var rowid = Ext.query("input[row=CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW"+rowIndex+"]");
		Ext.each(rowid, function(v, i){
			v.checked = allobj.checked;
		});
	},
	
	operate : function(value, c, record, rowIndex){
		var rd = record.json;
		if(rd.extend != '1'){
			return "<a href='javascript:void(0)' onclick='CNOA_user_disk_mgrpubPermitMember.deleteData("+value+", "+rowIndex+")'><span class='cnoa_color_red'>"+lang('del')+"</span></a>";
		}
	},
	
	deleteData : function(pid, rowIndex){
		var _this = this;
		
		CNOA.msg.cf(lang('confirmToDelete'), function(btn){
			if(btn == "yes"){
				Ext.Ajax.request({
					url: _this.baseUrl + '&task=deletePermitM&pid='+pid,
					method: "POST",
					params:{fid:_this.fid},
					success: function(r, opts) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							//var move = _this.store.getAt(rowIndex);
							//_this.store.remove(move);
							_this.store.reload();
							//_this.loadFormData(_this.fid);
						}else{
							CNOA.msg.alert(result.msg, function(){
								
							});
						}
					},
					failure: function(response, opts) {
						CNOA.msg.alert(result.msg, function(){
							_this.coststore.reload();
						});
					}
				});
			}
		});
	},
	/*
	loadFormData : function(fid){
		var _this = this;
		
		_this.mainPanel.getForm().load({
			url: _this.baseUrl+"&task=loadPermitM",
			params: {fid: fid},
			method:'POST',
			success: function(form, action){
				Ext.each(action.result.data, function(v, i){
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITMEMBER_VIEW"+v.pid).dom.checked = Number(v.vi);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITMEMBER_EDIT"+v.pid).dom.checked = Number(v.ed);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITMEMBER_DOWNLOAD"+v.pid).dom.checked = Number(v.dl);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITMEMBER_SHARE"+v.pid).dom.checked = Number(v.sh);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITMEMBER_UPLOAD"+v.pid).dom.checked = Number(v.up);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITMEMBER_DELETE"+v.pid).dom.checked = Number(v.dt);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITMEMBER_ALL"+v.pid).dom.checked = Number(v.mgr);
				});
			},
			failure: function(form, action){
				CNOA.msg.alert(action.result.msg, function(){

				});
			}.createDelegate(this)
		});
	},
	*/
	submit : function(fid){
		var _this = this;
		
		_this.mainPanel.getForm().submit({
			url: _this.baseUrl+"&task=addPermitDataM",
			method: 'POST',	
			params : {fid : fid},
			success: function(form, action) {
				CNOA.msg.notice(action.result.msg,lang('netDisk'));
				_this.store.reload();
				//_this.loadFormData(_this.fid);
			}.createDelegate(this),
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg);
			}.createDelegate(this)
		});
	},
	
	add : function(fid){
		var _this = this;
		var ID_TEXT_UID = Ext.id();
		var ID_TEXT_TRUENAME = Ext.id();
		
		var form = new Ext.form.FormPanel({
			bodyStyle: "padding:10px;",
			border: false,
			waitMsgTarget: true,
			labelWidth : 50,
			labelAlign : "right",
			items: [
				{
					xtype : "hidden",
					id : ID_TEXT_UID
				},
				{
					xtype: "textarea",
					fieldLabel: lang('setPeople'),
					height: 80,
					width: 350,
					readOnly: true,
					id : ID_TEXT_TRUENAME
				},
				{
					xtype: "btnForPoepleSelector",
					text: lang('select'),
					dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
					style: "margin-left:50px; margin-top:10px",
					listeners: {
						"selected" : function(th, data){
							var names = new Array();
							var uids = new Array();
							if (data.length>0){
								for (var i=0;i<data.length;i++){
									names.push(data[i].uname);
									uids.push(data[i].uid);
								}
							}
							Ext.getCmp(ID_TEXT_TRUENAME).setValue(names.join(","));
							Ext.getCmp(ID_TEXT_UID).setValue(uids.join(","));
						},
						"onrender" : function(th){
							
						}
					}
				}
			]
		});
		
		var win = new Ext.Window({
			title : lang('addUser'),
			width: 500,
			height: 200,
			modal: true,
			resizable: false,
			items: form,
			layout: "fit",
			buttons: [
				{
					text: lang('add'),
					iconCls: 'icon-btn-save',
					handler: function(){
						var uid = Ext.getCmp(ID_TEXT_UID).getValue();
						Ext.Ajax.request({
						    url: _this.baseUrl + "&task=addPermitM",
						    method: 'POST',
							params : {
								fid : fid,
								uid : uid
							},
						    success: function(r) {
						        var result = Ext.decode(r.responseText);
						        if(result.success === true){
									win.close();
									_this.store.reload();
									//_this.loadFormData(_this.fid);
						        }else{
						            CNOA.msg.alert(result.msg, function(){});
						        }
						    }
						});
					}.createDelegate(this)
				},
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	}
}

/**
 * 部门权限
 */
CNOA_user_disk_mgrpubPermitDeptmentClass = CNOA.Class.create();
CNOA_user_disk_mgrpubPermitDeptmentClass.prototype = {
	init:function(fid){
		var _this = this;
		var selectOAUID = Ext.id();
		
		this.fid	= fid;
		this.submitBtnID = Ext.id();
		
		this.baseUrl = "index.php?app=user&func=disk&action=mgrpub";
		
		this.fields = [
			{name : "sid"},
			{name : "uid"},
			{name : "name"}
		];
		
		this.store = new Ext.data.GroupingStore({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + '&task=permitListByS&fid='+fid
			}),
			reader: new Ext.data.JsonReader({
				totalProperty: "total",
				root: "data",
				fields: this.fields
			}),
			listeners : {
				load : function(th, record, operation) {
					
					$.each(["VIEW", "EDIT", "DOWNLOAD", "SHARE", "UPLOAD", "DELETE"], function(key, vv){
						Ext.fly('CNOA_DEPT_DISK_SELLECTALL_'+vv).on('click',function(){
							var ck = this.dom.checked;
							Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_'+vv+']').each(function(){
								if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}
							});
						});
					});

					var formFieldSelectAllMGR = Ext.fly('CNOA_DEPT_DISK_SELLECTALL_MGR').on('click',function(){
						var ck = this.dom.checked;
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_VIEW]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_EDIT]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_DOWNLOAD]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_SHARE]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_UPLOAD]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_DELETE]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
						Ext.select('input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_ALL]').each(function(){if(!$(this.dom).attr('disabled')){this.dom.checked = ck;}});
					});
					
					var n = 0;
					Ext.each(record, function(v){
						if(v.json.extend == '1'){
							n++;
						}
					});
					if(th.getCount() > n){
						Ext.getCmp(_this.submitBtnID).enable();
					}
				}
			}
		});
				
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: true
		});
		
		this.store.load();
		
		this.cm = [
			new Ext.grid.RowNumberer(),
			{header: 'sid',dataIndex: 'sid', hidden: true},
			{header: lang('deptName'),dataIndex: 'name',width: 100,sortable: true},
			{header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_VIEW'>"+lang('permit4View'), dataIndex: 'sid', width: 100, sortable: false, menuDisabled :true, renderer:this.viewfile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_EDIT'>"+lang('permit4Edit'), dataIndex: 'sid', width: 100, sortable: false, menuDisabled :true, renderer:this.editfile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_DOWNLOAD'>"+lang('permit4Down'), dataIndex: 'sid', width: 80, sortable: false, menuDisabled :true, renderer:this.downloadfile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_SHARE'>"+lang('permit4Share'), dataIndex: 'sid', width: 80, sortable: false, menuDisabled :true, renderer:this.sharefile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_UPLOAD'>"+lang('permit4Up'), dataIndex: 'sid', width: 80, sortable: false, menuDisabled :true, renderer:this.uploadfile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_DELETE'>"+lang('permit4Del'), dataIndex: 'sid', width: 80, sortable: false, menuDisabled :true, renderer:this.deletefile.createDelegate(this)},
			{header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_MGR'>"+lang('permit4Mgr'), dataIndex: 'sid', width: 80, sortable: false, menuDisabled :true, renderer:this.selectall.createDelegate(this)},
			{header: lang('opt'), dataIndex: 'sid', width: 80, sortable: true, menuDisabled :true, renderer:this.operate.createDelegate(this)},
			{header: "", dataIndex: '', width: 1, menuDisabled: true,resizable: false}
		];
		
		this.grid = new Ext.grid.GridPanel({
			border:false,
			layout:"fit",
			autoScroll:true,
			store:this.store,
			loadMask : {msg: lang('waiting')},
			columns: this.cm,
			sm: this.sm,
       		stripeRows : true,
			hideBorders: true,
			listeners:{
				cellclick:function(th, rowNum, columnNum, e){
					
				}
			},
			tbar : [
				{
					text : lang('refresh'),
					iconCls: 'icon-system-refresh',
					handler : function(btn){
						_this.store.reload();
						//_this.loadFormData(_this.fid);
					}
				},"-",
				{
					text : lang('addDept'),
					iconCls: 'icon-utils-s-add',
					handler: function(){
						_this.add(fid);
					}
				},"-",
				{
					text : '&nbsp;&nbsp;'+lang('submit')+'&nbsp;&nbsp;',
					iconCls : "icon-system-menu-user",
					style: "margin-left:5px",
					cls: "x-btn-over",
					id: this.submitBtnID,
					disabled: true,
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submit(fid);
					}
				}
			]
		});
		
		this.mainPanel = new Ext.form.FormPanel({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'fit',
			autoScroll: false,
			items: [this.grid]
		})
		
		//_this.loadFormData(fid);
	},
	
	viewfile : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'",  check = rd.vi == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW"+rowIndex+"' name='dept["+value+"][vi]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_VIEW"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	editfile : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'",  check = rd.ed == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW"+rowIndex+"' name='dept["+value+"][ed]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_EDIT"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	downloadfile : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'",  check = rd.dl == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW"+rowIndex+"' name='dept["+value+"][dl]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_DOWNLOAD"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	sharefile : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'",  check = rd.sh == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW"+rowIndex+"' name='dept["+value+"][sh]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_SHARE"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	uploadfile:function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'",  check = rd.up == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW"+rowIndex+"' name='dept["+value+"][up]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_UPLOAD"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	deletefile:function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'",  check = rd.dt == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW"+rowIndex+"' name='dept["+value+"][dt]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_DELETE"+value+"' "+check+" "+disabled+" /></label>";
	},
	
	selectall:function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.json, extend = rd.extend == "1" ? "extend='1'" : "extend='0'",  check = rd.mgr == "1" ? " checked='checked' " : "", disabled = rd.extend == '1' ? "disabled='true'" : "";
		return "<label><input "+extend+" type='checkbox' onclick='CNOA_user_disk_mgrpubPermitDeptment.selectall2("+rowIndex+",this)' name='dept["+value+"][mgr]' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_ALL"+value+"' "+check+" "+disabled+"/></label>";
	},
	
	selectall2:function(rowIndex,allobj){
		var rowid = Ext.query("input[row^=CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW"+rowIndex+"]");
		Ext.each(rowid, function(v, i){
			v.checked = allobj.checked;
		});
	},
	
	operate : function(value, c, record){
		var rd = record.json;
		if(rd.extend != '1'){
			return "<a href='javascript:void(0)' onclick='CNOA_user_disk_mgrpubPermitDeptment.deleteData("+value+")'><span class='cnoa_color_red'>"+lang('del')+"</span></a>";
		}
	},
	
	deleteData : function(sid){
		var _this = this;
		
		CNOA.msg.cf(lang('confirmToDelete'), function(btn){
			if(btn == "yes"){
				Ext.Ajax.request({
					url: _this.baseUrl + '&task=deletePermitS&sid='+sid,
					method: "POST",
					success: function(r, opts) {
						var result = Ext.decode(r.responseText);
						if(result.success === true){
							_this.store.reload();
							//_this.loadFormData(_this.fid);
						}else{
							CNOA.msg.alert(result.msg, function(){
								
							});
						}
					},
					failure: function(response, opts) {
						CNOA.msg.alert(result.msg, function(){
							_this.coststore.reload();
						});
					}
				});
			}
		});
	},
	/*
	loadFormData : function(fid){
		var _this = this;
		
		_this.mainPanel.getForm().load({
			url: _this.baseUrl+"&task=loadPermitS",
			params: {fid: fid},
			method:'POST',
			success: function(form, action){
				Ext.each(action.result.data, function(v, i){
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITDEPT_VIEW"+v.sid).dom.checked = Number(v.vi);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITDEPT_EDIT"+v.sid).dom.checked = Number(v.ed);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITDEPT_DOWNLOAD"+v.sid).dom.checked = Number(v.dl);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITDEPT_SHARE"+v.sid).dom.checked = Number(v.sh);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITDEPT_UPLOAD"+v.sid).dom.checked = Number(v.up);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITDEPT_DELETE"+v.sid).dom.checked = Number(v.dt);
					Ext.fly("CNOA_USER_DISK_MGRPUBPERMITDEPT_ALL"+v.sid).dom.checked = Number(v.mgr);
				});
			},
			failure: function(form, action){
				CNOA.msg.alert(action.result.msg, function(){

				});
			}.createDelegate(this)
		});
	},
	*/
	submit : function(fid){
		var _this = this;
		
		_this.mainPanel.getForm().submit({
			url: _this.baseUrl+"&task=addPermitDataS",
			method: 'POST',	
			params : {fid : fid},
			success: function(form, action) {
				CNOA.msg.notice(action.result.msg, lang('netDisk'));
				_this.store.reload();
				//_this.loadFormData(_this.fid);
			}.createDelegate(this),
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg);
			}.createDelegate(this)
		});
	},
	
	add : function(fid){
		var _this = this;
		var ID_TEXT_DID = Ext.id();
		
		var form = new Ext.form.FormPanel({
			bodyStyle: "padding:10px;",
			border: false,
			waitMsgTarget: true,
			labelWidth : 80,
			labelAllign : "right",
			items: [
			/*
				{
					xtype : "hidden",
					//id : ID_TEXT_DID
				},*/
				{
					xtype: 'hidden',
					name: "deptIds",
					id : ID_TEXT_DID
				},
				{
					xtype: 'textarea',
					width:300,
					height: 50,
					fieldLabel : lang('inDepartment'),
					//id: this.ID_textarea_deptNames,
					name: "deptNames",
					readOnly: true
				},
				{
					xtype: "deptMultipleSelector",
					style: "margin-left:85px; margin-bottom:4px; margin-top:5px;",
					//autoWidth: true,
					//id: this.ID_button_deptNames,
					deptListUrl : _this.baseUrl + "&task=getStructTree",
					//loader: CNOA_main_user_list.treeLoader,
					listeners:{
						"selected" : function(th, textString, idString){
							form.getForm().findField("deptNames").setValue(textString);
							form.getForm().findField("deptIds").setValue(idString);
						},
						"load" : function(th){
							//th.setSelectedIds(_this.formPanel.getForm().findField("deptIds").getValue());
						}
					}
				}/*,
				{
					xtype: "deptMultipleSelector",
					fieldLabel : "部门",
				    allowBlank:false,
				    deptListUrl: _this.baseUrl + "&task=getStructTree",
				    width: 180,
				    listeners:{
				        "selected":function(th, node){
				            if(node.attributes.selfid != ""){
								_this.formPanel.getForm().findField("deptNames").setValue(textString);
								_this.formPanel.getForm().findField("deptIds").setValue(idString);
				                Ext.getCmp(ID_TEXT_DID).setValue(node.attributes.selfid);
				            }
				        }.createDelegate(this)
				    }
				}*/
			]
		});
		
		var win = new Ext.Window({
			title : lang('addDept'),
			width: 430,
			height: 160,
			modal: true,
			resizable: false,
			items: form,
			layout: "fit",
			buttons: [
				{
					text: lang('add'),
					iconCls: 'icon-btn-save',
					handler: function(){
						var did = Ext.getCmp(ID_TEXT_DID).getValue();
						Ext.Ajax.request({
						    url:_this.baseUrl + "&task=addPermitS",
						    method: 'POST',
							params : {
								fid : fid,
								did : did
							},
						    success: function(r) {
						        var result = Ext.decode(r.responseText);
						        if(result.success === true){
									win.close();
									_this.store.reload();
									//_this.loadFormData(_this.fid);
						        }else{
						            CNOA.msg.alert(result.msg, function(){});
						        }
						    }
						});
					}.createDelegate(this)
				},
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	}
}