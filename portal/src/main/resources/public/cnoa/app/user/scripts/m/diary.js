$(function(){


	//底部添加计划
	$('#btn_actionsheet').click(function(){
	    $('#jingle_popup').show(70);
	    $('#jingle_popup_mask').show();
	  });
	$('#btn-cancel').click(function(){
	    $('#jingle_popup').hide(70);
	    $('#jingle_popup_mask').hide();
	  });
	$("#add_edit_summary").click(function(){//跳转的瞬间隐藏掉底部计划
		$('#jingle_popup').hide(70);
	    $('#jingle_popup_mask').hide();
	});
	$("#add_edit_additional").click(function(){//跳转的瞬间隐藏掉底部计划
		$('#jingle_popup').hide(70);
	    $('#jingle_popup_mask').hide();
	});

	//获取当前日期 格式yyyymmdd
	function getNowFormatDate(){
	    var day = new Date();
	    var Year = 0;
	    var Month = 0;
	    var Day = 0;
	    var CurrentDate = "";
	    Year= day.getFullYear();//支持IE和火狐浏览器.
	    Month= day.getMonth()+1;
	    Day = day.getDate();
	    CurrentDate += Year;
	    if (Month >= 10 ){
	     CurrentDate += Month;
	    }
	    else{
	     CurrentDate += "0" + Month;
	    }
	    if (Day >= 10 ){
	     CurrentDate += Day ;
	    }
	    else{
	     CurrentDate += "0" + Day ;
	    }
	    return CurrentDate;
	}

	//获取url参数值
	function GetQueryString(key){
	    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	}

	//对添加修改总结按钮附加参数和跳转地址
	$("#add_edit_summary").attr("href","m.php?app=user&func=diary&action=index&task=loadPageAddSummary&date="+GetQueryString('date'));
	$("#add_edit_additional").attr("href","m.php?app=user&func=diary&action=index&task=addEditAdditional&date="+GetQueryString('date'));

	//判断是否满足补充日记条件
	if(GetQueryString('date') >= getNowFormatDate()){
		//不满足日记补充条件
		$("#add_edit_additional").hide();
	}else{
		//满足日记补充条件
		$("#add_edit_additional").show();
	}

	//显示日记数据
	function view_diary(){
		var dc = Math.random();//测试  随机数防止缓存
		var post_url = "m.php?app=user&func=diary&action=index&task=viewDiary&_dc="+dc+"";
		$.post(post_url, {date:GetQueryString('date')},
			function(json){
				//alert(JSON.stringify(json)); //打印json
				if(json['success'] && json.data['plan'] != ""){
			      if(json.data['summary']){
			      	var summary = "<a class=\"list-group-item\">"+json.data['summary']+"</a>";
			      	$("#wrapper .summary").append(summary); //追加总结 
			      }
			      if(json.data['attach']){//附件存在
			      	var attach = "<p class=\"list-group-item disabled\">附件</p>"+json.data['attach'];
			      	$("#wrapper .fujian").append(attach); //追加附件
			      	$("#wrapper .fujian > a").attr("class","list-group-item attach");//附加属性
			      }
			      var commentTotal = "<a class=\"list-group-item disabled commentTotal\">评论("+json.data['commentTotal']+")";
			      $("#wrapper .comment").append(commentTotal); //追加评论数量 
			      if(json.data['comment'] != ""){//评论不等于空就追加  	
			      	$.each(json.data['comment'],function(index,array){ //遍历
				      	//拼接获取的JSON数据
				      	var comment = "<a class=\"list-group-item\"><div class=\"row\">";
				      	comment += "<div class=\"col-xs-2\"><img src=\""+array['face']+"\" style=\"width:40px; height:40px;\"></div>";
				      	comment += "<div class=\"col-xs-10\"><h3>"+array['truename']+"["+array['deptName']+"]</h3>";
				      	comment += "<p><span class=\"content\">"+array['content']+"</span><span class=\"posttime\">"+array['posttime']+"</span></p></div></div></a>";
				      	$("#wrapper .comment").append(comment); //追加评论 
				      })
			      }
			      if(json.data['additional'] != ""){//日记补充
			      	$.each(json.data['additional'],function(index,array){ //遍历
				      	//拼接获取的JSON数据
				      	var additional = "<a class=\"list-group-item\">"+(index+1)+"."+array['content']+"</a>";
				      	$("#wrapper .additional").append(additional); //追加今日计划  
				      })
			      }

			      $.each(json.data['plan'],function(index,array){ //遍历
			      	//拼接获取的JSON数据
			      	var plan = "<a class=\"list-group-item\">"+(index+1)+"."+array['content']+"</a>";
			      	$("#wrapper .plan").append(plan); //追加今日计划  
			      })
			    //追加数据完成再调用设定屏幕高度
			    loaded();
			    }else{
			    	return false;
			    }
			},"json");
	}	

	//从日记列表-日记详情默认调用
	view_diary();

})