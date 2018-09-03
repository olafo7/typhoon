
$(document).ready(function(){
	function getCount(){
		$.ajax({
			url: 'm.php?app=communication&func=message&action=index&task=getAllCount',
			method: 'post',
			dataType:'json',
			success:function(json){

				var data = json.count.split('/');
				console.log($('.accept').find('img').attr('src'));
				if(data[0] != '0'){
					$('.accept').find('img').attr('src','resources/images/email_icon/accept.png');
				}else{
					$('.accept').find('img').attr('src','resources/images/email_icon/accept1.png');
				}
				$(".num:eq(0)").html(json.count);
				$(".num:eq(1)").html(json.drafts);
				$(".num:eq(2)").html(json.outbox);
				$(".num:eq(3)").html(json.recycle);
				$(".num:eq(4)").html(json.folder);
			}
		})
	}

	getCount();

	$(document).on('touchstart','#btnBack',function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	});
	$(document).on('touchstart','.accept',function(){
		window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=acceptEmail";
	});
	$(document).on('touchstart','.draft',function(){
		window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=draft";
	});
	$(document).on('touchstart','.hassend',function(){
		window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=hassend";
	});
	$(document).on('touchstart','.recycle',function(){
		window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=recycle";
	});
	$(document).on('touchstart','.mailfolder',function(){
		window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=mailfolder";
	});
})