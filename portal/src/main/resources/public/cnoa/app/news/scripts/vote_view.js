//定义全局变量：
var CNOA_news_vote_viewClass, CNOA_news_vote_view;

CNOA_news_vote_viewClass = CNOA.Class.create();
CNOA_news_vote_viewClass.prototype = {
	init : function(){
		var _this = this;

		this.id 		= CNOA.news.vote.view.id;
		this.ID_post_textarea = Ext.id();
		this.ID_post_button   = Ext.id();

		this.baseUrl 	= "index.php?app=news&func=notice&action="+CNOA.news.vote.view.fromaction;

		this.tplTitle = new Ext.XTemplate(
			'<div class="news_news_view_head" style="height:85px;">',
			'  <div class="news_news_view_head_title">{title}</div>',
			'  <div class="news_news_view_head_bt">'+lang('deptToPost')+':<a>{dept}</a>&nbsp;&nbsp;&nbsp;&nbsp;'+lang('postter')+':<a>{user}</a>&nbsp;&nbsp;&nbsp;&nbsp;'+lang('posttime2')+':<a>{posttime}</a><br>'+lang('enableTime')+':<a>{stime}</a>&nbsp;&nbsp;&nbsp;&nbsp;'+lang('endTime')+':<a>{etime}</a></div></div>'
		);

		this.tplBody = new Ext.XTemplate(
			'<div class="news_news_view_content">',
			'{content}&nbsp;',
			'</div>',
			'	<tpl if="attachCount &gt; 0">',
			'	<div style="border:2px solid #E6E6E6;height:auto;margin-top:100px;">',
			'		<div style="height:32px;background-color:#E6E6E6;line-height:32px;">',
			'			<img src="./resources/images/icons/attach.gif">',
			'			<span style="font-size:14px;font-weight:bold;">'+lang('attach')+'</span>({attachCount})',
			'		</div>',
			'		{attach}',
			'	</div>',
			'	</tpl>'
		);
		
		this.titlePanel = new Ext.Panel({
			border: false,
			region: "north",
			html: '<div class="news_news_view_head" style="height:85px;"></div>',
			listeners: {
				render : function(p){
					_this.readInfo();
				}
			}
		});
		this.newsPanel = new Ext.Panel({
			border: false
		});
		this.bodyPanel = new Ext.Panel({
			region: "center",
			border: false,
			autoScroll: true,
			items: [this.newsPanel]
		});

		this.centerPanel = new Ext.Panel({
			border: false,
			region: "center",
			layout: "border",
			items: [this.titlePanel, this.bodyPanel],
			tbar : new Ext.Toolbar({
				items: [
					{
						handler : function(button, event) {
							_this.closeTab();
						}.createDelegate(this),
						text : lang('close'),
						iconCls: 'icon-dialog-cancel'
					},
					"-",{
						handler: function (button, event){
							CNOA_news_vote_view_reader = new CNOA_news_vote_view_readerClass(this.id);
							//CNOA_news_news_reader.sort = _this.sort;
							CNOA_news_vote_view_reader.show();
						}.createDelegate(this),
						text: lang('readStatus'),
						iconCls: 'icon-news-reader'
					},
					"-",
					{
						handler: function (button, event) {
							_this.print();
						}.createDelegate(this),
						iconCls: 'icon-print',
						text : lang('print')
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 45}
				]
			})
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel]
		});
	},
	
	readInfo : function(){
		var _this = this;
		
		_this.centerPanel.getEl().mask(lang('waiting'));
		
		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=view",
			method: 'POST',
			params: {id: _this.id},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					var rd = result.data;

					_this.tplBody.overwrite(_this.newsPanel.body, rd);
					_this.tplTitle.overwrite(_this.titlePanel.body, rd);
					
					try{
						CNOA_MAIN_DESKTOP_NEWS_NOTICE_OBJ.store.reload();
					}catch(e){}
				}else{
					CNOA.msg.alert(result.msg, function(){
						_this.closeTab();
					});
				}
				_this.centerPanel.getEl().unmask();
			}
		});
	},
	
	renderPostCmp : function(){
		var _this = this;
		
		new Ext.form.TextArea({
			renderTo: "NEWS_NEWS_VIEW_COMMENT_POST_TEXTAREA",
			width: 560,
			height: 100,
			id: this.ID_post_textarea,
			style: "margin-left:5px;"
		});
		new Ext.Button({
			renderTo: "NEWS_NEWS_VIEW_COMMENT_POST_BUTTON",
			text: lang('addComment'),
			id: this.ID_post_button,
			style: "margin-left:5px;",
			handler: function(){
				_this.postComment();
			}
		});
	},
	
	closeTab : function(){
		mainPanel.closeTab(CNOA.news.vote.view.parentID.replace("docs-", ""));
	},
	
	print : function(){
		window.open("index.php?app=news&func=notice&action=index&task=print&id="+this.id,'noticept', 'width=740,height='+(screen.availHeight-120)+',left='+((screen.availWidth-740)/2)+',top=60,scrollbars=yes,resizable=yes,status=no')
	}
}
