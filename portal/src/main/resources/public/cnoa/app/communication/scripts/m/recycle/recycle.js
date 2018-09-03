//ȫ�ֱ���
var pageNow = 1;

//ȫ�ֱ���`����
var myFns = {
	//����ˢ��
	pull_down_refresh: function(){		
		myFns.refreshFns(); //ˢ�½���
		pageNow = 1; //���õ�ǰҳΪ1
	},
	//ˢ�½���
	refreshFns: function(){
		var search = $('#search').val(); //���̱���`���
		var limit = 12; //ÿ��ȡ15��
		var start = 0;//���ÿ�ʼȡ����
		var url = "m.php?app=communication&func=message&action=index&task=getRecycle";
		$.post(url,{'start':start,'limit':limit,'search':search}, function(json){
			myFns.showView(json,true);
		},'json')
	},
	//��������
	pullUpAction:function(){
		var limit = 12; //ÿ��ȡ15��
		var start = (pageNow-1) * limit;//���ÿ�ʼȡ����
		var search = $('#search').val(); //���̱���`���
		var url = "m.php?app=communication&func=message&action=index&task=getRecycle";
		var data = {'start':start, 'limit':limit, 'search':search};
		$.post(url, data, function(json){
			myFns.showView(json,false);
		},'json');
	},
	//appen
	showView:function(json,isEmpty){
		if(isEmpty){
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
				var str = '<div class="contain"><div id="tt"><div class="check" id="check"><input type="checkbox" id="one"/><input type="hidden" value="'+array['id']+'"></div>';
					str+= '<div id="pic"><div id="img"><img src="'+array['face']+'"></div><span class="name">'+array['truename']+'</span></div></div>';
					str+= '<div id="right"><span class="shaft"></span>';
					str+= '<span class="glyphicon glyphicon-chevron-right btnGO" aria-hidden="true"></span>';
					str+= '<div class="cont"><span class="title">';
					str+= array['title'];
					//�ж��Ƿ��и���
					if(JSON.stringify(array['attach']) != '[]'){
						str+='<img class="attach" src="resources/images/email_icon/038.png">';
					}else{
						str+='<span></span>';
					}
					str+='</span>';
					str+='<p class="time">'+array['posttime']+'&nbsp;&nbsp;'+array['truename']+'</p></div>';
					str+= '</div><div class="line1"></div></div></div>';
				$("#main").append(str);
			})
			pageNow +=1;
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	}
}

$(function(){

	$(".opt").attr('disabled',true);

	function getJsonData(url){
		var search = $("#search").val();
		var limit = 12;
		var start =0;
		$.ajax({
			dataType:'json',
			method:'post',
			url:url,
			data:{'start':start,'limit':limit,'search':search},//����
			beforeSend:function(){
				showLoading('���ڼ��أ����Ե�...');
			},
			success:function(json){
				hideLoading();
				myFns.showView(json,false);
			}
		})
	}
	getJsonData('m.php?app=communication&func=message&action=index&task=getRecycle');

	//ȫѡ
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
	$(document).on('click',"input[type='checkbox']",function(){
		if(this.checked){
			$(".opt").removeClass('btn btn-default');
			$(".opt").addClass('btn btn-primary');
			$(".opt").attr('disabled',false);
		}else{
			if(!$("input[type='checkbox']").is(':checked')){
				$(".opt").removeClass('btn btn-primary');
				$(".opt").addClass('btn btn-default');
				$(".opt").attr('disabled',true);
			}
		}
	});
	//���checkbox
	$(document).on('click','#right',function(){
		if($(this).prev().find("input[type='checkbox']").eq(0).is(':checked')){
			$(this).prev().find("input[type='checkbox']").eq(0).prop('checked',false);
			if($("input[type='checkbox']:checked").length==0){
				$(".opt").removeClass('btn btn-primary');
				$(".opt").addClass('btn btn-default');
				$(".opt").attr('disabled',true);
			}
		}else{
			$(this).prev().find("input[type='checkbox']").prop('checked',true);
			$(".opt").removeClass('btn btn-default');
			$(".opt").addClass('btn btn-primary');
			$(".opt").attr('disabled',false);
		}
	});

	//����
	$(document).on('search','#search',function(){
		var search = $('#search').val(); //���̱���`���
		var limit = 12;
		var start =0;
		var url = "m.php?app=communication&func=message&action=index&task=getRecycle";
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

	$(document).on('touchstart','#btnBack',function(){
		history.go(-1);
	});

	//����վ�ָ�
	$(document).on('click','#recovery',function(){
		var fids=[];
		var check = $("input[type='checkbox']:checked").length;
		for(var i = 0;i < check;i++){
			fids.push($("input[type='checkbox']:checked:eq("+i+")").parent().children("input[type='hidden']").val());
		}
		$.ajax({
			dataType:'json',
			data:{'fids':fids},
			method:'post',
			url:'m.php?app=communication&func=message&action=index&task=recoveryMail',
			success:function(json){
				jSuccess("�ָ��ɹ�",{
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
				     OpacityOverlay : 0.3,            // �������ֲ��͸���� 
				     onClosed:function(){
				     	window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=recycle";
				     }
				});
			}
		})
	});

	//ɾ������վ�ʼ�
	$(document).on('click','#delete',function(){
		if($('#main').find('.title').length == 0){
			jNotify('û��ѡ���κ���!',{
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
				url:'m.php?app=communication&func=message&action=index&task=deleteRecoveryMail',
				success:function(json){
					window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=recycle";
				}
			})
		});
	});

	//���ȫ��
	$(document).on('click','#btnAdd',function(){
		swal({ 
		    title: "��ȷ��Ҫ���ȫ����", 
		    type: "warning", 
		    showCancelButton: true, 
		    closeOnConfirm: false, 
		    confirmButtonText: "ɾ��", 
		    confirmButtonColor: "#ec6c62" 
		},function(){
			$.ajax({
				dataType:'json',
				data:{'num':'all'},
				method:'post',
				url:'m.php?app=communication&func=message&action=index&task=deleteRecoveryMail',
				success:function(json){
					window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=recycle";
				}
			})
		});
	})

})