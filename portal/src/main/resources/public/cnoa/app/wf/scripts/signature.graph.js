/**
 * 图形签章
 */
var CNOA_signature_graphClass, CNOA_wf_signature_graph;
CNOA_signature_graphClass = CNOA.Class.create();
CNOA_signature_graphClass.prototype = {
	init : function(btn){
		var me = this;
		
		this.btn = btn;
		this.btn_oname = btn.attr('oname');
		this.btn_id = btn.attr('id');

		this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new";
		
		this.store = new Ext.data.JsonStore({
			url: this.baseUrl + '&task=getSignature',
			root: 'data',
			fields: ['signature', 'url', 'isUsePwd']
		});
		this.store.load();
		
		this.tpl = new Ext.XTemplate(
			'<tpl for=".">',
				'<div class="thumb-wrap" id="{signature}">',
					'<div class="thumb"><img src="{url}" title="{signature}"></div>',
					'<span class="x-editable">{signature}</span>',
				'</div>',
			'</tpl>',
			'<div class="x-clear"></div>'
		);
		
		this.ID_view = Ext.id();
		this.signatureView = new Ext.Panel({
			cls: 'img-chooser-view',
			border: false,
			autoScroll: true,
			items: new Ext.DataView({
				id: this.ID_view,
				singleSelect: true,
				overClass:'x-view-over',
				itemSelector: 'div.thumb-wrap',
				emptyText : '<div style="padding:10px;">' + lang('noGraphicSignature') + '</div>',
				store: this.store,
				tpl: this.tpl,
				listeners : {
					'dblclick' : function(){
						var isUsePwd = me.getIsUsePassword();
						if (isUsePwd == '1') {
							me.checkpwd('show');
						} else {
							me.showSignature();
							me.win.close();
						}
					},
					'selectionchange' : function(){
						Ext.getCmp(me.sure).setDisabled(false);
					}
				}
			})
		});
		this.sure = Ext.id();
		this.win = new Ext.Window({
			title: lang('signatureList1'),
			height: 267,
			width: 340,
			layout: 'fit',
			modal: true,
			resizable: false,
			items: [this.signatureView],
			buttons: [
				{
					text: lang('clear'),
					iconCls: 'icon-clear',
					handler: function(){
						var isUsePwd = me.getIsUsePassword();
						if (isUsePwd == '1') {
							me.checkpwd('clear');
						} else {
							me.clearSignature();
							me.win.close();
						}
					}
				},
				{
					text: lang('ok'),
					iconCls: 'icon-dialog-ok-apply',
					disabled: true,
					id: this.sure,
					handler: function(){
						var isUsePwd = me.getIsUsePassword();
						if (isUsePwd == '1') {
							me.checkpwd('show');
						} else {
							me.showSignature();
							me.win.close();
						}
					}
				},
				
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						me.win.close();
					}
				}
			]
		});
	},
	
	show : function(){
		this.win.show();
	},
	
	bindImg : function(img){
		var me = this;

		var id = img.attr('id');
		var btn = $("input[id='" + id + "']");
		
		//拖动
		var oname = img.attr('oname');
		var form = $(".wf_form_content");
		var hide = $(".wf_form_content input:[type='hidden'][oname='" + oname + "']");
		var src = img.attr('src').replace(/^http:\/\/.*(file.*)/ig, "$1");
		var X = btn.parent().position().left;
		var Y = btn.parent().position().top;
		loadJs('scripts/dragdrop/0.6.js', true, function(){
			var maxX = form.width();
			var maxY = form.height();
			var dd = new Dragdrop({
				target : img[0],
				area : [-X+10, maxX-X-40, -Y+10, maxY-Y-10],
				callback : function(obj){
					hide.attr('value', 'url:' + src + ';left:'+ obj.moveX +';top:' + obj.moveY);
				}
			});	
		});
		
		//添加图片的双击事件，以便更换签章
		img.dblclick(function(){
			me.btn = btn;
			me.show();
		});
	},
	
	showSignature : function(){
		var me = this;
		var selNode = Ext.getCmp(this.ID_view).getSelectedNodes()[0]; //获取选取的签章
		var src = $(selNode).find('img').attr('src').replace(/^http:\/\/.*(file.*)/ig, "$1");	
		var oname = this.btn_oname;	
		var X = this.btn.parent().position().left;
		var Y = this.btn.parent().position().top;
		var img = $($('img#'+this.btn_id)[0]);	//是否插入签章
		
		img.remove();
		img = $('<img src="' + src + '" oname="' + oname + '" id="' + this.btn_id + '" ext:qtip="' + lang('holdDownMouse') + '" />');
		this.btn.before(img);	//插入图片
		this.btn.hide(); //隐藏按钮
		
		var img = $(".wf_form_content img[oname='" + oname + "']");	//获取图片
		var hide = $(".wf_form_content input:[type='hidden'][oname='" + oname + "']");
		var left = 0;
		var top = -(img.height()/2+8);
		var style = 'cursor:move; float:left; position:absolute; left:' + left + 'px; top:' + top + 'px;';
		img.attr('style', style);	//使图片浮动
		hide.attr('value', 'url:' + src + ';left:'+ left +';top:' + top);
		
		//拖动
		var form = $(".wf_form_content[class*=wf_form_content_deal]");
		loadJs('scripts/dragdrop/0.6.js', true, function(){
			var maxX = form.width();
			var maxY = form.height();
			var dd = new Dragdrop({
				target : img[0],
				area : [-X+10, maxX-X-40, -Y+10, maxY-Y-10],
				callback : function(obj){
					hide = $(".wf_form_content input:[type='hidden'][name='" + me.btn_id + "']");
					hide.attr('value', 'url:' + src + ';left:'+ obj.moveX +';top:' + obj.moveY);
				}
			});	
		});
		
		//添加图片的双击事件，以便更换签章
		img.dblclick(function(){
			CNOA_wf_signature_graph = new CNOA_signature_graphClass(me.btn);
			CNOA_wf_signature_graph.show();
		});
	},
	
	clearSignature : function(){
		var me = this;
		var img = $('img#'+this.btn_id);
		img.remove();
		$('input[type=hidden][name='+this.btn_id+']').val("");
		
		var width = this.btn.css('width');
		var height = this.btn.css('height');
		this.btn.attr('style', '');
		this.btn.css({'width':width, 'height':height});
		this.btn.show();
	},
	
	checkpwd : function(act){
		var me = this;
		
		var promptWin = new Ext.MessageBox.promptWindow({
			multiline: false,
			width: 200,
			title: lang('pleaseUserPassword'),
			resizable: false,
			txCfg:{
				inputType: 'password'
			},
			listeners: {
				'submit' : function(th, text){
					Ext.Ajax.request({
						url: 'index.php?app=my&func=info&action=index&task=checkPassword',
						method: 'POST',
						params: {
							text: text
						},
						success: function(res, opt){
							result = Ext.decode(res.responseText);
							if (result.success === true) {
								if (act == 'show') {
									me.showSignature();
								}else if(act=='clear'){
									me.clearSignature();
								}
								me.win.close();
								promptWin.close();
							}
							else if (result.failure === true) {
								CNOA.msg.alert(lang('enterWrongPassword'), result.msg);
							}
						}
					});
				}
			}
		}).show();
	},

	getIsUsePassword : function(){
		var me = this,
			selectId = Ext.getCmp(this.ID_view).getSelectedRecords()[0].id,
			data = me.store.getById(selectId).data;

		return data.isUsePwd;
	}
}
