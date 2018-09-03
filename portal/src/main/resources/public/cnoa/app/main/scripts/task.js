/**
 * 计划任务For Application: main
 */

//任务一：启动在线人数计时器
//如果不是在小秘书中打开

window.CNOA_ONLINEDLOGINNOTICED = false;
var CNOA_ONLINENUM_REFRESH_FUNCTION = function(noshow){
	Ext.Ajax.request({  
		url: 'index.php?task=getonlinecount',
		method: 'GET',
		success: function(r) {
			var list = Ext.decode(r.responseText);

			CNOA_SYSTEM_ONLINE_LIST = new Array();

			for (var i=0;i<list['oluids'].length;i++){
				CNOA_SYSTEM_ONLINE_LIST.push(list['oluids'][i]);
			}
			
			var ntxt = "";
			if(Number(list['mlnums'])>1){
				if(noshow !== true && !window.CNOA_ONLINEDLOGINNOTICED){
					//CNOA.miniMsg.alertShowAt(ID_CNOA_main_mainPanel_btn_updateOnline, "提醒：<br>您的帐号在多处登陆", 30, -70, 'bottom');
				}

				ntxt = " + <span class='cnoa_color_red'>["+list['mlnums']+"]</span>";
			}
			
			var btn = Ext.getCmp(ID_CNOA_main_mainPanel_btn_updateOnline);
			//btn.onlinenum   = Number(list['oluids'].length);
			//btn.myonlinenum = Number(list['mlnums']);
			//btn.setText(lang('onlineCount') + ": ["+Number(list['oluids'].length)+"]" + ntxt);
		}.createDelegate(this)
	});
};
var CNOA_main_mainPanel_refreshTimer = setInterval("CNOA_ONLINENUM_REFRESH_FUNCTION();", CNOA.config.onlineRefreshTime);
setTimeout(function(){CNOA_ONLINENUM_REFRESH_FUNCTION();}, 1000);
