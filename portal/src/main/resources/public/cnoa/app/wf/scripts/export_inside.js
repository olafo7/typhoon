var resizWidth, resizeContentWidth;

$(document).ready(function(){
	resizeContentWidth = function (newValue){
		//var total = $("#wf_export_total");
		//total.css("width", 640+(newValue-100)*6);
		//resizWidth(640+(newValue-100)*6);
	}
	resizWidth = function(width){
//		$("#form_body").find("table,div,tr,td,input,textarea,select,span").each(function(){
//			if($(this).attr('owidth') > width){
//				$(this).width(width);
//			}else{
//				$(this).width($(this).attr('owidth'));
//			};
//		})
	}

	var initRadioCheckboxShowHide = function(){
		var els = $(".wf_form_content input:hidden:[showhide=true]");
		els.each(function(){
			var shows, hides, stmp, htmp;
			
			shows = stmp = $(this).attr('display').split(",");
			hides = htmp = $(this).attr('undisplay').split(",");
			
			if($(this).attr('fromtype')=='checkbox' && $(this).attr('checkboxck') == '0'){
				hides = stmp;
				shows = htmp;
			}
			
			try{
				for(var i=0;i<shows.length;i++){
					$(".wf_form_content span[class=group][oname="+shows[i]+"]").attr("showhide", "show").show();
				}
			}catch(e){}
			try{
				for(var i=0;i<hides.length;i++){
					$(".wf_form_content span[class=group][oname="+hides[i]+"]").attr("showhide", "hide").hide();
				}
			}catch(e){}
		});
	};
	
	var storeOwidth = function(){
		$("#form_body").find("table,div,tr,td,input,textarea,select,span").each(function(){
			$(this).attr('owidth', $(this).width());
		})
	}
	
	$.post("index.php?app=wf&func=flow&action=use&modul=todo&task=loadFormHtmlView", {type: "done", flowId: flowId, uFlowId: uFlowId, stepId: stepId},
	function(data){
		
		$('#wf_form_content').html(data.data.formHtml + '<div style="clear:both;"></div>');
		storeOwidth();
		
		var pageset = Ext.decode(data.pageset);
		
		
	 	var page = pageset.pageSize;
	   	var width = 794;
	  	var height = 1123;
		//设置纸张大小
		switch(page){
			case 'a1page' :
				if(pageset.pageDir == 'lengthways'){
					width = 2245;
					height = 3174;
				}else{
					width = 3174;
					height = 2245;
				}
			break;
			case 'a2page' :
				if(pageset.pageDir == 'lengthways'){
					width = 1587;
					height = 2245;
				}else{
					width = 2245;
					height = 1587;
				}
			break;
			case 'a3page' :
				if(pageset.pageDir == 'lengthways'){
					width = 1123;
					height = 1587;
				}else{
					width = 1587;
					height = 1123;
				}
			break;
			case 'a4page' :
				if(pageset.pageDir == 'lengthways'){
					width = 794;
					height = 1123;
				}else{
					width = 1123;
					height = 794;
				}
			break;
			case 'a5page' :
				if(pageset.pageDir =='lengthways'){
					width = 559;
					height = 794;
				}else{
					width = 794;
					height = 559;
				}
			break;
		}
		
		//设置边距大小
		var up = Math.ceil(pageset.pageUp*3.4);
		var down = Math.ceil(pageset.pageDown*3.4);
		var left = Math.ceil(pageset.pageLeft*3.4);
		var right = Math.ceil(pageset.pageRight*3.4);
	
		var padding = up+'px '+right+'px '+down+'px '+left+'px';
		
		pageBody = $("#wf_export_total");
		
		pageBody.css('padding', padding);
	   		   
		pageBody.width(width-left-right);
		
		if(Ext.isIE){
			
		}
		
		//签章显示
		try {
			var sealData = $(".wf_form_content input[sealstoredata=true]");
			if (sealData.length > 0) {
				//加载JS
				loadJs('app/wf/scripts/signature.electron.js', true, function(){
					CNOA_wf_signature_electron = new CNOA_signature_electronClass();
					CNOA_wf_signature_electron.SetSealValue(sealData);
				});
			}
		}catch(e){}

		initRadioCheckboxShowHide();
		
	}, "json");
	
	var prame = {uFlowId: uFlowId, flowId: flowId, flowType: flowType, tplSort: tplSort};
	$.post("index.php?app=wf&func=flow&action=use&modul=done&task=printList", prame, function(data){
		$(data.step).insertAfter($("#step_body").find("tr:last"));
		$(data.event).insertAfter($("#event_body").find("tr:last"));
		$(data.read).insertAfter($("#read_body").find("tr:last"));
	}, "json");
	
	var tinit = new top.init();
	tinit.bindEvent(window);
	
});
