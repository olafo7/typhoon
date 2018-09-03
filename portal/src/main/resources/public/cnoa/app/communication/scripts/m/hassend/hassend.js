var pageNow = 1;
var fenfaScroll;
//ȫ�ֱ���`����
var myFns = {
	//����ˢ��
	pull_down_refresh: function(){		
		myFns.refreshFns(); //ˢ�½���
		pageNow = 1; //���õ�ǰҳΪ1
	},
	//��������
	pullUpAction: function(){
		var limit = 12; //ÿ��ȡ15��
		var start = (pageNow-1) * limit;//���ÿ�ʼȡ����
		var search = $('#search').val(); //���̱���`���
		var url = "m.php?app=communication&func=message&action=index&task=getHassend";
		var data = {'start':start, 'limit':limit, 'search':search};
		$.post(url, data, function(json){
			myFns.showView(json,false);
		},'json');
	},
	//ˢ�½���
	refreshFns: function(){
		var search = $('#search').val(); //���̱���`���
		var limit = 12; //ÿ��ȡ15��
		var start = 0;//���ÿ�ʼȡ����
		var url = "m.php?app=communication&func=message&action=index&task=getHassend";
		$.post(url,{'start':start,'limit':limit,'search':search}, function(json){
			myFns.showView(json,true);
		},'json')
	},
	showView: function(json,isEmpty){
		if(isEmpty){ //�����������ؾ����������
			$("#main").empty();
		}
		if(json.success && json.data.length > 0){
			if(json.data.length < 12){ //�г���һҳ����������ʾ����������ʾ
				$("#pullUp").css("display","none");
	      		// $("#scroller .stop-label").html("�Ѽ�����ɣ���Ҫ�ٹ�����");
	      		$("#scroller .stop-label").css("display","none");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data,function(index,array){
				var str = '<div class="contain"><div id="tt"><div class="check" id="check"><input type="checkbox" id="one" data-id="'+array['id']+'"/><input type="hidden" value="'+array['id']+'"></div>';
					str+= '<div id="pic"><div id="img"><img src="'+array['face']+'"></div><span class="name">'+array['truename']+'</span></div></div>';
					str+= '<div id="right"><span class="shaft"></span>';
					str+= '<span class="glyphicon glyphicon-chevron-right btnGO" aria-hidden="true"></span>';
					str+= '<div class="cont"><input type="hidden" value="'+array['id']+'"><span class="title"><span class="dif">';
					str+= array['title'];
					
					//�ж��Ƿ��и���
					if(array['attach']!=0){
						str+='<img class="attach" src="resources/images/email_icon/038.png">';
					}else{
						str+='<span></span>';
					}
					str+='</span><span class="count" data-toggle="modal" data-target="#fenfaList">'+array['readed']+'</span>';
					str+='</span>';
					str+='<p class="time">'+array['posttime']+'&nbsp;&nbsp;'+array['truename']+'</p></div>';
					str+= '</div><div class="line1"></div></div></div>';
				$("#main").append(str);
			})
			pageNow += 1;
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	},
	fenfaLoaded:function(element){
		//��ʼ����iScroll�ؼ�
		fenfaScroll = new iScroll(element, {
			hScrollbar:false, vScrollbar:false,
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll',
			useTransition:false
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	}
}

$(function(){

    myFns.fenfaLoaded('fenfa-wrapper');
	// var userDataWrapperH = $(window).height()-151-361;//151-361
	var userDataWrapperH = '224px';
	$('.fenfa-wrapper').css('height', userDataWrapperH);
	 
	//���right id��ת�鿴��ϸ��test��
	$(document).on('click','.dif',function(){
		var id = $(this).parent().prev().val();
		window,location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=checkEmail&type=hassend&fids='+id+'&type=hassend';
	});
	//������Ա����
	$(document).on('touchstart','.count',function(){
		$('tbody').empty();
		var id = $(this).parent().prev().val();
		$.ajax({
			url: 'm.php?app=communication&func=message&action=index&task=getHassaw',
			dataType: 'json',
			method: 'post',
			data:{'fids':id},
			success:function(json){
				$.each(json.data,function(i,arr){
					if(arr['isread']=='0'){
						$('tbody').append('<tr><td><span style="color:#808080">'+arr['truename']+'</span></td><td>'+arr['readtime']+'</td></tr>');
					}else{
						$('tbody').append('<tr><td><span style="color:red">'+arr['truename']+'</span></td><td>'+arr['readtime']+'</td></tr>');
					}
				})
				setTimeout(function () {
			        fenfaScroll.refresh();
			    }, 100);
			}
		})
	});

	

	$(".opt").attr('disabled',true);
	
	//ת��
	$(document).on('click','#forward',function(){
		if($("input[type='checkbox']:checked").length>1){
			jNotify('ֻ��ѡ��һ���ʼ�!',{
			     autoHide : true,                // �Ƿ��Զ�������ʾ�� 
			     clickOverlay : false,            // �Ƿ񵥻����ֲ�Źر���ʾ�� 
			     MinWidth : 20,                    // ��С��� 
			     TimeShown : 1500,                 // ��ʾʱ�䣺���� 
			     ShowTimeEffect : 200,             // ��ʾ��ҳ��������ʱ�䣺���� 
			     HideTimeEffect : 200,             // ��ҳ������ʧ����ʱ�䣺���� 
			     LongTrip : 15,                    // ����ʾ����ʾ������ʱ��λ�� 
			     HorizontalPosition : "center",     // ˮƽλ��:left, center, right 
			     VerticalPosition : "center",     // ��ֱλ�ã�top, center, bottom 
			     ShowOverlay : false,                // �Ƿ���ʾ���ֲ� 
			     ColorOverlay : "#000",            // �������ֲ����ɫ 
			     OpacityOverlay : 0.3            // �������ֲ��͸���� 
			});
			return false;
		}
		var id = $("input[type='checkbox']:checked").attr('data-id');
		window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=writeEmail&emailType=outbox&opt=forward&fid='+id;
	});

	function getJsonData(url){
		search = $("#search").val();
		var limit = 12; //ÿ��ȡ12��
		var start = 0;//���ÿ�ʼȡ����
		$.ajax({
			url: url,
			dataType: 'json',
			method: 'post',
			data: {'start':start,'limit':limit,"search": search},
			beforeSend: function(){
				//�ύ��ǰ��֤
				showLoading("���ڼ��أ����Ե�...");
			},
			success:function(json){
				hideLoading();
				myFns.showView(json,false);
			}
		})
	}
	getJsonData("m.php?app=communication&func=message&action=index&task=getHassend");

	$(document).on('click','#all',function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){this.checked=true;});
			$(".opt").removeClass('btn btn-default');
			$(".opt").addClass('btn btn-primary');
			$(".opt").attr('disabled',false);
		}else{
			$("input[type='checkbox']").each(function(){this.checked=false;});
			$(".opt").removeClass('btn btn-primary');
			$(".opt").addClass('btn btn-default');
			$(".opt").attr('disabled',true);			
		}
	});
	$(document).on('touchstart','.check',function(){
		if($(this).find("input[type='checkbox']").is(':checked')){
			$(this).find("input[type='checkbox']").prop('checked',false);
			if(!$("input[type='checkbox']").is(':checked')){
				$(".opt").removeClass('btn btn-primary');
				$(".opt").addClass('btn btn-default');
				$(".opt").attr('disabled',true);
			}
			return false;
		}else{
			$(this).find("input[type='checkbox']").prop('checked',true);
			$(".opt").removeClass('btn btn-default');
			$(".opt").addClass('btn btn-primary');
			$(".opt").attr('disabled',false);
			return false;
		}
	});
	

	//���checkbox
	// $(document).on('click','#right',function(){
	// 	if($(this).prev().find("input[type='checkbox']").eq(0).is(':checked')){
	// 		$(this).prev().find("input[type='checkbox']").eq(0).prop('checked',false);
	// 		if($("input[type='checkbox']:checked").length==0){
	// 			$(".opt").removeClass('btn btn-primary');
	// 			$(".opt").addClass('btn btn-default');
	// 			$(".opt").attr('disabled',true);
	// 		}
	// 	}else{
	// 		$(this).prev().find("input[type='checkbox']").prop('checked',true);
	// 		$(".opt").removeClass('btn btn-default');
	// 		$(".opt").addClass('btn btn-primary');
	// 		$(".opt").attr('disabled',false);
	// 	}
	// });

	//����
	$(document).on('search','#search',function(){
		var search = $('#search').val(); //���̱���`���
		var limit = 12;
		var start =0;
		var url = "m.php?app=communication&func=message&action=index&task=getHassend";
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"search":search,
				"start":start,
				"limit":limit
			},
			success: function(json){
				if(json.data.length==0){
					jNotify("δ����������!",{
						autoHide : true,                // �Ƿ��Զ�������ʾ�� 
						clickOverlay : false,            // �Ƿ񵥻����ֲ�Źر���ʾ�� 
						MinWidth : 20,                    // ��С��� 
						TimeShown : 1500,                 // ��ʾʱ�䣺���� 
						ShowTimeEffect : 200,             // ��ʾ��ҳ��������ʱ�䣺���� 
						HideTimeEffect : 200,             // ��ҳ������ʧ����ʱ�䣺���� 
						LongTrip : 15,                    // ����ʾ����ʾ������ʱ��λ�� 
						HorizontalPosition : "center",     // ˮƽλ��:left, center, right 
						VerticalPosition : "center",     // ��ֱλ�ã�top, center, bottom 
						ShowOverlay : false,                // �Ƿ���ʾ���ֲ� 
						ColorOverlay : "#000",            // �������ֲ����ɫ 
						OpacityOverlay : 0.3            // �������ֲ��͸���� 
					});
					return false;
				}
				myFns.showView(json,true);
			},

			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
		pageNow = 1; //���õ�ǰҳΪ1
	});

	//������һҳ
	$(document).on('touchstart','#btnBack',function(){
		window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=emailIndex';	
	});

	//ɾ��
	$(document).on('click','#delete',function(){
		if($('#main').find('.title').length == 0){
			jNotify('ûѡ���κ���!',{
				autoHide : true,                // �Ƿ��Զ�������ʾ�� 
				clickOverlay : false,            // �Ƿ񵥻����ֲ�Źر���ʾ�� 
				MinWidth : 20,                    // ��С��� 
				TimeShown : 1500,                 // ��ʾʱ�䣺���� 
				ShowTimeEffect : 200,             // ��ʾ��ҳ��������ʱ�䣺���� 
				HideTimeEffect : 200,             // ��ҳ������ʧ����ʱ�䣺���� 
				LongTrip : 15,                    // ����ʾ����ʾ������ʱ��λ�� 
				HorizontalPosition : "center",     // ˮƽλ��:left, center, right 
				VerticalPosition : "center",     // ��ֱλ�ã�top, center, bottom 
				ShowOverlay : false,                // �Ƿ���ʾ���ֲ� 
				ColorOverlay : "#000",            // �������ֲ����ɫ 
				OpacityOverlay : 0.3            // �������ֲ��͸���� 
			});
			return false;
		}
		var fids=[];
		var check = $("input[type='checkbox']:checked").length;
		for(var i = 0;i < check;i++){
			fids.push($("input[type='checkbox']:checked:eq("+i+")").parent().children("input[type='hidden']").val());
		}
		swal({ 
		    title: "��ȷ��Ҫɾ����", 
		    type: "warning", 
		    showCancelButton: true, 
		    closeOnConfirm: false, 
		    confirmButtonText: "ɾ��", 
		    confirmButtonColor: "#ec6c62" 
		},function(){
			$.ajax({
				dataType:'json',
				data:{'fids':fids},
				method:'post',
				url:'m.php?app=communication&func=message&action=index&task=delHassend',
				success:function(json){
					window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=hassend";
				}
			})
		});
	});

	
})