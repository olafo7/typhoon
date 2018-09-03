queryBusinessData = function(bindfunc, engineId, uFlowId, flowId){
	if ( bindfunc == 'salaryApprove' ) {
		//专门为薪酬管理模块使用，
		var baseUrl = "index.php?app=wf&func=flow&action=use&modul=new&task=getBusinessData&bindfunc=" + bindfunc
					+ '&salaryApproveId=' + engineId + '&uFlowId=' + uFlowId;
	} else if ( bindfunc == 'userReadlySubmit' || bindfunc == 'userSubmit' ) {
		var baseUrl = "index.php?app=wf&func=flow&action=use&modul=new&task=getBusinessData&bindfunc="+bindfunc + '&userCid=' + engineId + '&uFlowId=' + uFlowId;
	} else {
		var baseUrl = "index.php?app=wf&func=flow&action=use&modul=new&task=getBusinessData&bindfunc="+bindfunc + '&flowId=' + flowId+ '&uFlowId=' + uFlowId;
	}

	//生成option选项
	var formatOption = function(data, display, value){
		var options = '<option value="">&nbsp;</option>';
		if(data.length === undefined) return ''; 
		for(var i=0,len=data.length;i<len;++i){
			var obj = data[i];
			if(obj['default']){
				options += '<option value="'+obj[value]+'" selected>'+obj[display]+'</option>';
			}else{
				options += '<option value="'+obj[value]+'">'+obj[display]+'</option>';
			}
		}
		return options;
	};
	
	//绑定事件
	var bindEvent = function(obj, field, fieldId){
		obj.change(function(){
			$('#wf_field_'+fieldId).val($(this).find('option:selected').text());
			change(field, fieldId);
		});
	}
	
	//改变有依赖关系的值
	var change = function(field, fieldId){
		if(field.relative !== undefined){
			var relative=field.relative, level=relative.level;
			data = relative.keys+"="+$('#wf_engine_field_'+fieldId).val();
			$.ajax({
				url:baseUrl+"&level="+level,
				type: 'POST',
				data: data,
				success: function(msg){
					dealMsg(msg);
				}
			});
		}
	};

	//绑定事件。选择车牌号时 自动加载对应的司机，若没有则加载全部，
	var bindChangeEvent = function(obj, field, fieldId, bindFieldId){
		obj.bind('change', function(){
			var value = obj.attr('value');
			Ext.Ajax.request({
				url: "index.php?app=wf&func=flow&action=use&modul=getBindDriver",
				method: 'post',
				params: {cid: value},
				success: function(response){
					var response = Ext.decode(response.responseText),
						data = response.data;

					if (data != undefined) {
						var options = '<option value="">&nbsp;</option>';
						$('#wf_engine_field_' + bindFieldId +' option').length = 0;
						
						for (var i = 0, len = data.length; i < len; i++) {
							var temp = data[i];

							options += '<option value="'+temp.value+'">'+temp.display+'</option>';
						}

						$('#wf_engine_field_' + bindFieldId).html(options);
					}
				}
			});
		})
	}

	//处理返回值
	var dealMsg = function(msg){
		var msg = $.parseJSON(msg);
		if(typeof msg !== "object") return ;
		for(var fieldId in msg){
			var field = msg[fieldId], ID_field = 'wf_field_'+fieldId, ID_engine_field='wf_engine_field_'+fieldId;
			var fieldObj = $('#'+ID_field);

			if(fieldObj.length==0) continue;
			if(fieldObj.attr('readonly')){
				//插入隐藏input
				var val = fieldObj.val(), data=field['data'], value;
				for(var i in data){
					if(eval("data[i]."+field['display']+"==val")){
						eval("value=data[i]."+field['value']+";");
						break;
					}
				}
				fieldObj.after('<input type="hidden" id="'+ID_engine_field+'" name="'+ID_engine_field+'" value="'+val+'" />');
				change(field, fieldId);
			}else{
				//判断表单是否初次加载
				if(fieldObj.get(0).nodeName==='SELECT'){
					var def = '';
					var f = '';
					// 判断是否有默认值（车辆申请）
					if(bindfunc=='admCar'){
						$.each(field.data,function(i,v){
							if(field.display == 'name'){
								f = 'name';
							}else if(field.display == 'carnumber'){
								f = 'carnumber'; 
							}
							if(v['default']){
								def = v[f];
							}
						});
					}
					
					//插入隐藏input
					fieldObj.after('<input type="hidden" id="'+ID_field+'" name="'+ID_field+'" value="'+def+'" />');
					fieldObj.attr('id', ID_engine_field);
					fieldObj.attr('name', ID_engine_field);
				}else{
					//清空数据
					if(bindfunc!='admCar'){
						fieldObj.val('');
					}
					fieldObj = $('#'+ID_engine_field);
				}
				//插入选项
				var options = formatOption(field['data'], field['display'], field['value']);
				
				if (field['engineField'] == 'admCarNum') {
					bindChangeEvent(fieldObj, field, fieldId, field['bindFieldId']);
				}
				fieldObj.html(options);
				//绑定事件
				bindEvent(fieldObj, field, fieldId);
			}
		}
	};

	//薪酬管理，重新赋值操作。
	var dealActual = function(actualSum, actualFieldId){
		var fieldObj = $('#wf_field_' + actualFieldId);

		if ( ! fieldObj.attr('readonly') ) {
			fieldObj.val(actualSum);
		}
	};

	//薪酬管理，重新赋值操作。
	var dealShould = function(shouldSum, shouldFieldId){
		var fieldObj = $('#wf_field_' + shouldFieldId);

		if ( ! fieldObj.attr('readonly') ) {
			fieldObj.val(shouldSum);
		}
	};
	
	//初始化表单选择数据
	$.ajax({
		url: baseUrl+"&level=init",
		success: function(msg){
			if ( bindfunc == 'salaryApprove' ) {
				var msg = Ext.decode(msg),
					checked = Ext.encode(msg.checkIdea),
					actual = msg.actual,
					actualSum = actual.actualSum,
					actualFieldId = actual.fieldId,
					should = msg.should,
					shouldSum = should.shouldSum,
					shouldFieldId = should.fieldId;

				dealMsg(checked);
				if ( actualFieldId != 0 ) {
					dealActual(actualSum, actualFieldId);
				} 
				if ( shouldFieldId != 0 ) {
					dealShould(shouldSum, shouldFieldId);
				}
			} else if( bindfunc == 'userReadlySubmit' || bindfunc == 'userSubmit' ) {
				var msg = Ext.decode(msg),
					checked = Ext.encode(msg.checkIdea),
					result = msg.result;

				dealMsg(checked);

				for (var i in result ) {
					var fieldObj = $('#wf_field_' + i);

					if ( ! fieldObj.attr('readonly') ) {
						fieldObj.val(result[i]);
					}
				}
			} else {
				dealMsg(msg);
			}
		}
	});
}


