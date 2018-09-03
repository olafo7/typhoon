/**
 * office签章 
 */
var CNOA_wf_signature_officeClass, CNOA_wf_signature_office;
CNOA_wf_signature_officeClass = CNOA.Class.create();
CNOA_wf_signature_officeClass.prototype = {
	init : function(){
		/*
		if(!Ext.isIE){
			CNOA.msg.alert('请用IE内核的浏览器，才能实现盖章功能', function(){

			});
			return 0;
		}*/
		var _this = this;
		
		this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new";
		
		this.graphSeal();

	},
	
	//图形签章
	graphSeal : function(){
		var _this = this;
		
		var store = new Ext.data.JsonStore({
			url: this.baseUrl + '&task=getSignature',
			root: 'data',
			fields: ['signature', 'url']
		});
		store.load();
		
		var tpl = new Ext.XTemplate(
			'<tpl for=".">',
				'<div class="thumb-wrap" id="{signature}">',
					'<div class="thumb"><img src="{url}" title="{signature}"></div>',
					'<span class="x-editable">{signature}</span>',
				'</div>',
			'</tpl>',
			'<div class="x-clear"></div>'
		);
		
		this.ID_view = Ext.id();
		var signatureView = new Ext.Panel({
			cls: 'img-chooser-view',
			border: false,
			autoScroll: true,
			items: new Ext.DataView({
				id: this.ID_view,
				singleSelect: true,
				overClass:'x-view-over',
				itemSelector: 'div.thumb-wrap',
				emptyText : '<div style="padding:10px;">' + lang('noGraphicSignature') + '</div>',
				store: store,
				tpl: tpl,
				listeners : {
					'dblclick' : function(){
						_this.checkpwd('show');
					}
				}
			})
		});
		
		this.win = new Ext.Window({
			title: lang('signatureList1'),
			height: 267,
			width: 340,
			layout: 'fit',
			modal: true,
			resizable: false,
			items: [signatureView],
			buttons: [
				{
					text: lang('clear'),
					iconCls: 'icon-clear',
					handler: function(){
						_this.checkpwd('clear');
					}
				},
				{
					text: lang('ok'),
					iconCls: 'icon-dialog-ok-apply',
					handler: function(){
						_this.checkpwd('show');
					}
				},
				
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						_this.win.close();
					}
				}
			]
		}).show();
	},
	
	//验证密码
	checkpwd : function(opt){
		var _this = this;
		
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
								var selNode = Ext.getCmp(_this.ID_view).getSelectedNodes()[0]; //获取选取的签章
								var baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
								var src = baseURI + $(selNode).find('img').attr('src').replace(/^http:\/\/.*(file.*)/ig, "$1");
								_this.addPicture("mark", src, 5);
								_this.win.close();
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
	
	//插入图片
	addPicture : function(strMarkName, strImgPath, vType){
		var weboffice = $("#CNOA_WEBOFFICE").get(0);
		var doctype = weboffice.DocType;
		if (doctype == 11) {
			weboffice.SetFieldValue(strMarkName, "", "::ADDMARK::");
			var obj = new Object(weboffice.GetDocumentObject());
			var pBookMarks = obj.Bookmarks;
			var pBookM = pBookMarks(strMarkName);
			var pRange = pBookM.Range;
			var pRangeInlines = pRange.InlineShapes;
			var pRangeInline = pRangeInlines.AddPicture(strImgPath);
			pRangeInline.ConvertToShape().WrapFormat.TYPE = vType;
			delete obj;
		}else if(doctype==12){
			weboffice.SetFieldValue("", "::JPG::"+strImgPath, "");
		}
	}
}