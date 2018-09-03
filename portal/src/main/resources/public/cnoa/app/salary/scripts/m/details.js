$(function(){
	// 返回上一步
	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=salary&func=manage&action=mysalary&task=loadPage&from=viewSalary';
	})
})